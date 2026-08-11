<?php
/**
 * Title: Login Page Sagicc Academy
 * Slug: theme_sagicc_academy/login
 * Categories: page, custom
 */

$lang = isset( $_COOKIE['lang'] ) && in_array( $_COOKIE['lang'], array( 'es', 'en' ) ) ? $_COOKIE['lang'] : 'es';

$translations = array(
	'es' => array(
		'auth.login' => 'Iniciar Sesión',
		'auth.login_title' => 'Construye tu atención al cliente',
		'auth.login_desc' => 'Domina Sagicc y aprende a crear experiencias únicas.',
	),
	'en' => array(
		'auth.login' => 'Login',
		'auth.login_title' => 'Build your customer service',
		'auth.login_desc' => 'Master Sagicc and learn to create unique experiences.',
	)
);

$t = function( $key ) use ( $translations, $lang ) {
	return isset( $translations[ $lang ][ $key ] ) ? $translations[ $lang ][ $key ] : $key;
};
?>
<div class="sa-auth-page">
	<!-- Columna Izquierda: Cover estético -->
	<div class="sa-auth-cover">
		<div class="sa-auth-cover-card">
			<div class="sa-auth-cover-overlay"></div>
			<div class="sa-auth-cover-content">
				<h2 class="sa-auth-cover-title">Sagicc Academy</h2>
				<p class="sa-auth-cover-desc"><?php echo esc_html( $t( 'auth.login_desc' ) ); ?></p>
			</div>
		</div>
	</div>

	<!-- Columna Derecha: Formulario -->
	<div class="sa-auth-form-container">
		<div class="sa-auth-form-box">
			<header class="sa-auth-header">
				<h1 class="sa-auth-title">
					<?php echo esc_html( $t( 'auth.login' ) ); ?>
				</h1>
				<p class="sa-auth-subtitle"><?php echo esc_html( $t( 'auth.login_title' ) ); ?></p>
			</header>

			<div>
				<!-- Renderizado de los shortcodes solicitados -->
				<?php echo do_shortcode( '[ultimatemember form_id="305"]' ); ?>
			</div>
		</div>
	</div>
</div>
