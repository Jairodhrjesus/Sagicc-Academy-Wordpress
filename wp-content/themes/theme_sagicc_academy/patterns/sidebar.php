<?php
/**
 * Title: Sidebar Sagicc Academy
 * Slug: theme_sagicc_academy/sidebar
 * Categories: header, custom
 */

// Idioma actual (Polylang o cookie fallback)
$lang = function_exists('pll_current_language') ? pll_current_language() : (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], array('es', 'en')) ? $_COOKIE['lang'] : 'es');

// URLs de idioma para Polylang / Fallback
$es_url = '';
$en_url = '';

if (function_exists('pll_the_languages')) {
	$raw_languages = pll_the_languages(array('raw' => 1));
	$es_url = isset($raw_languages['es']) ? $raw_languages['es']['url'] : '';
	$en_url = isset($raw_languages['en']) ? $raw_languages['en']['url'] : '';
}

$current_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
if (empty($es_url)) {
	if (preg_match('#^/en(/|$)#', $current_uri)) {
		$es_url = preg_replace('#^/en(/|$)#', '$1', $current_uri);
	} else {
		$es_url = $current_uri;
	}
	if (empty($es_url)) {
		$es_url = '/';
	}
}
if (empty($en_url)) {
	if (preg_match('#^/en(/|$)#', $current_uri)) {
		$en_url = $current_uri;
	} else {
		$en_url = '/en' . $current_uri;
	}
}

// Traducciones en formato clave -> valor
$translations = array(
	'es' => array(
		'dashboard.title' => 'Dashboard',
		'dashboard.search' => 'Buscar',
		'dashboard.section_learn' => 'Aprender',
		'dashboard.courses' => 'Cursos',
		'dashboard.routes' => 'Rutas',
		'dashboard.videos' => 'Videos',
		'dashboard.guides' => 'Guías',
		'dashboard.docs' => 'Documentación',
		'dashboard.interactive' => 'Interactivo',
		'dashboard.certificates' => 'Certificados',
		'dashboard.section_participate' => 'Participar',
		'dashboard.support' => 'Soporte',
		'dashboard.support_url' => 'https://sagicc.co/es/soporte/',
		'dashboard.updates' => 'Updates Sagicc',
		'dashboard.updates_url' => 'https://news.sagicc.co/announcements',
		'dashboard.feature_ideas' => 'Mejoras Sagicc',
		'dashboard.feature_ideas_url' => 'https://news.sagicc.co/b/n0enqj0g/feature-ideas/idea/new',
		'dashboard.logout' => 'Cerrar Sesión',
		'dashboard.profile' => 'Perfil',
		'auth.login' => 'Iniciar Sesión',
		'auth.logout_confirm_title' => '¿Cerrar sesión?',
		'auth.logout_confirm_desc' => '¿Estás seguro de que quieres salir de tu cuenta?',
		'auth.logout_confirm_btn' => 'Sí, salir',
		'auth.logout_cancel_btn' => 'Cancelar',
	),
	'en' => array(
		'dashboard.title' => 'Dashboard',
		'dashboard.search' => 'Search',
		'dashboard.section_learn' => 'Learn',
		'dashboard.courses' => 'Courses',
		'dashboard.routes' => 'Paths',
		'dashboard.videos' => 'Videos',
		'dashboard.guides' => 'Guides',
		'dashboard.docs' => 'Documentation',
		'dashboard.interactive' => 'Interactive',
		'dashboard.certificates' => 'Certificates',
		'dashboard.section_participate' => 'Participate',
		'dashboard.support' => 'Support',
		'dashboard.support_url' => 'https://sagicc.co/en/support/help/',
		'dashboard.updates' => 'Sagicc Updates',
		'dashboard.updates_url' => 'https://news.sagicc.co/announcements',
		'dashboard.feature_ideas' => 'Feature Ideas',
		'dashboard.feature_ideas_url' => 'https://news.sagicc.co/b/n0enqj0g/feature-ideas/idea/new',
		'dashboard.logout' => 'Logout',
		'dashboard.profile' => 'Profile',
		'auth.login' => 'Login',
		'auth.logout_confirm_title' => 'Logout?',
		'auth.logout_confirm_desc' => 'Are you sure you want to sign out?',
		'auth.logout_confirm_btn' => 'Yes, logout',
		'auth.logout_cancel_btn' => 'Cancel',
	)
);

