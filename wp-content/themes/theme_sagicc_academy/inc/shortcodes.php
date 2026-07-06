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
		<header class="mb-16 relative overflow-hidden rounded-3xl bg-secondary p-12 lg:p-16 text-white font-sans">
			<div class="relative z-10 max-w-2xl">
				<span
					class="inline-block px-4 py-1.5 bg-sagicc text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6">
					<?php echo esc_html($t('home.welcome_badge')); ?>
				</span>
				<h1 class="text-5xl lg:text-7xl font-black text-white leading-[0.95] tracking-tighter mb-6">
					<?php echo esc_html($t('home.hero_title')); ?>
				</h1>
				<p class="text-gray-400 text-lg lg:text-xl font-medium mb-10 max-w-lg">
					<?php echo esc_html($t('home.hero_desc')); ?>
				</p>
				<div class="flex flex-wrap gap-4">
					<a href="<?php echo esc_url(home_url('/login/')); ?>"
						class="px-8 py-4 bg-white text-secondary rounded-2xl font-black text-base hover:bg-gray-100 transition-all active:scale-95 shadow-xl shadow-white/10">
						<?php echo esc_html($t('home.cta_start')); ?>
					</a>
					<a href="<?php echo esc_url(home_url('/register/')); ?>"
						class="px-8 py-4 bg-white/10 text-white border border-white/10 rounded-2xl font-black text-base hover:bg-white/20 transition-all active:scale-95">
						<?php echo esc_html($t('home.cta_register')); ?>
					</a>
				</div>
			</div>

			<!-- Abstract Background Decoration -->
			<div class="absolute -right-20 -top-20 w-96 h-96 bg-sagicc/20 rounded-full blur-[120px]"></div>
			<div class="absolute right-20 bottom-0 w-64 h-64 bg-blue-500/20 rounded-full blur-[100px]"></div>
		</header>
	<?php else:
		$current_user = wp_get_current_user();
		$first_name = $current_user->user_firstname;
		if (empty($first_name)) {
			$first_name = $current_user->display_name;
		}
		?>
		<header class="mb-12 font-sans">
			<h1 class="text-4xl font-black text-secondary tracking-tighter mb-2">
				<?php echo esc_html($t('dashboard.welcome_back')); ?>, <?php echo esc_html($first_name); ?>
			</h1>
			<p class="text-gray-400 font-medium text-lg">
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
	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 font-sans">
		<?php while ( $courses_query->have_posts() ) : $courses_query->the_post(); 
			$course_id = get_the_ID();
			$permalink = get_permalink();
			$thumbnail_url = get_the_post_thumbnail_url( $course_id, 'large' );
			$has_access = is_user_logged_in() && function_exists( 'sfwd_lms_has_access' ) && sfwd_lms_has_access( $course_id, get_current_user_id() );
			?>
			<article class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
				<a href="<?php echo esc_url( $permalink ); ?>" class="relative aspect-[16/9] block overflow-hidden bg-gray-100">
					<?php if ( $thumbnail_url ) : ?>
						<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php the_title_attribute(); ?>" class="object-cover w-full h-full hover:scale-105 transition-transform duration-500">
					<?php else : ?>
						<div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-300">
							<i class="fa-solid fa-graduation-cap text-4xl"></i>
						</div>
					<?php endif; ?>
				</a>
				<div class="p-6 flex-1 flex flex-col">
					<h3 class="text-xl font-bold text-secondary mb-2 hover:text-sagicc transition-colors">
						<a href="<?php echo esc_url( $permalink ); ?>">
							<?php the_title(); ?>
						</a>
					</h3>
					<div class="text-gray-400 text-sm font-medium flex-1 mb-6">
						<?php echo wp_trim_words( get_the_excerpt(), 18 ); ?>
					</div>
					<div class="mt-auto">
						<a href="<?php echo esc_url( $permalink ); ?>" class="block w-full px-6 py-3.5 bg-blue-50 text-sagicc font-black text-center text-sm rounded-2xl hover:bg-secondary hover:text-white transition-all active:scale-[0.98]">
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
	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 font-sans">
		<?php while ( $videos_query->have_posts() ) : $videos_query->the_post(); 
			$video_id = get_the_ID();
			$permalink = get_permalink();
			$thumbnail_url = get_the_post_thumbnail_url( $video_id, 'large' );
			?>
			<article class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
				<a href="<?php echo esc_url( $permalink ); ?>" class="relative aspect-[16/9] block overflow-hidden bg-gray-100">
					<?php if ( $thumbnail_url ) : ?>
						<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php the_title_attribute(); ?>" class="object-cover w-full h-full hover:scale-105 transition-transform duration-500">
					<?php else : ?>
						<div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-300">
							<i class="fa-solid fa-play text-4xl"></i>
						</div>
					<?php endif; ?>
				</a>
				<div class="p-6 flex-1 flex flex-col">
					<h3 class="text-xl font-bold text-secondary mb-2 hover:text-sagicc transition-colors">
						<a href="<?php echo esc_url( $permalink ); ?>">
							<?php the_title(); ?>
						</a>
					</h3>
					<div class="text-gray-400 text-sm font-medium flex-1 mb-6">
						<?php echo wp_trim_words( get_the_excerpt(), 18 ); ?>
					</div>
					<div class="mt-auto">
						<a href="<?php echo esc_url( $permalink ); ?>" class="block w-full px-6 py-3.5 bg-blue-50 text-sagicc font-black text-center text-sm rounded-2xl hover:bg-secondary hover:text-white transition-all active:scale-[0.98]">
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
		),
		'en' => array(
			'no_guides' => 'No guides available.',
			'view_guide' => 'Read guide',
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
	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 font-sans">
		<?php while ( $guias_query->have_posts() ) : $guias_query->the_post(); 
			$guia_id = get_the_ID();
			$permalink = get_permalink();
			$thumbnail_url = get_the_post_thumbnail_url( $guia_id, 'large' );
			?>
			<article class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
				<a href="<?php echo esc_url( $permalink ); ?>" class="relative aspect-[16/9] block overflow-hidden bg-gray-100">
					<?php if ( $thumbnail_url ) : ?>
						<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php the_title_attribute(); ?>" class="object-cover w-full h-full hover:scale-105 transition-transform duration-500">
					<?php else : ?>
						<div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-300">
							<i class="fa-solid fa-book-open text-4xl"></i>
						</div>
					<?php endif; ?>
				</a>
				<div class="p-6 flex-1 flex flex-col">
					<h3 class="text-xl font-bold text-secondary mb-2 hover:text-sagicc transition-colors">
						<a href="<?php echo esc_url( $permalink ); ?>">
							<?php the_title(); ?>
						</a>
					</h3>
					<div class="text-gray-400 text-sm font-medium flex-1 mb-6">
						<?php echo wp_trim_words( get_the_excerpt(), 18 ); ?>
					</div>
					<div class="mt-auto">
						<a href="<?php echo esc_url( $permalink ); ?>" class="block w-full px-6 py-3.5 bg-blue-50 text-sagicc font-black text-center text-sm rounded-2xl hover:bg-secondary hover:text-white transition-all active:scale-[0.98]">
							<?php echo esc_html( $t('view_guide') ); ?>
						</a>
					</div>
				</div>
			</article>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
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
	<header class="mb-12 font-sans">
		<h1 class="text-4xl font-black text-secondary tracking-tighter mb-2">
			<?php echo esc_html($title); ?>
		</h1>
		<p class="text-gray-400 font-medium text-lg">
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
	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 font-sans">
		<?php foreach ($certificates as $cert) : ?>
			<article class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
				<div class="p-6 flex-1 flex flex-col justify-between">
					<div>
						<div class="flex items-center gap-2 mb-4">
							<span class="px-3 py-1 bg-blue-50 text-sagicc text-[11px] font-black uppercase tracking-wider rounded-full">
								<?php echo esc_html($cert['badge']); ?>
							</span>
							<?php if (!empty($cert['date'])) : ?>
								<span class="text-xs text-gray-400 font-medium">
									<?php echo esc_html($t('completed_on') . ' ' . $cert['date']); ?>
								</span>
							<?php endif; ?>
						</div>
						<h3 class="text-xl font-bold text-secondary mb-6 leading-snug">
							<?php echo esc_html($cert['title']); ?>
						</h3>
					</div>
					<div class="mt-auto">
						<a href="<?php echo esc_url($cert['link']); ?>" target="_blank" rel="noopener noreferrer" class="block w-full px-6 py-3.5 bg-sagicc text-white font-black text-center text-sm rounded-2xl hover:bg-secondary transition-all active:scale-[0.98]">
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
