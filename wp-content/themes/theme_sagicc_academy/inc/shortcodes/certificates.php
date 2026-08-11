<?php
/**
 * Certificates List Shortcode ([sagicc_certificates_list])
 *
 * @package theme_sagicc_academy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'sagicc_certificates_list', function () {
	$lang = isset( $_COOKIE['lang'] ) && in_array( $_COOKIE['lang'], array( 'es', 'en' ) ) ? $_COOKIE['lang'] : 'es';

	$translations = array(
		'es' => array(
			'no_login'        => 'Debes iniciar sesión para ver tus certificados.',
			'no_certificates' => 'Aún no tienes certificados disponibles.',
			'download'        => 'Descargar Certificado',
			'completed_on'    => 'Completado el',
			'course'          => 'Curso',
			'exam'            => 'Examen',
		),
		'en' => array(
			'no_login'        => 'You must log in to view your certificates.',
			'no_certificates' => 'You do not have any certificates available yet.',
			'download'        => 'Download Certificate',
			'completed_on'    => 'Completed on',
			'course'          => 'Course',
			'exam'            => 'Exam',
		)
	);

	$t = function ( $key ) use ( $translations, $lang ) {
		return isset( $translations[ $lang ][ $key ] ) ? $translations[ $lang ][ $key ] : $key;
	};

	if ( ! is_user_logged_in() ) {
		return '<p class="text-gray-400 font-medium text-base font-sans">' . esc_html( $t( 'no_login' ) ) . '</p>';
	}

	$user_id      = get_current_user_id();
	$certificates = array();

	// 1. Obtener cursos completados
	$completed_courses = array();
	if ( function_exists( 'learndash_user_get_completed_courses' ) ) {
		$completed_courses = learndash_user_get_completed_courses( $user_id );
	}
	if ( empty( $completed_courses ) ) {
		$all_user_meta = get_user_meta( $user_id );
		if ( is_array( $all_user_meta ) ) {
			foreach ( $all_user_meta as $meta_key => $meta_val ) {
				if ( strpos( $meta_key, 'course_completed_' ) === 0 && ! empty( $meta_val[0] ) ) {
					$cid = (int) str_replace( 'course_completed_', '', $meta_key );
					if ( $cid > 0 ) {
						$completed_courses[] = $cid;
					}
				}
			}
		}
	}

	if ( ! empty( $completed_courses ) && is_array( $completed_courses ) ) {
		foreach ( $completed_courses as $course_id ) {
			$cert_link = '';
			if ( function_exists( 'learndash_get_course_certificate_link' ) ) {
				$cert_link = learndash_get_course_certificate_link( $course_id, $user_id );
			}
			if ( empty( $cert_link ) ) {
				$cert_link = get_permalink( $course_id );
			}

			$completed_date = '';
			if ( function_exists( 'learndash_user_course_completed_date' ) ) {
				$completed_timestamp = learndash_user_course_completed_date( $user_id, $course_id );
				if ( $completed_timestamp ) {
					$completed_date = wp_date( get_option( 'date_format' ), $completed_timestamp );
				}
			}
			if ( empty( $completed_date ) ) {
				$meta_date = get_user_meta( $user_id, 'course_completed_' . $course_id, true );
				if ( $meta_date && is_numeric( $meta_date ) ) {
					$completed_date = wp_date( get_option( 'date_format' ), (int) $meta_date );
				}
			}

			$certificates[] = array(
				'title'     => get_the_title( $course_id ),
				'link'      => $cert_link,
				'date'      => $completed_date,
				'type'      => 'course',
				'badge'     => $t( 'course' ),
				'unique_id' => 'course-' . $course_id
			);
		}
	}

	// 2. Obtener certificados de Exámenes/Quizzes
	$user_quizzes = get_user_meta( $user_id, '_sfwd-quizzes', true );
	if ( is_array( $user_quizzes ) ) {
		foreach ( $user_quizzes as $quiz_attempt ) {
			if ( ! empty( $quiz_attempt['pass'] ) && isset( $quiz_attempt['certificate']['url'] ) && ! empty( $quiz_attempt['certificate']['url'] ) ) {
				$cert_link           = $quiz_attempt['certificate']['url'];
				$quiz_id             = $quiz_attempt['quiz'];
				$completed_timestamp = isset( $quiz_attempt['time'] ) ? $quiz_attempt['time'] : '';
				$completed_date      = $completed_timestamp ? wp_date( get_option( 'date_format' ), $completed_timestamp ) : '';

				$exists = false;
				foreach ( $certificates as $existing_cert ) {
					if ( $existing_cert['link'] === $cert_link ) {
						$exists = true;
						break;
					}
				}

				if ( ! $exists ) {
					$certificates[] = array(
						'title'     => get_the_title( $quiz_id ),
						'link'      => $cert_link,
						'date'      => $completed_date,
						'type'      => 'quiz',
						'badge'     => $t( 'exam' ),
						'unique_id' => 'quiz-' . $quiz_id . '-' . $completed_timestamp
					);
				}
			}
		}
	}

	if ( empty( $certificates ) ) {
		return '<p class="text-gray-400 font-medium text-base font-sans">' . esc_html( $t( 'no_certificates' ) ) . '</p>';
	}

	ob_start();
	?>
	<div class="sa-grid">
		<?php foreach ( $certificates as $cert ) : ?>
			<article class="sa-card">
				<div class="sa-card-body">
					<div>
						<div class="sa-card-header-meta">
							<span class="sa-badge">
								<?php echo esc_html( $cert['badge'] ); ?>
							</span>
							<?php if ( ! empty( $cert['date'] ) ) : ?>
								<span class="sa-card-date">
									<?php echo esc_html( $t( 'completed_on' ) . ' ' . $cert['date'] ); ?>
								</span>
							<?php endif; ?>
						</div>
						<h3 class="sa-card-title">
							<?php echo esc_html( $cert['title'] ); ?>
						</h3>
					</div>
					<div class="sa-card-footer">
						<a href="<?php echo esc_url( $cert['link'] ); ?>" target="_blank" rel="noopener noreferrer" class="sa-btn-card-solid">
							<?php echo esc_html( $t( 'download' ) ); ?>
						</a>
					</div>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
} );
