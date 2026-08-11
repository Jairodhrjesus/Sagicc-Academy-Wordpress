<?php
/**
 * Register custom shortcodes for dynamic layout components
 *
 * @package theme_sagicc_academy
 */

add_shortcode('sagicc_sidebar', function () {
	ob_start();
	include get_template_directory() . '/patterns/sidebar.php';
	return ob_get_clean();
});

add_shortcode('sagicc_profile', function () {
	ob_start();
	include get_template_directory() . '/patterns/profile.php';
	return ob_get_clean();
});

add_shortcode('sagicc_login', function () {
	ob_start();
	include get_template_directory() . '/patterns/login.php';
	return ob_get_clean();
});

add_shortcode('sagicc_register', function () {
	ob_start();
	include get_template_directory() . '/patterns/register.php';
	return ob_get_clean();
});

add_shortcode('sagicc_home_header', function () {
	$lang = isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], array('es', 'en')) ? $_COOKIE['lang'] : 'es';

	$translations = array(
		'es' => array(
			'home.welcome_badge' => 'Bienvenido a la Academia',
			'home.hero_title' => 'Escala tu conocimiento al siguiente nivel.',
			'home.hero_desc' => 'Aprende de expertos en CX y CRM con cursos diseñados para el mercado actual.',
			'home.cta_start' => 'Comenzar ahora',
			'home.cta_register' => 'Crear cuenta',
			'dashboard.welcome_back' => 'Bienvenido de nuevo',
			'dashboard.available_courses_desc' => 'Contenido exclusivo para potenciar tus habilidades.',
		),
		'en' => array(
			'home.welcome_badge' => 'Welcome to the Academy',
			'home.hero_title' => 'Scale your knowledge to the next level.',
			'home.hero_desc' => 'Learn from CX and CRM experts with courses designed for today\'s market.',
			'home.cta_start' => 'Get started',
			'home.cta_register' => 'Create account',
			'dashboard.welcome_back' => 'Welcome back',
			'dashboard.available_courses_desc' => 'Exclusive content to boost your skills.',
		)
	);

	$t = function ($key) use ($translations, $lang) {
		return isset($translations[$lang][$key]) ? $translations[$lang][$key] : $key;
	};

	$is_logged_in = is_user_logged_in();
	ob_start();

	if (!$is_logged_in): ?>
		<header class="sa-hero">
			<div class="sa-hero-content">
				<span class="sa-hero-badge">
					<?php echo esc_html($t('home.welcome_badge')); ?>
				</span>
				<h1 class="sa-hero-title">
					<?php echo esc_html($t('home.hero_title')); ?>
				</h1>
				<p class="sa-hero-desc">
					<?php echo esc_html($t('home.hero_desc')); ?>
				</p>
				<div class="sa-hero-actions">
					<a href="<?php echo esc_url(home_url('/login/')); ?>" class="sa-btn sa-btn-primary">
						<?php echo esc_html($t('home.cta_start')); ?>
					</a>
					<a href="<?php echo esc_url(home_url('/register/')); ?>" class="sa-btn sa-btn-outline">
						<?php echo esc_html($t('home.cta_register')); ?>
					</a>
				</div>
			</div>

			<!-- Abstract Background Decoration -->
			<div class="sa-hero-bg-glow-1"></div>
			<div class="sa-hero-bg-glow-2"></div>
		</header>
	<?php else:
		$current_user = wp_get_current_user();
		$first_name = $current_user->user_firstname;
		if (empty($first_name)) {
			$first_name = $current_user->display_name;
		}
		?>
		<header class="sa-header">
			<h1 class="sa-header-title">
				<?php echo esc_html($t('dashboard.welcome_back')); ?>, <?php echo esc_html($first_name); ?>
			</h1>
			<p class="sa-header-subtitle">
				<?php echo esc_html($t('dashboard.available_courses_desc')); ?>
			</p>
		</header>
	<?php endif;

	return ob_get_clean();
});

add_shortcode( 'sagicc_courses_list', function() {
	$courses_query = new WP_Query( array(
		'post_type'      => 'sfwd-courses',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	) );

	if ( ! $courses_query->have_posts() ) {
		return '<p class="text-gray-400 font-medium text-base font-sans">' . esc_html__( 'No hay cursos disponibles.', 'theme-sagicc-academy' ) . '</p>';
	}

	ob_start();
	?>
	<div class="sa-grid">
		<?php while ( $courses_query->have_posts() ) : $courses_query->the_post(); 
			$course_id = get_the_ID();
			$permalink = get_permalink();
			$thumbnail_url = get_the_post_thumbnail_url( $course_id, 'large' );
			$has_access = is_user_logged_in() && function_exists( 'sfwd_lms_has_access' ) && sfwd_lms_has_access( $course_id, get_current_user_id() );
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
							<?php echo $has_access ? esc_html__( 'Continuar curso', 'theme-sagicc-academy' ) : esc_html__( 'Ver curso', 'theme-sagicc-academy' ); ?>
						</a>
					</div>
				</div>
			</article>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
	<?php
	return ob_get_clean();
} );