// Helper de traducción local
$t = function ($key) use ($translations, $lang) {
	return isset($translations[$lang][$key]) ? $translations[$lang][$key] : $key;
};

// Rutas de WordPress
$lang_home = function_exists('pll_home_url') ? pll_home_url($lang) : home_url($lang === 'en' ? '/en/' : '/');

$dashboard_url = $lang_home;

$courses_archive = get_post_type_archive_link('sfwd-courses');
$courses_url = $courses_archive ? $courses_archive : home_url( ($lang === 'en' ? '/en' : '') . '/courses/' );

$routes_url = home_url( ($lang === 'en' ? '/en' : '') . '/routes/' );

$videos_archive = get_post_type_archive_link('video');
$videos_url = $videos_archive ? $videos_archive : home_url( ($lang === 'en' ? '/en' : '') . '/video/' );

$guides_archive = get_post_type_archive_link('guia');
$guides_url = $guides_archive ? $guides_archive : home_url( ($lang === 'en' ? '/en' : '') . '/guia/' );

$docs_url = 'https://docs.sagicc.co';
$interactive_url = '#';
$certificates_url = trailingslashit($lang_home) . 'certificates/';
$support_url = $t('dashboard.support_url');
$updates_url = $t('dashboard.updates_url');
$ideas_url = $t('dashboard.feature_ideas_url');

// Obtener datos del usuario
$is_logged_in = is_user_logged_in();
$user_profile_url = $is_logged_in ? home_url('/profile/') : home_url('/login/');
$user_title = $is_logged_in ? $t('dashboard.profile') : $t('auth.login');
$user_avatar = '';

if ($is_logged_in) {
	$current_user = wp_get_current_user();
	$avatar_url = get_avatar_url($current_user->ID, array('size' => 32));
	if ($avatar_url) {
		$user_avatar = '<img src="' . esc_url($avatar_url) . '" class="w-8 h-8 rounded-full object-cover border border-gray-100" />';
	}
}

if (empty($user_avatar)) {
	$icon = $is_logged_in ? 'fa-user-gear' : 'fa-user-lock';
	$user_avatar = '<span class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-secondary group transition-colors"><i class="fa-solid ' . esc_attr($icon) . ' text-xs"></i></span>';
}

$theme_uri = get_stylesheet_directory_uri();
?>

