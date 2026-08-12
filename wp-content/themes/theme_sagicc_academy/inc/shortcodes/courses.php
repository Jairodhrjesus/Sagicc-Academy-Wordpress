<?php
/**
 * Courses Grid Shortcode ([sagicc_courses_list])
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'sagicc_courses_list', function () {
	$lang = function_exists( 'pll_current_language' ) ? pll_current_language() : ( isset( $_COOKIE['lang'] ) && in_array( $_COOKIE['lang'], array( 'es', 'en' ) ) ? $_COOKIE['lang'] : 'es' );

	$translations = array(
		'es' => array(
			'no_courses'    => 'No hay cursos disponibles.',
			'continue_course' => 'Continuar curso',
			'view_course'   => 'Ver curso',
		),
		'en' => array(
			'no_courses'    => 'No courses available.',
			'continue_course' => 'Continue course',
			'view_course'   => 'View course',
		)
	);

	$t = function ( $key ) use ( $translations, $lang ) {
		return isset( $translations[ $lang ][ $key ] ) ? $translations[ $lang ][ $key ] : $key;
	};

	$courses_query = new WP_Query( array(
		'post_type'      => 'sfwd-courses',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'lang'           => $lang,
	) );

	if ( ! $courses_query->have_posts() ) {
		return '<p class="text-gray-400 font-medium text-base font-sans">' . esc_html( $t( 'no_courses' ) ) . '</p>';
	}

	ob_start();
	?>
	<div class="sa-grid">
		<?php while ( $courses_query->have_posts() ) : $courses_query->the_post(); 
			$course_id     = get_the_ID();
			$permalink     = get_permalink();
			$thumbnail_url = get_the_post_thumbnail_url( $course_id, 'large' );
			$has_access    = is_user_logged_in() && function_exists( 'sfwd_lms_has_access' ) && sfwd_lms_has_access( $course_id, get_current_user_id() );
			?>
			<article class="sa-card">
				<a href="<?php echo esc_url( $permalink ); ?>" class="sa-card-media">
					<?php if ( $thumbnail_url ) : ?>
						<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php the_title_attribute(); ?>" class="sa-card-img">
					<?php else : ?>
						<div class="sa-card-media-placeholder">
							<i class="fa-solid fa-graduation-cap"></i>
						</div>
					<?php endif; ?>
				</a>
				<div class="sa-card-body">
					<h3 class="sa-card-title">
						<a href="<?php echo esc_url( $permalink ); ?>">
							<?php the_title(); ?>
						</a>
					</h3>
					<div class="sa-card-excerpt">
						<?php echo wp_trim_words( get_the_excerpt(), 18 ); ?>
					</div>
					<div class="sa-card-footer">
						<a href="<?php echo esc_url( $permalink ); ?>" class="sa-btn-card">
							<?php echo $has_access ? esc_html( $t( 'continue_course' ) ) : esc_html( $t( 'view_course' ) ); ?>
						</a>
					</div>
				</div>
			</article>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
	<?php
	return ob_get_clean();
} );