add_shortcode( 'sagicc_videos_list', function() {
	$lang = isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], array('es', 'en')) ? $_COOKIE['lang'] : 'es';

	$translations = array(
		'es' => array(
			'no_videos' => 'No hay videos disponibles.',
			'view_video' => 'Ver video',
		),
		'en' => array(
			'no_videos' => 'No videos available.',
			'view_video' => 'Watch video',
		)
	);

	$t = function ($key) use ($translations, $lang) {
		return isset($translations[$lang][$key]) ? $translations[$lang][$key] : $key;
	};

	$videos_query = new WP_Query( array(
		'post_type'      => 'video',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	) );

	if ( ! $videos_query->have_posts() ) {
		return '<p class="text-gray-400 font-medium text-base font-sans">' . esc_html( $t('no_videos') ) . '</p>';
	}

	ob_start();
	?>
	<div class="sa-grid">
		<?php while ( $videos_query->have_posts() ) : $videos_query->the_post(); 
			$video_id = get_the_ID();
			$permalink = get_permalink();
			$thumbnail_url = get_the_post_thumbnail_url( $video_id, 'large' );
			?>
			<article class="sa-card">
				<a href="<?php echo esc_url( $permalink ); ?>" class="sa-card-media">
					<?php if ( $thumbnail_url ) : ?>
						<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php the_title_attribute(); ?>" class="sa-card-img">
					<?php else : ?>
						<div class="sa-card-media-placeholder">
							<i class="fa-solid fa-play"></i>
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
							<?php echo esc_html( $t('view_video') ); ?>
						</a>
					</div>
				</div>
			</article>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
	<?php
	return ob_get_clean();
} );

