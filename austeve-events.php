<?php
/**
 * Plugin Name: Events - Sustainable Saint John
 * Plugin URI: https://github.com/australiansteve/wp-plugin-austeve-events
 * Description: Add, edit & display events
 * Version: 2.0.1
 * Author: AustralianSteve
 * Author URI: http://AustralianSteve.com
 * License: GPL2
 */

class AUSteve_Events_CPT {

	function __construct() {

		add_action( 'init', array($this, 'register_post_type') );

		add_action( 'pre_get_posts', array($this, 'sort_by_date'), 999 );
	}

	function sort_by_date($query) {

		if ( ! is_admin() ) {

			//error_log("Before PRE_GET_POSTS: ".print_r($query, true));
			if ((is_archive('austeve-events') && $query->is_main_query()) || ($query->get('post_type') == 'austeve-events' && $query->get('apply_filters'))) 
			{
				// find date time now
				$date_now = date('Y-m-d H:i:s');
				$meta_query = array(
			        'key'			=> 'event_date',
			        'compare'		=> '>=',
			        'value'			=> $date_now,
			        'type'			=> 'DATETIME',
			    );
				$args = array(
					'post_type' 	=> 'austeve-events',
					'posts_per_page' => $query->get('posts_per_page'),
					'post_status' 	=> array('publish'),
					'meta_key'		=> 'event_date',
					'meta_type'		=> 'DATETIME',
					'orderby'		=> 'meta_value',
					'order' 		=> 'ASC',
					'meta_query' 	=> $meta_query
				);

				if (isset($_GET['past-events']))
				{
					$args['meta_query']['compare'] = '<=';
					$args['order'] = 'DESC';
				}

				$query->query_vars = $args;
			}
			//error_log("After PRE_GET_POSTS: ".print_r($query, true));
		}
		return $query;

	}

	function register_post_type() {

		// Set UI labels for Custom Post Type
		$labels = array(
			'name'                => _x( 'Events', 'Post Type General Name', 'austeve-events' ),
			'singular_name'       => _x( 'Event', 'Post Type Singular Name', 'austeve-events' ),
			'menu_name'           => __( 'Events', 'austeve-events' ),
			'all_items'           => __( 'All Events', 'austeve-events' ),
			'view_item'           => __( 'View Event', 'austeve-events' ),
			'add_new_item'        => __( 'Add New Event', 'austeve-events' ),
			'add_new'             => __( 'Add New', 'austeve-events' ),
			'edit_item'           => __( 'Edit Event', 'austeve-events' ),
			'update_item'         => __( 'Update Event', 'austeve-events' ),
			'search_items'        => __( 'Search Events', 'austeve-events' ),
			'not_found'           => __( 'Not Found', 'austeve-events' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'austeve-events' ),
		);
		
		// Set other options for Custom Post Type		
		$args = array(
			'label'               => __( 'Events', 'austeve-events' ),
			'description'         => __( 'Events', 'austeve-events' ),
			'labels'              => $labels,
			// Features this CPT supports in Post Editor
			'supports'            => array( 'title', 'editor', 'author', 'revisions', 'excerpt'),
			// You can associate this CPT with a taxonomy or custom taxonomy. 
			'taxonomies'          => array( ),
			/* A hierarchical CPT is like Pages and can have
			* Parent and child items. A non-hierarchical CPT
			* is like Posts.
			*/	
			'hierarchical'        => false,
			'rewrite'           => array( 'slug' => 'events' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'menu_icon'           => 'dashicons-calendar-alt',
		);
		
		// Registering your Custom Post Type
		register_post_type( 'austeve-events', $args );
	}
}

$austeveEvents = new AUSteve_Events_CPT();

