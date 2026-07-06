<?php
/**
 * Register Custom Post Types
 *
 * @package theme_sagicc_academy
 */

function theme_sagicc_academy_register_post_types() {
	$labels = array(
		'name'                  => _x( 'Videos', 'Post Type General Name', 'theme_sagicc_academy' ),
		'singular_name'         => _x( 'Video', 'Post Type Singular Name', 'theme_sagicc_academy' ),
		'menu_name'             => __( 'Videos', 'theme_sagicc_academy' ),
		'name_admin_bar'        => __( 'Video', 'theme_sagicc_academy' ),
		'archives'              => __( 'Video Archives', 'theme_sagicc_academy' ),
		'attributes'            => __( 'Video Attributes', 'theme_sagicc_academy' ),
		'parent_item_colon'     => __( 'Parent Video:', 'theme_sagicc_academy' ),
		'all_items'             => __( 'All Videos', 'theme_sagicc_academy' ),
		'add_new_item'          => __( 'Add New Video', 'theme_sagicc_academy' ),
		'add_new'               => __( 'Add New', 'theme_sagicc_academy' ),
		'new_item'              => __( 'New Video', 'theme_sagicc_academy' ),
		'edit_item'             => __( 'Edit Video', 'theme_sagicc_academy' ),
		'update_item'           => __( 'Update Video', 'theme_sagicc_academy' ),
		'view_item'             => __( 'View Video', 'theme_sagicc_academy' ),
		'view_items'            => __( 'View Videos', 'theme_sagicc_academy' ),
		'search_items'          => __( 'Search Video', 'theme_sagicc_academy' ),
		'not_found'             => __( 'Not found', 'theme_sagicc_academy' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'theme_sagicc_academy' ),
		'featured_image'        => __( 'Featured Image', 'theme_sagicc_academy' ),
		'set_featured_image'    => __( 'Set featured image', 'theme_sagicc_academy' ),
		'remove_featured_image' => __( 'Remove featured image', 'theme_sagicc_academy' ),
		'use_featured_image'    => __( 'Use featured image', 'theme_sagicc_academy' ),
		'insert_into_item'      => __( 'Insert into video', 'theme_sagicc_academy' ),
		'uploaded_to_this_item' => __( 'Uploaded to this video', 'theme_sagicc_academy' ),
		'items_list'            => __( 'Videos list', 'theme_sagicc_academy' ),
		'items_list_navigation' => __( 'Videos list navigation', 'theme_sagicc_academy' ),
		'filter_items_list'     => __( 'Filter videos list', 'theme_sagicc_academy' ),
	);
	$args = array(
		'label'                 => __( 'Video', 'theme_sagicc_academy' ),
		'description'           => __( 'Video content type', 'theme_sagicc_academy' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-video-alt3',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'show_in_rest'          => true,
	);
	register_post_type( 'video', $args );

	// Register Guías Custom Post Type
	$labels_guias = array(
		'name'                  => _x( 'Guías', 'Post Type General Name', 'theme_sagicc_academy' ),
		'singular_name'         => _x( 'Guía', 'Post Type Singular Name', 'theme_sagicc_academy' ),
		'menu_name'             => __( 'Guías', 'theme_sagicc_academy' ),
		'name_admin_bar'        => __( 'Guía', 'theme_sagicc_academy' ),
		'archives'              => __( 'Guía Archives', 'theme_sagicc_academy' ),
		'attributes'            => __( 'Guía Attributes', 'theme_sagicc_academy' ),
		'parent_item_colon'     => __( 'Parent Guía:', 'theme_sagicc_academy' ),
		'all_items'             => __( 'All Guías', 'theme_sagicc_academy' ),
		'add_new_item'          => __( 'Add New Guía', 'theme_sagicc_academy' ),
		'add_new'               => __( 'Add New', 'theme_sagicc_academy' ),
		'new_item'              => __( 'New Guía', 'theme_sagicc_academy' ),
		'edit_item'             => __( 'Edit Guía', 'theme_sagicc_academy' ),
		'update_item'           => __( 'Update Guía', 'theme_sagicc_academy' ),
		'view_item'             => __( 'View Guía', 'theme_sagicc_academy' ),
		'view_items'            => __( 'View Guías', 'theme_sagicc_academy' ),
		'search_items'          => __( 'Search Guía', 'theme_sagicc_academy' ),
		'not_found'             => __( 'Not found', 'theme_sagicc_academy' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'theme_sagicc_academy' ),
		'featured_image'        => __( 'Featured Image', 'theme_sagicc_academy' ),
		'set_featured_image'    => __( 'Set featured image', 'theme_sagicc_academy' ),
		'remove_featured_image' => __( 'Remove featured image', 'theme_sagicc_academy' ),
		'use_featured_image'    => __( 'Use featured image', 'theme_sagicc_academy' ),
		'insert_into_item'      => __( 'Insert into guía', 'theme_sagicc_academy' ),
		'uploaded_to_this_item' => __( 'Uploaded to this guía', 'theme_sagicc_academy' ),
		'items_list'            => __( 'Guías list', 'theme_sagicc_academy' ),
		'items_list_navigation' => __( 'Guías list navigation', 'theme_sagicc_academy' ),
		'filter_items_list'     => __( 'Filter guías list', 'theme_sagicc_academy' ),
	);
	$args_guias = array(
		'label'                 => __( 'Guía', 'theme_sagicc_academy' ),
		'description'           => __( 'Guía content type', 'theme_sagicc_academy' ),
		'labels'                => $labels_guias,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 6,
		'menu_icon'             => 'dashicons-book-alt',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'show_in_rest'          => true,
	);
	register_post_type( 'guia', $args_guias );
}
add_action( 'init', 'theme_sagicc_academy_register_post_types', 0 );
