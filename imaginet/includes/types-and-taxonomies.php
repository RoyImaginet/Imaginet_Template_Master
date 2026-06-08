<?php
function news_post_type() {

	$labels = array(
		'name'                  => _x( 'News', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'News Item', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'News', 'text_domain' ),
		'name_admin_bar'        => __( 'News', 'text_domain' ),
		'archives'              => __( 'News Archives', 'text_domain' ),
		'attributes'            => __( 'News Attributes', 'text_domain' ),
		'all_items'             => __( 'All News items', 'text_domain' ),
		'add_new_item'          => __( 'Add New News item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New News item', 'text_domain' ),
		'edit_item'             => __( 'Edit News item', 'text_domain' ),
		'update_item'           => __( 'Update News item', 'text_domain' ),
		'view_item'             => __( 'View News item', 'text_domain' ),
		'view_items'            => __( 'View News', 'text_domain' ),
		'search_items'          => __( 'Search News item', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'News', 'text_domain' ),
		'description'           => __( 'Post Type Description', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-coffee',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'taxonomies' 			=> array('post_tag'),
		'show_in_rest' 			=> true,
	);
	register_post_type( 'news', $args );

}
add_action( 'init', 'news_post_type', 0 );

// Register Custom Taxonomy
function news_cat_taxonomy() {

	$labels = array(
		'name'                       => _x( 'News Categories', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'News Category', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'News Category', 'text_domain' ),
		'all_items'                  => __( 'News Categories', 'text_domain' ),
		'new_item_name'              => __( 'New Category Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Category', 'text_domain' ),
		'edit_item'                  => __( 'Edit Category', 'text_domain' ),
		'update_item'                => __( 'Update Category', 'text_domain' ),
		'view_item'                  => __( 'View Category', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'news_cat', array( 'news' ), $args );

}
add_action( 'init', 'news_cat_taxonomy', 0 );