add_shortcode( 'sagicc_guias_list', function() {
	$lang = isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], array('es', 'en')) ? $_COOKIE['lang'] : 'es';

	$translations = array(
		'es' => array(
			'no_guides' => 'No hay guías disponibles.',
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

	$t = function ($key) use ($translations, $lang) {
		return isset($translations[$lang][$key]) ? $translations[$lang][$key] : $key;
	};

	$guias_query = new WP_Query( array(
		'post_type'      => 'guia',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	) );

	if ( ! $guias_query->have_posts() ) {
		return '<p class="text-gray-400 font-medium text-base font-sans">' . esc_html( $t('no_guides') ) . '</p>';
	}

	ob_start();
	?>
	<div class="sa-view-switcher">
		<div class="sa-view-toggle-group" role="group" aria-label="Cambiar vista">
			<button type="button" class="sa-view-toggle-btn active" data-view="list" title="<?php echo esc_attr( $t('view_list') ); ?>">
				<i class="fa-solid fa-list"></i>
				<span><?php echo esc_html( $t('view_list') ); ?></span>
			</button>
			<button type="button" class="sa-view-toggle-btn" data-view="grid" title="<?php echo esc_attr( $t('view_grid') ); ?>">
				<i class="fa-solid fa-border-all"></i>
				<span><?php echo esc_html( $t('view_grid') ); ?></span>
			</button>
		</div>
	</div>

	<div id="sa-guias-container" class="sa-view-container sa-view-list">
		<?php while ( $guias_query->have_posts() ) : $guias_query->the_post(); 
			$guia_id = get_the_ID();
			$permalink = get_permalink();
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
							<?php echo esc_html( $t('view_guide') ); ?>
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

add_shortcode('sagicc_archive_header', function($atts) {
	$atts = shortcode_atts( array(
		'post_type' => 'post',
	), $atts );

	$lang = isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], array('es', 'en')) ? $_COOKIE['lang'] : 'es';

	$translations = array(
		'video' => array(
			'es' => array(
				'title' => 'Videos',
				'desc'  => 'Contenido en video para potenciar tus habilidades.'
			),
			'en' => array(
				'title' => 'Videos',
				'desc'  => 'Video content to boost your skills.'
			)
		),
		'guia' => array(
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

	$title = isset($translations[$atts['post_type']][$lang]['title']) ? $translations[$atts['post_type']][$lang]['title'] : '';
	$desc = isset($translations[$atts['post_type']][$lang]['desc']) ? $translations[$atts['post_type']][$lang]['desc'] : '';

	ob_start();
	?>
	<header class="sa-header">
		<h1 class="sa-header-title">
			<?php echo esc_html($title); ?>
		</h1>
		<p class="sa-header-subtitle">
			<?php echo esc_html($desc); ?>
		</p>
	</header>
	<?php
	return ob_get_clean();
});

add_shortcode('sagicc_certificates_list', function () {
	$lang = isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], array('es', 'en')) ? $_COOKIE['lang'] : 'es';

	$translations = array(
		'es' => array(
			'no_login' => 'Debes iniciar sesión para ver tus certificados.',
			'no_certificates' => 'Aún no tienes certificados disponibles.',
			'download' => 'Descargar Certificado',
			'completed_on' => 'Completado el',
			'course' => 'Curso',
			'exam' => 'Examen',
		),
		'en' => array(
			'no_login' => 'You must log in to view your certificates.',
			'no_certificates' => 'You do not have any certificates available yet.',
			'download' => 'Download Certificate',
			'completed_on' => 'Completed on',
			'course' => 'Course',
			'exam' => 'Exam',
		)
	);

	$t = function ($key) use ($translations, $lang) {
		return isset($translations[$lang][$key]) ? $translations[$lang][$key] : $key;
	};

	if (!is_user_logged_in()) {
		return '<p class="text-gray-400 font-medium text-base font-sans">' . esc_html($t('no_login')) . '</p>';
	}

	$user_id = get_current_user_id();
	$certificates = array();

	// 1. Get Course Certificates
	if (function_exists('learndash_user_get_completed_courses')) {
		$completed_courses = learndash_user_get_completed_courses($user_id);
		if (!empty($completed_courses) && is_array($completed_courses)) {
			foreach ($completed_courses as $course_id) {
				if (function_exists('learndash_get_course_certificate_link')) {
					$cert_link = learndash_get_course_certificate_link($course_id, $user_id);
					if (!empty($cert_link)) {
						$completed_date = '';
						if (function_exists('learndash_user_course_completed_date')) {
							$completed_timestamp = learndash_user_course_completed_date($user_id, $course_id);
							if ($completed_timestamp) {
								$completed_date = wp_date(get_option('date_format'), $completed_timestamp);
							}
						}

						$certificates[] = array(
							'title'     => get_the_title($course_id),
							'link'      => $cert_link,
							'date'      => $completed_date,
							'type'      => 'course',
							'badge'     => $t('course'),
							'unique_id' => 'course-' . $course_id
						);
					}
				}
			}
		}
	}

	// 2. Get Quiz Certificates
	$user_quizzes = get_user_meta($user_id, '_sfwd-quizzes', true);
	if (is_array($user_quizzes)) {
		foreach ($user_quizzes as $quiz_attempt) {
			if (!empty($quiz_attempt['pass'])) {
				if (isset($quiz_attempt['certificate']['url']) && !empty($quiz_attempt['certificate']['url'])) {
					$cert_link = $quiz_attempt['certificate']['url'];
					$quiz_id = $quiz_attempt['quiz'];
					$completed_timestamp = isset($quiz_attempt['time']) ? $quiz_attempt['time'] : '';
					$completed_date = $completed_timestamp ? wp_date(get_option('date_format'), $completed_timestamp) : '';

					// Avoid exact duplicate links
					$exists = false;
					foreach ($certificates as $existing_cert) {
						if ($existing_cert['link'] === $cert_link) {
							$exists = true;
							break;
						}
					}

					if (!$exists) {
						$certificates[] = array(
							'title'     => get_the_title($quiz_id),
							'link'      => $cert_link,
							'date'      => $completed_date,
							'type'      => 'quiz',
							'badge'     => $t('exam'),
							'unique_id' => 'quiz-' . $quiz_id . '-' . $completed_timestamp
						);
					}
				}
			}
		}
	}

	if (empty($certificates)) {
		return '<p class="text-gray-400 font-medium text-base font-sans">' . esc_html($t('no_certificates')) . '</p>';
	}

	ob_start();
	?>
	<div class="sa-grid">
		<?php foreach ($certificates as $cert) : ?>
			<article class="sa-card">
				<div class="sa-card-body">
					<div>
						<div class="sa-card-header-meta">
							<span class="sa-badge">
								<?php echo esc_html($cert['badge']); ?>
							</span>
							<?php if (!empty($cert['date'])) : ?>
								<span class="sa-card-date">
									<?php echo esc_html($t('completed_on') . ' ' . $cert['date']); ?>
								</span>
							<?php endif; ?>
						</div>
						<h3 class="sa-card-title">
							<?php echo esc_html($cert['title']); ?>
						</h3>
					</div>
					<div class="sa-card-footer">
						<a href="<?php echo esc_url($cert['link']); ?>" target="_blank" rel="noopener noreferrer" class="sa-btn-card-solid">
							<?php echo esc_html($t('download')); ?>
						</a>
					</div>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
});