<aside id="sidebar"
	class="hidden lg:flex w-72 border-r border-gray-100 flex-col h-screen sticky top-0 bg-white transition-all duration-300 ease-in-out font-sans">
	<div class="p-6 flex flex-col h-full overflow-hidden">
		<!-- LOGO SECTION -->
		<div class="flex items-center gap-2 mb-8 px-2 min-h-[48px]">
			<a href="<?php echo esc_url($dashboard_url); ?>" id="sidebar-logo"
				class="flex items-center gap-2 overflow-hidden transition-all duration-300 hover:opacity-80 active:scale-[0.98]">
				<img src="<?php echo esc_url($theme_uri . '/assets/Sagicc-Academy-Logo.svg'); ?>" alt="Sagicc Academy"
					class="h-10 w-auto min-w-[40px] full-logo" />
				<img src="<?php echo esc_url($theme_uri . '/assets/isotipo-sagicc-academy.svg'); ?>"
					alt="Sagicc Academy" class="h-10 w-auto min-w-[40px] isotype-logo hidden" />
			</a>
		</div>

		<div id="sidebar-content" class="flex-1 overflow-y-auto overflow-x-hidden scrollbar-hide">
			<!-- NAVIGATION: MAIN -->
			<nav class="space-y-1 mb-10">
				<a href="<?php echo esc_url($dashboard_url); ?>"
					class="flex items-center gap-3 px-3 py-2.5 bg-gray-50 text-secondary rounded-lg font-bold text-base group"
					title="<?php echo esc_attr($t('dashboard.title')); ?>">
					<i class="fa-solid fa-table-columns w-6 text-sm text-center"></i>
					<span
						class="sidebar-text transition-opacity duration-300"><?php echo esc_html($t('dashboard.title')); ?></span>
				</a>
				<a data-search-trigger
					class="flex items-center gap-3 px-3 py-2.5 text-gray-400 hover:bg-gray-50 hover:text-secondary rounded-lg font-bold text-base transition-all group cursor-pointer"
					title="<?php echo esc_attr($t('dashboard.search')); ?>">
					<i class="fa-solid fa-magnifying-glass w-6 text-sm text-center"></i>
					<span
						class="sidebar-text transition-opacity duration-300 whitespace-nowrap"><?php echo esc_html($t('dashboard.search')); ?></span>
					<span
						class="sidebar-text ml-auto text-xs border border-gray-200 px-2 py-0.5 rounded text-gray-300 font-medium opacity-0 group-hover:opacity-100 lg:block hidden">⌘P</span>
				</a>
			</nav>

			<!-- NAVIGATION: LEARN -->
			<div class="mb-10">
				<p
					class="sidebar-text px-3 text-sm font-black text-gray-300 uppercase mb-4 whitespace-nowrap overflow-hidden">
					<?php echo esc_html($t('dashboard.section_learn')); ?>
				</p>
				<nav class="space-y-1">
					<a href="<?php echo esc_url($courses_url); ?>"
						class="flex items-center gap-3 px-3 py-2 text-gray-500 hover:bg-gray-50 hover:text-secondary rounded-lg font-bold text-base transition-all group"
						title="<?php echo esc_attr($t('dashboard.courses')); ?>">
						<i class="fa-solid fa-graduation-cap w-6 text-sm text-center"></i>
						<span
							class="sidebar-text transition-opacity duration-300 whitespace-nowrap"><?php echo esc_html($t('dashboard.courses')); ?></span>
					</a>
					<a href="<?php echo esc_url($routes_url); ?>"
						class="flex items-center gap-3 px-3 py-2 text-gray-500 hover:bg-gray-50 hover:text-secondary rounded-lg font-bold text-base transition-all group"
						title="<?php echo esc_attr($t('dashboard.routes')); ?>">
						<i class="fa-solid fa-route w-6 text-sm text-center"></i>
						<span
							class="sidebar-text transition-opacity duration-300 whitespace-nowrap"><?php echo esc_html($t('dashboard.routes')); ?></span>
					</a>
					<a href="<?php echo esc_url($videos_url); ?>"
						class="flex items-center gap-3 px-3 py-2 text-gray-500 hover:bg-gray-50 hover:text-secondary rounded-lg font-bold text-base transition-all group"
						title="<?php echo esc_attr($t('dashboard.videos')); ?>">
						<i class="fa-solid fa-play w-6 text-sm text-center"></i>
						<span
							class="sidebar-text transition-opacity duration-300 whitespace-nowrap"><?php echo esc_html($t('dashboard.videos')); ?></span>
					</a>
					<a href="<?php echo esc_url($guides_url); ?>"
						class="flex items-center gap-3 px-3 py-2 text-gray-500 hover:bg-gray-50 hover:text-secondary rounded-lg font-bold text-base transition-all group"
						title="<?php echo esc_attr($t('dashboard.guides')); ?>">
						<i class="fa-solid fa-book-open w-6 text-sm text-center"></i>
						<span
							class="sidebar-text transition-opacity duration-300 whitespace-nowrap"><?php echo esc_html($t('dashboard.guides')); ?></span>
					</a>
					<a href="<?php echo esc_url($docs_url); ?>" target="_blank" rel="noopener noreferrer"
						class="flex items-center gap-3 px-3 py-2 text-gray-500 hover:bg-gray-50 hover:text-secondary rounded-lg font-bold text-base transition-all group"
						title="<?php echo esc_attr($t('dashboard.docs')); ?>">
						<i class="fa-solid fa-file-lines w-6 text-sm text-center"></i>
						<span
							class="sidebar-text transition-opacity duration-300 whitespace-nowrap"><?php echo esc_html($t('dashboard.docs')); ?></span>
						<i
							class="fa-solid fa-up-right-from-square text-[10px] ml-auto opacity-0 group-hover:opacity-40 sidebar-text transition-all"></i>
					</a>
					<a href="<?php echo esc_url($interactive_url); ?>"
						class="flex items-center gap-3 px-3 py-2 text-gray-500 hover:bg-gray-50 hover:text-secondary rounded-lg font-bold text-base transition-all group"
						title="<?php echo esc_attr($t('dashboard.interactive')); ?>">
						<i class="fa-solid fa-computer-mouse w-6 text-sm text-center"></i>
						<span
							class="sidebar-text transition-opacity duration-300 whitespace-nowrap"><?php echo esc_html($t('dashboard.interactive')); ?></span>
					</a>
					<a href="<?php echo esc_url($certificates_url); ?>"
						class="flex items-center gap-3 px-3 py-2 text-gray-500 hover:bg-gray-50 hover:text-secondary rounded-lg font-bold text-base transition-all group"
						title="<?php echo esc_attr($t('dashboard.certificates')); ?>">
						<i class="fa-solid fa-award w-6 text-sm text-center"></i>
						<span
							class="sidebar-text transition-opacity duration-300 whitespace-nowrap"><?php echo esc_html($t('dashboard.certificates')); ?></span>
					</a>
				</nav>
			</div>

			<!-- NAVIGATION: PARTICIPATE -->
			<div class="mb-10">
				<p
					class="sidebar-text px-3 text-sm font-black text-gray-300 uppercase mb-4 whitespace-nowrap overflow-hidden">
					<?php echo esc_html($t('dashboard.section_participate')); ?>
				</p>
				<nav class="space-y-1">
					<a href="<?php echo esc_url($support_url); ?>" target="_blank" rel="noopener noreferrer"
						class="flex items-center gap-3 px-3 py-2 text-gray-500 hover:bg-gray-50 hover:text-secondary rounded-lg font-bold text-base transition-all group"
						title="<?php echo esc_attr($t('dashboard.support')); ?>">
						<i class="fa-solid fa-headset w-6 text-sm text-center"></i>
						<span
							class="sidebar-text transition-opacity duration-300 whitespace-nowrap"><?php echo esc_html($t('dashboard.support')); ?></span>
						<i
							class="fa-solid fa-up-right-from-square text-[10px] ml-auto opacity-0 group-hover:opacity-40 sidebar-text transition-all"></i>
					</a>
					<a href="<?php echo esc_url($updates_url); ?>" target="_blank" rel="noopener noreferrer"
						class="flex items-center gap-3 px-3 py-2 text-gray-500 hover:bg-gray-50 hover:text-secondary rounded-lg font-bold text-base transition-all group"
						title="<?php echo esc_attr($t('dashboard.updates')); ?>">
						<i class="fa-solid fa-bullhorn w-6 text-sm text-center"></i>
						<span
							class="sidebar-text transition-opacity duration-300 whitespace-nowrap"><?php echo esc_html($t('dashboard.updates')); ?></span>
						<i
							class="fa-solid fa-up-right-from-square text-[10px] ml-auto opacity-0 group-hover:opacity-40 sidebar-text transition-all"></i>
					</a>
					<a href="<?php echo esc_url($ideas_url); ?>" target="_blank" rel="noopener noreferrer"
						class="flex items-center gap-3 px-3 py-2 text-gray-500 hover:bg-gray-50 hover:text-secondary rounded-lg font-bold text-base transition-all group"
						title="<?php echo esc_attr($t('dashboard.feature_ideas')); ?>">
						<i class="fa-solid fa-lightbulb w-6 text-sm text-center"></i>
						<span
							class="sidebar-text transition-opacity duration-300 whitespace-nowrap"><?php echo esc_html($t('dashboard.feature_ideas')); ?></span>
						<i
							class="fa-solid fa-up-right-from-square text-[10px] ml-auto opacity-0 group-hover:opacity-40 sidebar-text transition-all"></i>
					</a>
				</nav>
			</div>
		</div>
	</div>

	<!-- FOOTER ACTIONS -->
	<div class="mt-auto border-t border-gray-100 py-4 bg-white z-10 px-4">
		<div class="footer-icons flex items-center justify-between transition-all duration-300 w-full">
			<div class="flex items-center justify-center w-10 h-10">
				<button id="sidebar-toggle-btn"
					class="flex items-center justify-center p-2 text-gray-400 hover:text-secondary transition-colors">
					<i class="fa-solid fa-window-maximize text-sm"></i>
				</button>
			</div>

			<div class="flex items-center justify-center w-10 h-10">
				<a href="<?php echo esc_url($user_profile_url); ?>"
					class="flex items-center justify-center p-1 hover:bg-gray-50 rounded-full transition-all"
					title="<?php echo esc_attr($user_title); ?>">
					<?php echo $user_avatar; ?>
				</a>
			</div>

			<div class="flex items-center justify-center w-10 h-10">
				<!-- Language Switcher Container -->
				<div class="relative lang-switcher-container">
					<button
						class="lang-toggle flex items-center justify-center p-2 text-gray-400 hover:text-secondary transition-colors"
						title="<?php echo esc_attr($lang === 'es' ? 'Idioma' : 'Language'); ?>">
						<i class="fa-solid fa-language text-lg"></i>
					</button>

					<!-- Dropdown -->
					<div
						class="lang-dropdown hidden absolute bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden min-w-[140px] z-50 bottom-full mb-2 left-1/2 -translate-x-1/2">
						<a href="<?php echo esc_url($es_url); ?>" data-lang="es"
							class="lang-option w-full px-4 py-2.5 text-left text-xs font-bold transition-all flex items-center gap-2 <?php echo $lang === 'es' ? 'bg-gray-50 text-secondary' : 'text-gray-400 hover:bg-gray-50 hover:text-secondary'; ?>">
							<span class="text-xs text-gray-400">ES</span>
							Español
							<?php if ($lang === 'es'): ?>
								<i class="fa-solid fa-check text-[10px] ml-auto text-secondary"></i>
							<?php endif; ?>
						</a>
						<a href="<?php echo esc_url($en_url); ?>" data-lang="en"
							class="lang-option w-full px-4 py-2.5 text-left text-xs font-bold transition-all flex items-center gap-2 <?php echo $lang === 'en' ? 'bg-gray-50 text-secondary' : 'text-gray-400 hover:bg-gray-50 hover:text-secondary'; ?>">
							<span class="text-xs text-gray-400">EN</span>
							English
							<?php if ($lang === 'en'): ?>
								<i class="fa-solid fa-check text-[10px] ml-auto text-secondary"></i>
							<?php endif; ?>
						</a>
					</div>
				</div>
			</div>

			<?php if ($is_logged_in): ?>
				<div class="flex items-center justify-center w-10 h-10">
					<button id="logout-btn"
						class="flex items-center justify-center p-2 text-gray-400 hover:text-red-500 transition-colors"
						title="<?php echo esc_attr($t('dashboard.logout')); ?>">
						<i class="fa-solid fa-right-from-bracket text-sm"></i>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<!-- LOGOUT CONFIRMATION MODAL -->
	<?php if ($is_logged_in): ?>
		<div id="logout-modal" class="fixed inset-0 z-[999999] hidden anim-fade-in group/modal">
			<div class="absolute inset-0 bg-secondary/40 backdrop-blur-sm"></div>
			<div class="absolute inset-0 flex items-center justify-center p-4">
				<div
					class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl scale-95 opacity-0 modal-content transition-all duration-300">
					<div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mb-6 mx-auto">
						<i class="fa-solid fa-right-from-bracket text-2xl"></i>
					</div>
					<h3 class="text-2xl font-black text-secondary text-center mb-2">
						<?php echo esc_html($t('auth.logout_confirm_title')); ?>
					</h3>
					<p class="text-gray-400 text-center font-medium mb-8">
						<?php echo esc_html($t('auth.logout_confirm_desc')); ?>
					</p>
					<div class="flex flex-col w-full gap-3">
						<button id="confirm-logout" data-logout-url="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>"
							class="w-full py-4 bg-red-500 text-white rounded-2xl font-black text-base hover:bg-red-600 transition-all shadow-lg shadow-red-500/20 active:scale-95">
							<?php echo esc_html($t('auth.logout_confirm_btn')); ?>
						</button>
						<button id="cancel-logout"
							class="w-full py-4 bg-gray-50 text-gray-400 rounded-2xl font-black text-base hover:bg-gray-100 hover:text-secondary transition-all active:scale-95">
							<?php echo esc_html($t('auth.logout_cancel_btn')); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</aside>

