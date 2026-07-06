<?php
/**
 * Shortcodes de perfil de usuario y carrusel de autores.
 * Lógica original del usuario + Optimización CSS.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode [perfil_usuario]
 * Mantenemos la mejora de UX: si no está logueado, va al login, no a un enlace muerto '#'.
 */
function link_perfil_usuario_actual() {
	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();
		return esc_url( get_author_posts_url( $user->ID ) );
	}
	return esc_url( wp_login_url() );
}
add_shortcode( 'perfil_usuario', 'link_perfil_usuario_actual' );

/**
 * Helper: Nombre completo o fallback a display_name.
 */
function get_first_and_last_name_improved( $user ) {
	$first = get_user_meta( $user->ID, 'first_name', true );
	$last  = get_user_meta( $user->ID, 'last_name', true );

	if ( ! empty( $first ) || ! empty( $last ) ) {
		return trim( "$first $last" );
	}
	return $user->display_name;
}

/**
 * Shortcode [autores_grid]
 * Filtro original del usuario + CSS Grid/Scroll Snap
 */
function shortcode_autores_carrusel() {
	// LÓGICA RESTAURADA: Tu filtro original exacto
	$authors = get_users(
		[
			'role'    => 'Author',       // Volvemos a tu restricción de rol
			'orderby' => 'display_name', // Volvemos a tu orden alfabético
			'order'   => 'ASC',
            // 'has_published_posts' => true // DESCOMENTAR si quieres ocultar autores sin artículos
		]
	);

	if ( empty( $authors ) ) {
		return ''; 
	}

	ob_start();
	?>
	<style>
        /* Estilos Modernos encapsulados */
        .authors-container {
            --ac-bg: #fff;
            --ac-text: #333;
            --ac-accent: #1e1c66;
            --ac-scroll-track: #f1f1f1;
            --ac-scroll-thumb: #c1c1c1;
        }

		.authors-carousel {
			display: flex;
			gap: 1rem;
			overflow-x: auto;
			padding: 1.5rem 0.5rem;
			scroll-snap-type: x mandatory;
            scrollbar-width: thin;
            scrollbar-color: var(--ac-scroll-thumb) transparent;
            -webkit-overflow-scrolling: touch;
		}

        /* Scrollbar visible y estética */
        .authors-carousel::-webkit-scrollbar {
            height: 8px;
        }
        .authors-carousel::-webkit-scrollbar-track {
            background: var(--ac-scroll-track);
            border-radius: 4px;
        }
        .authors-carousel::-webkit-scrollbar-thumb {
            background-color: var(--ac-scroll-thumb);
            border-radius: 4px;
        }
        .authors-carousel::-webkit-scrollbar-thumb:hover {
            background-color: #a8a8a8;
        }

		.author-card {
            background: var(--ac-bg);
			flex: 0 0 160px; /* Ancho fijo */
			scroll-snap-align: start;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
			border-radius: 12px;
			padding: 20px 15px;
            /* Sombra suave moderna */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            border: 1px solid #eaeaea;
		}

        .author-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .author-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            background-color: #f3f4f6; /* Placeholder visual mientras carga */
        }

		.author-name {
            font-family: inherit;
			font-weight: 600;
            font-size: 0.95rem;
            color: var(--ac-text);
            line-height: 1.3;
		}

        .author-card:hover .author-name {
            color: var(--ac-accent);
        }
	</style>

	<div class="authors-container">
		<div class="authors-carousel">
			<?php foreach ( $authors as $author ) : 
                $name = get_first_and_last_name_improved( $author );
            ?>
				<a href="<?php echo esc_url( get_author_posts_url( $author->ID ) ); ?>" class="author-card">
					<?php echo get_avatar( $author->ID, 160, '', $name, ['class' => 'author-avatar'] ); ?>
					<span class="author-name">
						<?php echo esc_html( $name ); ?>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'autores_grid', 'shortcode_autores_carrusel' );