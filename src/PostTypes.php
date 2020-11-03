<?php
namespace KnowledgeBase;

class PostTypes {

	public static function init() {

		$post_types = PostTypes::get_post_types_definitions();
		foreach ($post_types as $post_type) {
			register_post_type( $post_type['name'], $post_type['args']);
			if ( $post_type['options']['has_cats'] == true ) {
				register_taxonomy_for_object_type( 'category', $post_type['name'] );
			}
			if ( $post_type['options']['has_tags'] == true ) {
				register_taxonomy_for_object_type( 'post_tag', $post_type['name'] );
			}
		}
	}

	/**
	 * Defines the labels for the custom post type
	 *
	 * @since    1.0.0
	 */
	public static function labels( $label_singular, $label_plural, $label_featured_image ) {

		$label_singular_lc = strtolower( $label_singular );
		$label_plural_lc = strtolower( $label_plural );
		$label_featured_image_lc = strtolower( $label_featured_image );

	    $labels = array(
	        'name'                  		=> _x( $label_plural, 'Post type general name', 'plugin-text-domain' ),
	        'singular_name'         		=> _x( $label_singular, 'Post type singular name', 'plugin-text-domain' ),
        	'add_new'               		=> __( 'Add New', 'plugin-text-domain' ),
	        'add_new_item'          		=> __( 'Add New ' . $label_plural, 'plugin-text-domain' ),
					'edit_item'             		=> __( 'Edit ' . $label_plural, 'plugin-text-domain' ),
	        'new_item'              		=> __( 'New ' . $label_singular, 'plugin-text-domain' ),
	        'view_item'             		=> __( 'View ' . $label_singular, 'plugin-text-domain' ),
					'view_items'            		=> __( 'View ' . $label_plural, 'plugin-text-domain' ),
					'search_items'          		=> __( 'Search ' . $label_plural, 'plugin-text-domain' ),
					'not_found'             		=> __( 'No ' . $label_plural_lc . ' found.', 'plugin-text-domain' ),
	        'not_found_in_trash'    		=> __( 'No ' . $label_plural_lc . ' found in Trash.', 'plugin-text-domain' ),
					'parent_item_colon'     		=> __( 'Parent ' . $label_singular . ':', 'plugin-text-domain' ),
	        'all_items'             		=> __( 'All ' . $label_plural, 'plugin-text-domain' ),
					'archives'              		=> _x( $label_singular . ' archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'plugin-text-domain' ),
					'atrributes'            		=> _x( $label_singular . ' atrributes', 'Label for the attributes meta box. Default “Post Attributes”.', 'plugin-text-domain' ),
					'insert_into_item'      		=> _x( 'Insert into ' . $label_singular_lc, 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'plugin-text-domain' ),
	        'uploaded_to_this_item' 		=> _x( 'Uploaded to this ' . $label_singular_lc, 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'plugin-text-domain' ),
	        'featured_image'        		=> _x( $label_featured_image, 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'plugin-text-domain' ),
	        'set_featured_image'    		=> _x( 'Set ' . $label_featured_image, 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'plugin-text-domain' ),
	        'remove_featured_image' 		=> _x( 'Remove ' . $label_featured_image, 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'plugin-text-domain' ),
	        'use_featured_image'    		=> _x( 'Use as ' . $label_featured_image, 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'plugin-text-domain' ),
					'menu_name'             		=> _x( $label_plural, 'Admin Menu text', 'plugin-text-domain' ),
	        'filter_items_list'     		=> _x( 'Filter ' . $label_plural_lc . ' list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'plugin-text-domain' ),
	        'items_list_navigation' 		=> _x( $label_plural . ' list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'plugin-text-domain' ),
	        'items_list'            		=> _x( $label_plural . ' list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'plugin-text-domain' ),
					'item_published'        		=> _x( $label_singular . ' published.', 'Label used when an item is published. Default is “Post published”.', 'plugin-text-domain' ),
					'item_published_privately'  => _x( $label_singular . ' published privately.', 'Label used when an item is published with private visibility. Default is “Post published privately”.', 'plugin-text-domain' ),
					'item_reverted_to_draft'    => _x( $label_singular . ' reverted to draft.', 'Label used when an item is switched to a draft. Default is ‘Post reverted to draft.’', 'plugin-text-domain' ),
					'item_scheduled'        		=> _x( $label_singular . ' scheduled.', 'Label used when an item is scheduled for publishing. Default is ‘Post scheduled.’', 'plugin-text-domain' ),
					'item_updated'        			=> _x( $label_singular . ' updated.', 'Label used when an item is updated. Default is ‘Post updated.’', 'plugin-text-domain' ),
	    );

		return $labels;
	}

	/**
	 * Defines settings for a list of custom post types
	 *
	 * @since    1.0.0
	 */
	public static function get_post_types_definitions() {

		$post_types = array();

		// Liberal History Events Post Type Definition --->
		$post_type_name = 'guide';
		$label_singular = 'Guide';
		$label_plural = 'Guides';
		$label_featured_image = 'Featured Image';
		$post_types[] =  array(
			'name' => $post_type_name,
			'args' => array(

			// Post type arguments.
			'public'              => true,
			'description'         => 'Knowledge base guides. Guides provide information, advice or instruction to help someone complete a specific task or explain in plain English how the user can follow a policy.',
			'hierarchical'        => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
			'menu_position'       => 20,
			'query_var'           => true,
			'can_export'          => true,
			'delete_with_user'    => true,
			'has_archive'         => 'guides',
			'rest_base'           => '',
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-sos',
			'capability_type'     => 'page',

			// The rewrite handles the URL structure.
			'rewrite' => [
				'slug'       => 'guides',
				'with_front' => false,
				'pages'      => true,
				'feeds'      => true,
				'ep_mask'    => EP_PERMALINK,
			],

			// Features the post type supports.
			'supports' => [
				'title',
				'editor',
				'excerpt',
				'thumbnail'
			],

			// Text labels.
			'labels' => PostTypes::labels( $label_singular, $label_plural, $label_featured_image )
			),
			'options' => array(
				'has_cats' => false,
				'has_tags' => false
			)
		);
		// ---> End of Definition

		return $post_types;

		}

}
