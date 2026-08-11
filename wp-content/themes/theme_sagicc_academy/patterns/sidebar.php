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

$docs_url = 'https://technisupport.atlassian.net/wiki/spaces/S2MDU/overview';
$interactive_url = '#';
$certificates_url = trailingslashit($lang_home) . 'certificates/';
$support_url = $t('dashboard.support_url');
$updates_url = $t('dashboard.updates_url');
$ideas_url = $t('dashboard.feature_ideas_url');

// Obtener datos del usuario
$is_logged_in = is_user_logged_in();
$user_avatar = '';
$user_profile_url = home_url( ($lang === 'en' ? '/en' : '') . '/login/' );

if ($is_logged_in) {
	$current_user = wp_get_current_user();
	$user_profile_url = home_url( ($lang === 'en' ? '/en' : '') . '/profile/' . $current_user->user_nicename . '/' );
	$avatar_url = get_avatar_url($current_user->ID, array('size' => 32));
	if ($avatar_url) {
		$user_avatar = '<img src="' . esc_url($avatar_url) . '" class="sa-avatar-sm" />';
	}
}

$user_title = $is_logged_in ? $t('dashboard.profile') : $t('auth.login');

if (empty($user_avatar)) {
	$icon = $is_logged_in ? 'fa-user-gear' : 'fa-user-lock';
	$user_avatar = '<span class="sa-avatar-sm-placeholder"><i class="fa-solid ' . esc_attr($icon) . '"></i></span>';
}

$theme_uri = get_stylesheet_directory_uri();
?>

<!-- MOBILE TOPBAR -->
<div class="sa-mobile-topbar">
	<button id="sa-mobile-toggle-btn" type="button" class="sa-mobile-toggle-btn" aria-label="Abrir Menú">
		<i class="fa-solid fa-bars"></i>
	</button>
	<a href="<?php echo esc_url($dashboard_url); ?>" class="sa-mobile-logo-link">
		<img src="<?php echo esc_url($theme_uri . '/assets/Sagicc-Academy-Logo.svg'); ?>" alt="Sagicc Academy" class="sa-mobile-logo" />
	</a>
	<a href="<?php echo esc_url($user_profile_url); ?>" class="sa-mobile-user-link" title="<?php echo esc_attr($user_title); ?>">
		<?php echo $user_avatar; ?>
	</a>
</div>

<!-- MOBILE BACKDROP OVERLAY -->
<div id="sa-sidebar-backdrop" class="sa-sidebar-backdrop"></div>

