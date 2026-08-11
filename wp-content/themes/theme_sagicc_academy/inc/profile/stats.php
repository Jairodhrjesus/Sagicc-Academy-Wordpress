<?php
/**
 * Profile Stats Cards Component
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="sa-profile-stats-grid">
	<div class="sa-stat-card">
		<div class="sa-stat-icon icon-courses">
			<i class="fa-solid fa-graduation-cap"></i>
		</div>
		<div class="sa-stat-details">
			<p class="sa-stat-number"><?php echo esc_html( $p['completed_courses_count'] ); ?></p>
			<p class="sa-stat-label"><?php echo esc_html( $p['t']( 'profile.stats_courses' ) ); ?></p>
		</div>
	</div>

	<div class="sa-stat-card">
		<div class="sa-stat-icon icon-certs">
			<i class="fa-solid fa-award"></i>
		</div>
		<div class="sa-stat-details">
			<p class="sa-stat-number"><?php echo esc_html( $p['certificates_count'] ); ?></p>
			<p class="sa-stat-label"><?php echo esc_html( $p['t']( 'profile.stats_certs' ) ); ?></p>
		</div>
	</div>

	<div class="sa-stat-card">
		<div class="sa-stat-icon icon-status">
			<i class="fa-solid fa-shield-halved"></i>
		</div>
		<div class="sa-stat-details">
			<p class="sa-stat-number text-success"><?php echo esc_html( $p['t']( 'profile.active' ) ); ?></p>
			<p class="sa-stat-label"><?php echo esc_html( $p['t']( 'profile.stats_status' ) ); ?></p>
		</div>
	</div>
</div>
