<?php
/**
 * Custom Admin Banner for wp-admin/about.php
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Banner personalizado ultra-minimalista para Sagicc Academy en wp-admin/about.php
add_action( 'in_admin_header', function () {
	$screen = get_current_screen();
	if ( ! $screen || $screen->id !== 'about' ) {
		return;
	}
	$logo_url = get_stylesheet_directory_uri() . '/assets/Sagicc-Academy-Logo.svg';
	$site_url = home_url( '/' );
	?>
	<div class="sa-admin-minimal-banner">
		<div class="sa-admin-minimal-brand">
			<img src="<?php echo esc_url( $logo_url ); ?>" alt="Sagicc Academy" class="sa-admin-minimal-logo" />
			<div class="sa-admin-minimal-info">
				<h2>Sagicc Academy</h2>
				<p>Panel de Administración y Gestión de Cursos</p>
			</div>
		</div>
		<a href="<?php echo esc_url( $site_url ); ?>" target="_blank" class="sa-admin-minimal-link">
			<span>Ir a la Academia</span>
			<span class="material-symbols-outlined">open_in_new</span>
		</a>
	</div>
	<style>
		.sa-admin-minimal-banner {
			margin: 20px 20px 15px 0;
			padding: 0.85rem 1.25rem;
			background: #ffffff;
			border: 1px solid #E2E8F0;
			border-radius: 8px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 1rem;
			box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
		}
		.sa-admin-minimal-brand {
			display: flex;
			align-items: center;
			gap: 1rem;
		}
		.sa-admin-minimal-logo {
			height: 1.6rem;
			width: auto;
		}
		.sa-admin-minimal-info h2 {
			font-size: 0.95rem !important;
			font-weight: 700 !important;
			color: #0F172A !important;
			margin: 0 !important;
			line-height: 1.2 !important;
			padding: 0 !important;
		}
		.sa-admin-minimal-info p {
			font-size: 0.8rem !important;
			color: #64748B !important;
			margin: 0 !important;
			line-height: 1.2 !important;
		}
		.sa-admin-minimal-link {
			display: inline-flex;
			align-items: center;
			gap: 0.4rem;
			font-size: 0.8rem;
			font-weight: 600;
			color: #0052FF;
			text-decoration: none;
			padding: 0.4rem 0.85rem;
			border-radius: 6px;
			background-color: #F8FAFC;
			border: 1px solid #E2E8F0;
			transition: all 0.2s ease;
			white-space: nowrap;
		}
		.sa-admin-minimal-link .material-symbols-outlined {
			font-size: 1.1rem;
			line-height: 1;
		}
		.sa-admin-minimal-link:hover {
			background-color: #F1F5F9;
			color: #0040D0;
		}
	</style>
	<?php
} );