<style>
	/* Ajustes cuando el sidebar está contraído */
	.sidebar-collapsed .lang-dropdown {
		bottom: auto !important;
		top: -20px !important;
		left: 100% !important;
		transform: none !important;
		margin-left: 10px !important;
	}

	/* COLLAPSED STATE STYLES */
	#sidebar.sidebar-collapsed {
		width: 6rem;
	}

	.sidebar-collapsed .p-6 {
		padding-left: 0.75rem;
		padding-right: 0.75rem;
	}

	.sidebar-collapsed nav a {
		justify-content: center;
		padding-left: 0;
		padding-right: 0;
	}

	.sidebar-collapsed .sidebar-text {
		display: none;
	}

	.sidebar-collapsed i {
		margin: 0 !important;
		width: auto !important;
		font-size: 1.1rem;
	}

	.sidebar-collapsed .footer-icons {
		padding-left: 0;
		padding-right: 0;
	}

	.sidebar-collapsed .footer-icons button {
		padding: 0.75rem;
		width: 100%;
	}

	.sidebar-collapsed .full-logo {
		display: none !important;
	}

	.sidebar-collapsed .isotype-logo {
		display: block !important;
		margin: 0 auto;
	}

	.sidebar-collapsed #sidebar-logo {
		justify-content: center;
		width: 100%;
	}
