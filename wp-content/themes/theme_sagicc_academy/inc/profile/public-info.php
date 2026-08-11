<?php
/**
 * Profile Public Info Component
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="sa-card sa-profile-card">
	<div class="sa-card-header">
		<h3 class="sa-section-title"><?php echo esc_html( $p['t']( 'profile.info_title' ) ); ?></h3>
	</div>
	<div class="sa-card-body">
		<div class="sa-form-grid-2">
			<div class="sa-info-card">
				<p class="sa-info-label"><?php echo esc_html( $p['t']( 'profile.username' ) ); ?></p>
				<p class="sa-info-value">@<?php echo esc_html( $p['username'] ); ?></p>
			</div>
			<div class="sa-info-card">
				<p class="sa-info-label"><?php echo esc_html( $p['t']( 'profile.role' ) ); ?></p>
				<p class="sa-info-value"><?php echo esc_html( $p['role_name'] ); ?></p>
			</div>
		</div>
	</div>
</div>
