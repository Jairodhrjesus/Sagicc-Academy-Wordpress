<?php
/**
 * Title: Register Page Sagicc Academy
 * Slug: theme_sagicc_academy/register
 * Categories: page, custom
 */

$lang = isset( $_COOKIE['lang'] ) && in_array( $_COOKIE['lang'], array( 'es', 'en' ) ) ? $_COOKIE['lang'] : 'es';

$translations = array(
	'es' => array(
		'auth.register' => 'Registrarse',
		'auth.register_title' => 'Únete a la Academia',
		'auth.register_desc' => 'Domina Sagicc y aprende a crear experiencias únicas.',
		'auth.already_have_account' => '¿Ya tienes una cuenta?',
		'auth.login' => 'Iniciar Sesión',
	),
	'en' => array(
		'auth.register' => 'Register',
		'auth.register_title' => 'Join the Academy',
		'auth.register_desc' => 'Master Sagicc and learn to create unique experiences.',
		'auth.already_have_account' => 'Already have an account?',
		'auth.login' => 'Login',
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
				<p class="sa-auth-cover-desc"><?php echo esc_html( $t( 'auth.register_desc' ) ); ?></p>
			</div>
		</div>
	</div>

	<!-- Columna Derecha: Formulario -->
	<div class="sa-auth-form-container">
		<div class="sa-auth-form-box">
			<header class="sa-auth-header">
				<h1 class="sa-auth-title">
					<?php echo esc_html( $t( 'auth.register' ) ); ?>
				</h1>
				<p class="sa-auth-subtitle"><?php echo esc_html( $t( 'auth.register_title' ) ); ?></p>
			</header>

			<div>
				<!-- Renderizado del shortcode de registro de Ultimate Member -->
				<?php echo do_shortcode( '[ultimatemember form_id="321"]' ); ?>
			</div>

			<p class="sa-auth-footer">
				<?php echo esc_html( $t( 'auth.already_have_account' ) ); ?> <a href="/login" class="sa-auth-link"><?php echo esc_html( $t( 'auth.login' ) ); ?></a>
			</p>
		</div>
	</div>
</div>
