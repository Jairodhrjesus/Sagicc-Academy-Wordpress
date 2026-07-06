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
<div class="min-h-screen flex bg-white relative font-sans w-full">
	<!-- Columna Izquierda: Cover estético -->
	<div class="hidden lg:flex lg:w-[45%] relative p-4 bg-gray-50">
		<div class="relative w-full h-full overflow-hidden rounded-[2.5rem] shadow-2xl bg-gradient-to-tr from-[#080833] via-[#0b0b47] to-[#2463eb] flex flex-col justify-end p-12 text-white">
			<!-- Overlay sutil con micro-líneas o diseño de fondo -->
			<div class="absolute inset-0 bg-black/10 mix-blend-multiply"></div>
			<div class="relative z-10 space-y-4 max-w-md">
				<h2 class="text-4xl font-extrabold font-title tracking-tight leading-tight">Sagicc Academy</h2>
				<p class="text-white/80 font-medium text-lg"><?php echo esc_html( $t( 'auth.register_desc' ) ); ?></p>
			</div>
		</div>
	</div>

	<!-- Columna Derecha: Formulario -->
	<div class="w-full lg:w-[55%] flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-white">
		<div class="w-full max-w-md">
			<header class="mb-10 text-center">
				<h1 class="text-4xl font-black text-secondary tracking-tighter mb-4">
					<?php echo esc_html( $t( 'auth.register' ) ); ?>
				</h1>
				<p class="text-gray-400 font-medium text-sm"><?php echo esc_html( $t( 'auth.register_title' ) ); ?></p>
			</header>

			<div class="space-y-6">
				<!-- Renderizado del shortcode de registro de Ultimate Member -->
				<?php echo do_shortcode( '[ultimatemember form_id="321"]' ); ?>
			</div>

			<p class="text-center text-sm text-gray-400 pt-8 font-medium">
				<?php echo esc_html( $t( 'auth.already_have_account' ) ); ?> <a href="/login" class="text-secondary font-black hover:underline underline-offset-4"><?php echo esc_html( $t( 'auth.login' ) ); ?></a>
			</p>
		</div>
	</div>
</div>
