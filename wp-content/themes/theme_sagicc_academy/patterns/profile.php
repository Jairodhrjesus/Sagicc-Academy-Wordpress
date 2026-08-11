<?php
/**
 * Title: Profile Page Sagicc Academy
 * Slug: theme_sagicc_academy/profile
 * Categories: page, custom
 *
 * @package theme_sagicc_academy
 */

if ( ! is_user_logged_in() ) {
	wp_redirect( home_url( '/login/' ) );
	exit;
}

// 1. Cargar manejador de datos y lógica del perfil
$p = require get_template_directory() . '/inc/profile/profile-data.php';
?>

<div class="sa-profile-wrapper">
	<header class="sa-header">
		<h1 class="sa-header-title">
			<?php echo esc_html( $p['t']( 'dashboard.profile' ) ); ?>
		</h1>
		<p class="sa-header-subtitle"><?php echo esc_html( $p['t']( 'profile.desc' ) ); ?></p>
	</header>

	<!-- ALERTAS GLOBALES -->
	<?php if ( ! empty( $p['info_error'] ) ) : ?>
		<div class="sa-alert-error"><i class="fa-solid fa-circle-exclamation me-2"></i><?php echo esc_html( $p['info_error'] ); ?></div>
	<?php endif; ?>
	<?php if ( ! empty( $p['info_success'] ) ) : ?>
		<div class="sa-alert-success"><i class="fa-solid fa-circle-check me-2"></i><?php echo esc_html( $p['info_success'] ); ?></div>
	<?php endif; ?>
	<?php if ( ! empty( $p['upload_error'] ) ) : ?>
		<div class="sa-alert-error"><i class="fa-solid fa-circle-exclamation me-2"></i><?php echo esc_html( $p['upload_error'] ); ?></div>
	<?php endif; ?>
	<?php if ( ! empty( $p['upload_success'] ) ) : ?>
		<div class="sa-alert-success"><i class="fa-solid fa-circle-check me-2"></i><?php echo esc_html( $p['upload_success'] ); ?></div>
	<?php endif; ?>

	<!-- COMPONENTES DE PERFIL -->
	<?php require get_template_directory() . '/inc/profile/hero.php'; ?>
	<?php require get_template_directory() . '/inc/profile/stats.php'; ?>

	<div class="sa-profile-sections">
		<?php if ( $p['is_own_profile'] ) : ?>
			<?php require get_template_directory() . '/inc/profile/info-form.php'; ?>
			<?php require get_template_directory() . '/inc/profile/password-form.php'; ?>
		<?php else : ?>
			<?php require get_template_directory() . '/inc/profile/public-info.php'; ?>
		<?php endif; ?>
	</div>
</div>