</style>

<script>
	(function () {
		function setupSidebar() {
			const sidebar = document.getElementById("sidebar");
			const toggle = document.getElementById("sidebar-toggle-btn");
			const texts = document.querySelectorAll(".sidebar-text");
			const logoutBtn = document.getElementById("logout-btn");

			if (!sidebar || !toggle) return;
			if (sidebar.dataset.initialized) return;

			const applyState = (isCollapsed) => {
				const footerIcons = document.querySelector(".footer-icons");
				if (isCollapsed) {
					sidebar.classList.add("sidebar-collapsed");
					sidebar.classList.replace("w-72", "w-24");
					texts.forEach((t) => t.classList.add("hidden"));
					footerIcons?.classList.add("flex-col", "gap-4", "items-center");
					footerIcons?.classList.remove("justify-between");
				} else {
					sidebar.classList.remove("sidebar-collapsed");
					sidebar.classList.replace("w-24", "w-72");
					texts.forEach((t) => t.classList.remove("hidden"));
					footerIcons?.classList.remove("flex-col", "gap-4", "items-center");
					footerIcons?.classList.add("justify-between");
				}
				localStorage.setItem("sidebar-collapsed", String(isCollapsed));
			};

			toggle.addEventListener("click", () => {
				const nowCollapsed = !sidebar.classList.contains("sidebar-collapsed");
				applyState(nowCollapsed);
			});

			logoutBtn?.addEventListener("click", () => {
				const modal = document.getElementById("logout-modal");
				if (modal) {
					modal.classList.remove("hidden");
					modal.classList.add("flex");
					sidebar.classList.add("z-[999999]");
					setTimeout(() => {
						modal.querySelector(".modal-content")?.classList.remove("scale-95", "opacity-0");
					}, 10);
				}
			});

			const modal = document.getElementById("logout-modal");
			const cancelBtn = document.getElementById("cancel-logout");
			const confirmBtn = document.getElementById("confirm-logout");

			const closeModal = () => {
				modal?.querySelector(".modal-content")?.classList.add("scale-95", "opacity-0");
				setTimeout(() => {
					modal?.classList.add("hidden");
					modal?.classList.remove("flex");
					sidebar.classList.remove("z-[999999]");
				}, 200);
			};

			cancelBtn?.addEventListener("click", closeModal);
			modal?.addEventListener("click", (e) => {
				if (e.target === modal) closeModal();
			});

			confirmBtn?.addEventListener("click", () => {
				confirmBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
				window.location.href = confirmBtn.dataset.logoutUrl;
			});

			sidebar.dataset.initialized = "true";

			if (localStorage.getItem("sidebar-collapsed") === "true") {
				sidebar.classList.add("duration-0");
				applyState(true);
				setTimeout(() => sidebar.classList.remove("duration-0"), 50);
			}
		}

		function setupLanguageSwitchers() {
			const containers = document.querySelectorAll(".lang-switcher-container");

			containers.forEach((container) => {
				const toggle = container.querySelector(".lang-toggle");
				const dropdown = container.querySelector(".lang-dropdown");
				const options = container.querySelectorAll(".lang-option");

				if (!toggle || !dropdown) return;

				toggle.addEventListener("click", (e) => {
					e.stopPropagation();
					document.querySelectorAll(".lang-dropdown").forEach((d) => {
						if (d !== dropdown) d.classList.add("hidden");
					});
					dropdown.classList.toggle("hidden");
				});

				options.forEach((opt) => {
					opt.addEventListener("click", () => {
						const lang = opt.dataset.lang;
						document.cookie = `lang=${lang}; path=/; max-age=${60 * 60 * 24 * 365}; SameSite=Lax`;
					});
				});
			});
		}

		document.addEventListener("DOMContentLoaded", () => {
			setupSidebar();
			setupLanguageSwitchers();
			document.addEventListener("click", () => {
				document.querySelectorAll(".lang-dropdown").forEach((d) => d.classList.add("hidden"));
			});
		});
	})();
</script>