<aside id="sidebar" class="sa-sidebar">
	<div class="sa-sidebar-wrapper">
		<!-- LOGO SECTION -->
		<div class="sa-sidebar-logo-container">
			<a href="<?php echo esc_url($dashboard_url); ?>" id="sidebar-logo" class="sa-sidebar-logo-link">
				<img src="<?php echo esc_url($theme_uri . '/assets/Sagicc-Academy-Logo.svg'); ?>" alt="Sagicc Academy" class="sa-sidebar-logo full-logo" />
				<img src="<?php echo esc_url($theme_uri . '/assets/isotipo-sagicc-academy.svg'); ?>" alt="Sagicc Academy" class="sa-sidebar-logo isotype-logo sa-hidden" />
			</a>
			<button id="sa-mobile-close-btn" type="button" class="sa-mobile-close-btn" aria-label="Cerrar Menú">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>

		<div id="sidebar-content" class="sa-sidebar-content scrollbar-hide">
			<!-- NAVIGATION: MAIN -->
			<nav class="sa-sidebar-nav">
				<a href="<?php echo esc_url($dashboard_url); ?>" class="sa-sidebar-link active" title="<?php echo esc_attr($t('dashboard.title')); ?>">
					<i class="fa-solid fa-table-columns"></i>
					<span class="sidebar-text"><?php echo esc_html($t('dashboard.title')); ?></span>
				</a>
			</nav>

			<!-- NAVIGATION: LEARN -->
			<div class="sa-sidebar-section">
				<p class="sidebar-text sa-sidebar-section-title">
					<?php echo esc_html($t('dashboard.section_learn')); ?>
				</p>
				<nav class="sa-sidebar-nav">
					<a href="<?php echo esc_url($courses_url); ?>" class="sa-sidebar-link" title="<?php echo esc_attr($t('dashboard.courses')); ?>">
						<i class="fa-solid fa-graduation-cap"></i>
						<span class="sidebar-text"><?php echo esc_html($t('dashboard.courses')); ?></span>
					</a>
					<a href="<?php echo esc_url($videos_url); ?>" class="sa-sidebar-link" title="<?php echo esc_attr($t('dashboard.videos')); ?>">
						<i class="fa-solid fa-play"></i>
						<span class="sidebar-text"><?php echo esc_html($t('dashboard.videos')); ?></span>
					</a>
					<a href="<?php echo esc_url($guides_url); ?>" class="sa-sidebar-link" title="<?php echo esc_attr($t('dashboard.guides')); ?>">
						<i class="fa-solid fa-book-open"></i>
						<span class="sidebar-text"><?php echo esc_html($t('dashboard.guides')); ?></span>
					</a>
					<a href="<?php echo esc_url($docs_url); ?>" target="_blank" rel="noopener noreferrer" class="sa-sidebar-link" title="<?php echo esc_attr($t('dashboard.docs')); ?>">
						<i class="fa-solid fa-file-lines"></i>
						<span class="sidebar-text"><?php echo esc_html($t('dashboard.docs')); ?></span>
						<i class="fa-solid fa-up-right-from-square sa-sidebar-icon-ext sidebar-text"></i>
					</a>
					<a href="<?php echo esc_url($interactive_url); ?>" class="sa-sidebar-link" title="<?php echo esc_attr($t('dashboard.interactive')); ?>">
						<i class="fa-solid fa-computer-mouse"></i>
						<span class="sidebar-text"><?php echo esc_html($t('dashboard.interactive')); ?></span>
					</a>
					<a href="<?php echo esc_url($certificates_url); ?>" class="sa-sidebar-link" title="<?php echo esc_attr($t('dashboard.certificates')); ?>">
						<i class="fa-solid fa-award"></i>
						<span class="sidebar-text"><?php echo esc_html($t('dashboard.certificates')); ?></span>
					</a>
				</nav>
			</div>

			<!-- NAVIGATION: PARTICIPATE -->
			<div class="sa-sidebar-section">
				<p class="sidebar-text sa-sidebar-section-title">
					<?php echo esc_html($t('dashboard.section_participate')); ?>
				</p>
				<nav class="sa-sidebar-nav">
					<a href="<?php echo esc_url($support_url); ?>" target="_blank" rel="noopener noreferrer" class="sa-sidebar-link" title="<?php echo esc_attr($t('dashboard.support')); ?>">
						<i class="fa-solid fa-headset"></i>
						<span class="sidebar-text"><?php echo esc_html($t('dashboard.support')); ?></span>
						<i class="fa-solid fa-up-right-from-square sa-sidebar-icon-ext sidebar-text"></i>
					</a>
					<a href="<?php echo esc_url($updates_url); ?>" target="_blank" rel="noopener noreferrer" class="sa-sidebar-link" title="<?php echo esc_attr($t('dashboard.updates')); ?>">
						<i class="fa-solid fa-bullhorn"></i>
						<span class="sidebar-text"><?php echo esc_html($t('dashboard.updates')); ?></span>
						<i class="fa-solid fa-up-right-from-square sa-sidebar-icon-ext sidebar-text"></i>
					</a>
					<a href="<?php echo esc_url($ideas_url); ?>" target="_blank" rel="noopener noreferrer" class="sa-sidebar-link" title="<?php echo esc_attr($t('dashboard.feature_ideas')); ?>">
						<i class="fa-solid fa-lightbulb"></i>
						<span class="sidebar-text"><?php echo esc_html($t('dashboard.feature_ideas')); ?></span>
						<i class="fa-solid fa-up-right-from-square sa-sidebar-icon-ext sidebar-text"></i>
					</a>
				</nav>
			</div>
		</div>
	</div>

	<!-- FOOTER ACTIONS -->
	<div class="sa-sidebar-footer">
		<div class="sa-sidebar-footer-icons">
			<div class="sa-sidebar-footer-item">
				<button id="sidebar-toggle-btn"
					type="button"
					class="sa-sidebar-footer-btn"
					title="<?php echo esc_attr($lang === 'es' ? 'Contraer / Expandir' : 'Collapse / Expand'); ?>">
					<i class="fa-solid fa-window-maximize"></i>
				</button>
			</div>

			<div class="sa-sidebar-footer-item">
				<a href="<?php echo esc_url($user_profile_url); ?>"
					class="sa-sidebar-footer-btn"
					title="<?php echo esc_attr($user_title); ?>">
					<?php echo $user_avatar; ?>
				</a>
			</div>

			<div class="sa-sidebar-footer-item">
				<!-- Language Switcher Container -->
				<div class="sa-lang-container">
					<button
						type="button"
						class="lang-toggle sa-sidebar-footer-btn"
						title="<?php echo esc_attr($lang === 'es' ? 'Idioma' : 'Language'); ?>">
						<i class="fa-solid fa-language"></i>
					</button>

					<!-- Dropdown -->
					<div class="lang-dropdown sa-lang-dropdown sa-hidden">
						<a href="<?php echo esc_url($es_url); ?>" data-lang="es"
							class="lang-option sa-lang-option <?php echo $lang === 'es' ? 'active' : ''; ?>">
							<span class="sa-lang-tag">ES</span>
							Español
							<?php if ($lang === 'es'): ?>
								<i class="fa-solid fa-check sa-lang-check"></i>
							<?php endif; ?>
						</a>
						<a href="<?php echo esc_url($en_url); ?>" data-lang="en"
							class="lang-option sa-lang-option <?php echo $lang === 'en' ? 'active' : ''; ?>">
							<span class="sa-lang-tag">EN</span>
							English
							<?php if ($lang === 'en'): ?>
								<i class="fa-solid fa-check sa-lang-check"></i>
							<?php endif; ?>
						</a>
					</div>
				</div>
			</div>

			<?php if ($is_logged_in): ?>
				<div class="sa-sidebar-footer-item">
					<button id="logout-btn"
						type="button"
						class="sa-sidebar-footer-btn sa-sidebar-footer-btn-danger"
						title="<?php echo esc_attr($t('dashboard.logout')); ?>">
						<i class="fa-solid fa-right-from-bracket"></i>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<!-- LOGOUT CONFIRMATION MODAL -->
	<?php if ($is_logged_in): ?>
		<div id="logout-modal" class="sa-modal sa-hidden">
			<div class="sa-modal-backdrop"></div>
			<div class="sa-modal-container">
				<div class="sa-modal-card modal-content">
					<div class="sa-logout-icon">
						<i class="fa-solid fa-right-from-bracket"></i>
					</div>
					<h3 class="sa-modal-title">
						<?php echo esc_html($t('auth.logout_confirm_title')); ?>
					</h3>
					<p class="sa-modal-desc">
						<?php echo esc_html($t('auth.logout_confirm_desc')); ?>
					</p>
					<div class="sa-modal-actions">
						<button id="confirm-logout" data-logout-url="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>"
							class="sa-btn-danger">
							<?php echo esc_html($t('auth.logout_confirm_btn')); ?>
						</button>
						<button id="cancel-logout"
							class="sa-btn-cancel">
							<?php echo esc_html($t('auth.logout_cancel_btn')); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</aside>

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
				if (isCollapsed) {
					sidebar.classList.add("sidebar-collapsed");
					texts.forEach((t) => t.classList.add("sa-hidden"));
				} else {
					sidebar.classList.remove("sidebar-collapsed");
					texts.forEach((t) => t.classList.remove("sa-hidden"));
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
					modal.classList.remove("sa-hidden");
					document.body.style.overflow = "hidden";
					setTimeout(() => {
						modal.querySelector(".modal-content")?.classList.add("active");
					}, 10);
				}
			});

			const modal = document.getElementById("logout-modal");
			const cancelBtn = document.getElementById("cancel-logout");
			const confirmBtn = document.getElementById("confirm-logout");

			const closeModal = () => {
				modal?.querySelector(".modal-content")?.classList.remove("active");
				setTimeout(() => {
					modal?.classList.add("sa-hidden");
					document.body.style.overflow = "";
				}, 200);
			};

			cancelBtn?.addEventListener("click", closeModal);
			modal?.addEventListener("click", (e) => {
				if (e.target === modal || e.target.classList.contains("sa-modal-backdrop") || e.target.classList.contains("sa-modal-container")) closeModal();
			});

			confirmBtn?.addEventListener("click", () => {
				confirmBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
				window.location.href = confirmBtn.dataset.logoutUrl;
			});

			sidebar.dataset.initialized = "true";

			if (localStorage.getItem("sidebar-collapsed") === "true") {
				applyState(true);
			}
		}

		function setupLanguageSwitchers() {
			const containers = document.querySelectorAll(".sa-lang-container");

			containers.forEach((container) => {
				const toggle = container.querySelector(".lang-toggle");
				const dropdown = container.querySelector(".sa-lang-dropdown");
				const options = container.querySelectorAll(".sa-lang-option");

				if (!toggle || !dropdown) return;

				toggle.addEventListener("click", (e) => {
					e.stopPropagation();
					document.querySelectorAll(".sa-lang-dropdown").forEach((d) => {
						if (d !== dropdown) d.classList.add("sa-hidden");
					});
					dropdown.classList.toggle("sa-hidden");
				});

				options.forEach((opt) => {
					opt.addEventListener("click", () => {
						const lang = opt.dataset.lang;
						document.cookie = `lang=${lang}; path=/; max-age=${60 * 60 * 24 * 365}; SameSite=Lax`;
					});
				});
			});
		}

		function setupMobileSidebar() {
			const mobileToggle = document.getElementById("sa-mobile-toggle-btn");
			const mobileClose = document.getElementById("sa-mobile-close-btn");
			const sidebar = document.getElementById("sidebar");
			const backdrop = document.getElementById("sa-sidebar-backdrop");
			const navLinks = sidebar?.querySelectorAll(".sa-sidebar-link");

			if (!sidebar) return;

			const openMobileSidebar = () => {
				sidebar.classList.add("sa-mobile-open");
				backdrop?.classList.add("active");
				document.body.style.overflow = "hidden";
			};

			const closeMobileSidebar = () => {
				sidebar.classList.remove("sa-mobile-open");
				backdrop?.classList.remove("active");
				document.body.style.overflow = "";
			};

			mobileToggle?.addEventListener("click", openMobileSidebar);
			mobileClose?.addEventListener("click", closeMobileSidebar);
			backdrop?.addEventListener("click", closeMobileSidebar);

			navLinks?.forEach((link) => {
				link.addEventListener("click", () => {
					if (window.innerWidth < 1024) {
						closeMobileSidebar();
					}
				});
			});

			window.addEventListener("resize", () => {
				if (window.innerWidth >= 1024) {
					closeMobileSidebar();
				}
			});
		}

		document.addEventListener("DOMContentLoaded", () => {
			setupSidebar();
			setupMobileSidebar();
			setupLanguageSwitchers();
			document.addEventListener("click", () => {
				document.querySelectorAll(".sa-lang-dropdown").forEach((d) => d.classList.add("sa-hidden"));
			});
		});
	})();
</script>