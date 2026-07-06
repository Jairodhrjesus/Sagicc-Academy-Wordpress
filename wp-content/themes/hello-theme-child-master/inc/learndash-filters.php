<?php
/**
 * Filtros para LearnDash según rol del usuario.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor Query: cursos_del_usuario
 * Muestra cursos del autor en páginas de autor.
 */
add_action(
	'elementor/query/cursos_del_usuario',
	function ( $query ) {
		if ( is_author() ) {
			$author_id = get_queried_object_id();
			$query->set( 'author', $author_id );
		}
	}
);

/**
 * Filtrar cursos por rol (oculta categorías a ciertos roles).
 */
function sagicc_filtrar_cursos_por_rol( WP_Query $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$user = wp_get_current_user();

	// No aplicar filtro a administradores
	if ( in_array( 'administrator', (array) $user->roles, true ) ) {
		return;
	}

	$ids_a_excluir = [];

	// 87 = ld_course_category 'empleados-sagicc'
	if ( ! in_array( 'um_empleado-sagicc', (array) $user->roles, true ) ) {
		$ids_a_excluir[] = 87;
	}

	// 86 = ld_course_category 'partner'
	if ( ! in_array( 'partner', (array) $user->roles, true ) ) {
		$ids_a_excluir[] = 86;
	}

	if ( $ids_a_excluir ) {
		$tax_query = [
			[
				'taxonomy' => 'ld_course_category',
				'field'    => 'term_id',
				'terms'    => $ids_a_excluir,
				'operator' => 'NOT IN',
			],
		];

		$existing = (array) $query->get( 'tax_query' );
		$query->set( 'tax_query', array_merge( $existing, $tax_query ) );
	}
}
add_action( 'pre_get_posts', 'sagicc_filtrar_cursos_por_rol' );

/**
 * Ocultar términos de la taxonomía ld_course_category según rol.
 */
function sagicc_ocultar_terminos_por_rol( $args, $taxonomies ) {
	if ( ! in_array( 'ld_course_category', (array) $taxonomies, true ) ) {
		return $args;
	}

	$user = wp_get_current_user();

	// No aplicar filtro a administradores
	if ( in_array( 'administrator', (array) $user->roles, true ) ) {
		return $args;
	}

	$ids_a_excluir = [];

	if ( ! in_array( 'um_empleado-sagicc', (array) $user->roles, true ) ) {
		$ids_a_excluir[] = 87;
	}
	if ( ! in_array( 'partner', (array) $user->roles, true ) ) {
		$ids_a_excluir[] = 86;
	}

	if ( $ids_a_excluir ) {
		$args['exclude'] = array_merge( (array) ( $args['exclude'] ?? [] ), $ids_a_excluir );
	}

	return $args;
}
add_filter( 'get_terms_args', 'sagicc_ocultar_terminos_por_rol', 10, 2 );
