<?php
/**
 * Header Components Shortcodes (Home Header, Archive Header)
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'sagicc_home_header', function () {
	$lang = function_exists( 'pll_current_language' ) ? pll_current_language() : ( isset( $_COOKIE['lang'] ) && in_array( $_COOKIE['lang'], array( 'es', 'en' ) ) ? $_COOKIE['lang'] : 'es' );

	$translations = array(
		'es' => array(
			'home.welcome_badge'            => 'Bienvenido a la Academia',
			'home.hero_title'               => 'Escala tu conocimiento al siguiente nivel.',
			'home.hero_desc'                => 'Aprende de expertos en CX y CRM con cursos diseñados para el mercado actual.',
			'home.cta_start'                => 'Comenzar ahora',
			'home.cta_register'             => 'Crear cuenta',
			'dashboard.welcome_back'        => 'Bienvenido de nuevo',
			'dashboard.available_courses_desc' => 'Contenido exclusivo para potenciar tus habilidades.',
		),
		'en' => array(
			'home.welcome_badge'            => 'Welcome to the Academy',
			'home.hero_title'               => 'Scale your knowledge to the next level.',
			'home.hero_desc'                => 'Learn from CX and CRM experts with courses designed for today\'s market.',
			'home.cta_start'                => 'Get started',
			'home.cta_register'             => 'Create account',
			'dashboard.welcome_back'        => 'Welcome back',
			'dashboard.available_courses_desc' => 'Exclusive content to boost your skills.',
		)
	);

	$t = function ( $key ) use ( $translations, $lang ) {
		return isset( $translations[ $lang ][ $key ] ) ? $translations[ $lang ][ $key ] : $key;
	};

	$is_logged_in = is_user_logged_in();
	ob_start();

	if ( ! $is_logged_in ) : ?>
		<header class="sa-hero">
			<div class="sa-hero-dots-container">
				<div class="sa-hero-dots"></div>
			</div>

			<div class="sa-hero-content">
				<span class="sa-hero-badge">
					<?php echo esc_html( $t( 'home.welcome_badge' ) ); ?>
				</span>
				<h1 class="sa-hero-title">
					<?php echo esc_html( $t( 'home.hero_title' ) ); ?>
				</h1>
				<p class="sa-hero-desc">
					<?php echo esc_html( $t( 'home.hero_desc' ) ); ?>
				</p>
				<div class="sa-hero-actions">
					<a href="<?php echo esc_url( home_url( '/login/' ) ); ?>" class="sa-btn sa-btn-primary">
						<?php echo esc_html( $t( 'home.cta_start' ) ); ?>
					</a>
					<a href="<?php echo esc_url( home_url( '/register/' ) ); ?>" class="sa-btn sa-btn-outline">
						<?php echo esc_html( $t( 'home.cta_register' ) ); ?>
					</a>
				</div>
			</div>

			<div class="sa-hero-bg-glow-1"></div>
			<div class="sa-hero-bg-glow-2"></div>
		</header>

		<script>
		(function() {
			function initHeroDotsGsap() {
				const hero = document.querySelector('.sa-hero');
				const dots = document.querySelector('.sa-hero-dots');
				if (!hero || !dots) return;

				let bounds = hero.getBoundingClientRect();
				window.addEventListener('resize', () => {
					bounds = hero.getBoundingClientRect();
				});

				hero.addEventListener('mousemove', (e) => {
					if (typeof gsap === 'undefined') return;
					const relX = (e.clientX - bounds.left) / bounds.width - 0.5;
					const relY = (e.clientY - bounds.top) / bounds.height - 0.5;

					gsap.to(dots, {
						x: relX * 45,
						y: relY * 35,
						rotation: relX * 1.5,
						duration: 0.6,
						ease: 'power2.out',
						overwrite: 'auto'
					});
				});

				hero.addEventListener('mouseleave', () => {
					if (typeof gsap === 'undefined') return;
					gsap.to(dots, {
						x: 0,
						y: 0,
						rotation: 0,
						duration: 1,
						ease: 'power3.out',
						overwrite: 'auto'
					});
				});
			}

			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', initHeroDotsGsap);
			} else {
				initHeroDotsGsap();
			}
		})();
		</script>
	<?php else :
		$current_user = wp_get_current_user();
		$first_name   = $current_user->user_firstname;
		if ( empty( $first_name ) ) {
			$first_name = $current_user->display_name;
		}
		?>
		<header class="sa-header">
			<h1 class="sa-header-title">
				<?php echo esc_html( $t( 'dashboard.welcome_back' ) ); ?>, <?php echo esc_html( $first_name ); ?>
			</h1>
			<p class="sa-header-subtitle">
				<?php echo esc_html( $t( 'dashboard.available_courses_desc' ) ); ?>
			</p>
		</header>
	<?php endif;

	return ob_get_clean();
} );

add_shortcode( 'sagicc_archive_header', function ( $atts ) {
	$atts = shortcode_atts( array(
		'post_type' => 'post',
	), $atts );

	if ( ! is_user_logged_in() && $atts['post_type'] === 'certificate' ) {
		return '';
	}

	$lang = function_exists( 'pll_current_language' ) ? pll_current_language() : ( isset( $_COOKIE['lang'] ) && in_array( $_COOKIE['lang'], array( 'es', 'en' ) ) ? $_COOKIE['lang'] : 'es' );

	$translations = array(
		'video'       => array(
			'es' => array(
				'title' => 'Videos',
				'desc'  => 'Contenido en video para potenciar tus habilidades.'
			),
			'en' => array(
				'title' => 'Videos',
				'desc'  => 'Video content to boost your skills.'
			)
		),
		'guia'        => array(
			'es' => array(
				'title' => 'Guías',
				'desc'  => 'Guías exclusivas para potenciar tus habilidades.'
			),
			'en' => array(
				'title' => 'Guides',
				'desc'  => 'Exclusive guides to boost your skills.'
			)
		),
		'certificate' => array(
			'es' => array(
				'title' => 'Certificados',
				'desc'  => 'Tus certificados obtenidos.'
			),
			'en' => array(
				'title' => 'Certificates',
				'desc'  => 'Your earned certificates.'
			)
		)
	);

	$title = isset( $translations[ $atts['post_type'] ][ $lang ]['title'] ) ? $translations[ $atts['post_type'] ][ $lang ]['title'] : '';
	$desc  = isset( $translations[ $atts['post_type'] ][ $lang ]['desc'] ) ? $translations[ $atts['post_type'] ][ $lang ]['desc'] : '';

	ob_start();
	?>
	<header class="sa-header">
		<h1 class="sa-header-title">
			<?php echo esc_html( $title ); ?>
		</h1>
		<p class="sa-header-subtitle">
			<?php echo esc_html( $desc ); ?>
		</p>
	</header>
	<?php
	return ob_get_clean();
} );
