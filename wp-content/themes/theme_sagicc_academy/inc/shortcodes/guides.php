<?php
/**
 * Guides List & Switcher Shortcode ([sagicc_guias_list])
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'sagicc_guias_list', function () {
	$lang = isset( $_COOKIE['lang'] ) && in_array( $_COOKIE['lang'], array( 'es', 'en' ) ) ? $_COOKIE['lang'] : 'es';

	$translations = array(
		'es' => array(
			'no_guides'  => 'No hay guías disponibles.',
			'view_guide' => 'Ver guía',
			'view_list'  => 'Lista',
			'view_grid'  => 'Cuadrícula',
		),
		'en' => array(
			'no_guides'  => 'No guides available.',
			'view_guide' => 'Read guide',
			'view_list'  => 'List',
			'view_grid'  => 'Grid',
		)
	);

	$t = function ( $key ) use ( $translations, $lang ) {
		return isset( $translations[ $lang ][ $key ] ) ? $translations[ $lang ][ $key ] : $key;
	};

	$guias_query = new WP_Query( array(
		'post_type'      => 'guia',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	) );

	if ( ! $guias_query->have_posts() ) {
		return '<p class="text-gray-400 font-medium text-base font-sans">' . esc_html( $t( 'no_guides' ) ) . '</p>';
	}

	ob_start();
	?>
	<div class="sa-view-switcher">
		<div class="sa-view-toggle-group" role="group" aria-label="Cambiar vista">
			<button type="button" class="sa-view-toggle-btn active" data-view="list" title="<?php echo esc_attr( $t( 'view_list' ) ); ?>">
				<i class="fa-solid fa-list"></i>
				<span><?php echo esc_html( $t( 'view_list' ) ); ?></span>
			</button>
			<button type="button" class="sa-view-toggle-btn" data-view="grid" title="<?php echo esc_attr( $t( 'view_grid' ) ); ?>">
				<i class="fa-solid fa-border-all"></i>
				<span><?php echo esc_html( $t( 'view_grid' ) ); ?></span>
			</button>
		</div>
	</div>

	<div id="sa-guias-container" class="sa-view-container sa-view-list">
		<?php while ( $guias_query->have_posts() ) : $guias_query->the_post(); 
			$guia_id       = get_the_ID();
			$permalink     = get_permalink();
			$thumbnail_url = get_the_post_thumbnail_url( $guia_id, 'large' );
			?>
			<article class="sa-card">
				<a href="<?php echo esc_url( $permalink ); ?>" class="sa-card-media">
					<?php if ( $thumbnail_url ) : ?>
						<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php the_title_attribute(); ?>" class="sa-card-img">
					<?php else : ?>
						<div class="sa-card-media-placeholder">
							<i class="fa-solid fa-book-open"></i>
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
						<?php echo wp_trim_words( get_the_excerpt(), 25 ); ?>
					</div>
					<div class="sa-card-footer">
						<a href="<?php echo esc_url( $permalink ); ?>" class="sa-btn-card">
							<?php echo esc_html( $t( 'view_guide' ) ); ?>
						</a>
					</div>
				</div>
			</article>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const toggleBtns = document.querySelectorAll('.sa-view-toggle-btn');
		const container = document.getElementById('sa-guias-container');
		if (!container || !toggleBtns.length) return;

		toggleBtns.forEach(btn => {
			btn.addEventListener('click', function() {
				const view = this.getAttribute('data-view');
				toggleBtns.forEach(b => b.classList.remove('active'));
				this.classList.add('active');
				
				if (view === 'grid') {
					container.classList.remove('sa-view-list');
					container.classList.add('sa-view-grid');
				} else {
					container.classList.remove('sa-view-grid');
					container.classList.add('sa-view-list');
				}
			});
		});
	});
	</script>
	<?php
	return ob_get_clean();
} );
