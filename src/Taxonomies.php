<?php
namespace KnowledgeBase;

class Taxonomies {

	public static function init() {

		$taxonomies = Taxonomies::define_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			Taxonomies::register_taxonomy( $taxonomy );
		}
	}

	/**
	 * Defines the custom taxonomies included in the plugin
	 *
	 * Value taxonomy_type can be defined as:
	 * - category
	 * - tag
	 * - hidden
	 * - custom_metabox
	 * - alpha
	 * - or an array of custom arguments
	 *
	 * @since    1.0.0
	 */
	public static function define_taxonomies() {

		$custom_tax = array();

		$custom_tax[0] = array(
			'taxonomy_name' => 'guide-topic',
			'object_type' => 'guide',
			'taxonomy_type' => 'tag',
			'tax_label_singular' => 'Topic',
			'tax_label_plural' => 'Topics',
			'slug' => 'guide-topics',
			'description' => '',
			'display_post_class' => true,
			);

		$custom_tax[1] = array(
			'taxonomy_name' => 'guide-alpha',
			'object_type' => 'guide',
			'taxonomy_type' => 'alpha',
			'tax_label_singular' => 'Guides A to Z',
			'tax_label_plural' => 'Guides A to Z',
			'slug' => 'guide-atoz',
			'description' => '',
			'display_post_class' => false,
			);

		return $custom_tax;
	}

	/**
	 * Saves the first letter of the post field as the term in the alpha taxonomy for the defined post type
	 */
	public static function alphaindex_save_alpha( $post_id ) {

		// Exclude autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		// Exclude new posts
		if ( empty( $_POST['post_type'] ) )
			return;

		// Define post types with alpha taxonomies
		$post_types = array( 'guide', );

		// Check we are saving one of the defined post types
		if ( isset( $_POST['post_type'] ) && ( !in_array( $_POST['post_type'], $post_types ) ) )
			return;

		// Check permissions
		if ( !current_user_can( 'edit_post', $post_id ) )
			return;

		$letter = '';

		switch ( $_POST['post_type'] ) {
			case 'guide':
				$taxonomy = 'guide-alpha';
				$post_field = 'post_title';
				break;
			default:
				# code...
				break;
		}

		// Get the title of the post
		$title = strtolower( $_POST[$post_field] );

		// The next few lines remove A, An, or The from the start of the title
		$splitTitle = explode(" ", $title);
		$blacklist = array("an ","a ","the ");
		$splitTitle[0] = str_replace($blacklist,"",strtolower($splitTitle[0]));
		$title = implode(" ", $splitTitle);

		// Get the first letter of the title
		$letter = substr( $title, 0, 1 );

		// Set to 0-9 if it's a number
		if ( is_numeric( $letter ) ) {
			$letter = '0-9';
		}
		//set term as first letter of post title, lower case
		wp_set_post_terms( $post_id, $letter, $taxonomy );
	}

	/**
	 * Registers the custom taxonomies with WordPress
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function register_taxonomy( $taxonomy ) {

		$is_alpha = false;

		$taxonomy_name = $taxonomy['taxonomy_name'];
		$object_type = $taxonomy['object_type'];
		$taxonomy_type = $taxonomy['taxonomy_type'];
		$tax_label_singular = $taxonomy['tax_label_singular'];
		$tax_label_plural = $taxonomy['tax_label_plural'];
		$slug = $taxonomy['slug'];
		$description = $taxonomy['description'];

		if (is_array( $taxonomy_type )) {

			$args = $taxonomy_type;

		} else {

			switch ( $taxonomy_type ) {
				case 'category':
					$args = Taxonomies::category_args( $tax_label_singular, $tax_label_plural, $slug, $description );
					break;
				case 'tag':
					$args = Taxonomies::tag_args( $tax_label_singular, $tax_label_plural, $slug, $description );
					break;
				case 'hidden':
					$args = Taxonomies::hidden_args( $tax_label_singular, $tax_label_plural, $slug, $description );
					break;
				case 'custom_metabox':
					$args = Taxonomies::custom_metabox_args( $tax_label_singular, $tax_label_plural, $slug, $description );
					break;
				case 'alpha':
					$args = Taxonomies::alpha_args( $tax_label_singular, $tax_label_plural, $slug, $description );
					break;
				default:
					# code...
					break;
			}
		}

		register_taxonomy( $taxonomy_name, null, $args );

		if (is_array( $object_type )) {
			foreach ( $object_type as $object ) {
				register_taxonomy_for_object_type( $taxonomy_name, $object );
			}
		} else {
			register_taxonomy_for_object_type( $taxonomy_name, $object_type );
		}

		if ( 'alpha' == $taxonomy_type ) {
			$is_alpha = true;
		}

		add_filter( 'post_class', array( 'KnowledgeBase\Taxonomies', 'taxonomy_post_class' ) );

		if ( true == $is_alpha ) {

			 add_action( 'save_post',  array( 'KnowledgeBase\Taxonomies', 'alphaindex_save_alpha' ) );
		}
	}

	/**
	 * Defines the labels for the custom taxonomy
	 *
	 * @since    1.0.0
	 */
	public static function labels( $label_singular, $label_plural ) {

		$label_singular_lc = strtolower( $label_singular );
		$label_plural_lc = strtolower( $label_plural );

	    $labels = array(
				'name'                  		=> _x( $label_plural, 'Taxonomy general name', 'plugin-text-domain' ),
				'singular_name'         		=> _x( $label_singular, 'Taxonomy singular name', 'plugin-text-domain' ),
				'menu_name'             		=> _x( $label_plural, 'The menu name text.', 'plugin-text-domain' ),
				'all_items'             		=> __( 'All ' . $label_plural, 'plugin-text-domain' ),
				'view_item'             		=> __( 'View ' . $label_singular, 'plugin-text-domain' ),
				'edit_item'             		=> __( 'Edit ' . $label_singular, 'plugin-text-domain' ),
				'update_item'              	=> __( 'Update ' . $label_singular, 'plugin-text-domain' ),
				'add_new_item'          		=> __( 'Add New ' . $label_singular, 'plugin-text-domain' ),
				'new_item_name'            	=> __( 'New ' . $label_singular . ' Name', 'plugin-text-domain' ),
				'search_items'             	=> __( 'Search ' . $label_plural, 'plugin-text-domain' ),
				'popular_items'             => __( 'Popular ' . $label_plural, 'plugin-text-domain' ),
				'separate_items_with_commas'=> __( 'Separate ' . $label_plural_lc . ' with commas', 'plugin-text-domain' ),
				'add_or_remove_items'				=> __( 'Add or remove ' . $label_plural_lc, 'plugin-text-domain' ),
				'choose_from_most_used' 		=> __( 'Choose from the most used ' . $label_plural_lc, 'plugin-text-domain' ),
				'not_found'									=> __( 'No ' . $label_plural_lc . ' found.', 'plugin-text-domain' ),
				'not_found'									=> __( 'No ' . $label_plural_lc, 'plugin-text-domain' ),
				'items_list_navigation'    	=> __( $label_plural . 'list navigation', 'plugin-text-domain' ),
				'items_list'          			=> __( $label_plural . 'list', 'plugin-text-domain' ),
				'name_admin_bar'         		=> _x( $label_singular, 'plugin-text-domain' ),
				'select_name'              	=> __( 'Select ' . $label_singular, 'plugin-text-domain' ),
				'parent_item'              	=> __( 'Parent ' . $label_singular, 'plugin-text-domain' ),
				'parent_item_colon'        	=> __( 'Parent ' . $label_singular . ':', 'plugin-text-domain' ),
			);

		return $labels;
	}

	/**
	 * Sets up the arguments for a taxomony of the 'category' type
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function category_args( $tax_label_singular, $tax_label_plural, $slug, $description ) {

		$labels = Taxonomies::labels( $tax_label_singular, $tax_label_plural );

		$args = array(

			'labels' => $labels,

			// If the taxonomy should be publicly queryable.
			'public' => true,

            // Whether to generate a default UI for managing this taxonomy.
            'show_ui' => true,

			// Where to show the taxonomy in the admin menu.
            'show_in_menu' => true,

            // Makes this taxonomy available for selection in navigation menus.
			'show_in_nav_menus' => true,

			// Whether to allow the Tag Cloud widget to use this taxonomy.
			'show_tagcloud' => true,

			// Whether to show the taxonomy in the quick/bulk edit panel.
			'show_in_quick_edit' => true,

			// Whether to show the taxonomy in the REST API. Needed for tax to appear in Gutenberg editor.
			'show_in_rest' => true,

			// Provide a callback function name for the meta box display. If null uses default for categories or tags.
			'meta_box_cb' => null,

			// Whether to allow automatic creation of taxonomy columns on associated post-types table.
			'show_admin_column' => true,

			// Include a description of the taxonomy.
			'description' => $description,

			// Hierarchical taxonomy (like categories)
			'hierarchical' => true,

			'query_var' => true,
            'rewrite' => array(
					'slug' => $slug, // This controls the base slug that will display before each term
					'with_front' => true, // Display the category base before "/locations/"
					'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
					),
		);

		return $args;

	}

	/**
	 * Sets up the arguments for a taxomony of the 'tag' type
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function tag_args( $tax_label_singular, $tax_label_plural, $slug, $description ) {

		$labels = Taxonomies::labels( $tax_label_singular, $tax_label_plural );

		$args = array(

			'labels' => $labels,

			// If the taxonomy should be publicly queryable.
			'public' => true,

            // Whether to generate a default UI for managing this taxonomy.
            'show_ui' => true,

			// Where to show the taxonomy in the admin menu.
            'show_in_menu' => true,

            // Makes this taxonomy available for selection in navigation menus.
			'show_in_nav_menus' => false,

			// Whether to allow the Tag Cloud widget to use this taxonomy.
			'show_tagcloud' => true,

			// Whether to show the taxonomy in the quick/bulk edit panel.
			'show_in_quick_edit' => true,

			// Whether to show the taxonomy in the REST API. Needed for tax to appear in Gutenberg editor.
			'show_in_rest' => true,

			// Provide a callback function name for the meta box display. If null uses default for categories or tags.
			'meta_box_cb' => null,

			// Whether to allow automatic creation of taxonomy columns on associated post-types table.
			'show_admin_column' => false,

			// Include a description of the taxonomy.
			'description' => $description,

			// Hierarchical taxonomy (like categories)
			'hierarchical' => false,

			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
            'rewrite' => array(
					'slug' => $slug, // This controls the base slug that will display before each term
					'with_front' => true, // Display the category base before "/locations/"
					'hierarchical' => false // This will allow URL's like "/locations/boston/cambridge/"
					),
		);

		return $args;

	}

	/**
	 * Sets up the arguments for a taxomony of the 'hidden' type
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function hidden_args( $tax_label_singular, $tax_label_plural, $slug, $description ) {

		$labels = Taxonomies::labels( $tax_label_singular, $tax_label_plural );

		$args = array(

			'labels' => $labels,

			// If the taxonomy should be publicly queryable.
			'public' => true,

            // Whether to generate a default UI for managing this taxonomy.
            'show_ui' => false,

			// Where to show the taxonomy in the admin menu.
            'show_in_menu' => null,

            // Makes this taxonomy available for selection in navigation menus.
			'show_in_nav_menus' => false,

			// Whether to allow the Tag Cloud widget to use this taxonomy.
			'show_tagcloud' => null,

			// Whether to show the taxonomy in the quick/bulk edit panel.
			'show_in_quick_edit' => null,

			// Provide a callback function name for the meta box display. If null uses default for categories or tags.
			'meta_box_cb' => null,

			// Whether to allow automatic creation of taxonomy columns on associated post-types table.
			'show_admin_column' => false,

			// Include a description of the taxonomy.
			'description' => $description,

			// Hierarchical taxonomy (like categories)
			'hierarchical' => true,

			'query_var' => true,
            'rewrite' => array(
					'slug' => $slug, // This controls the base slug that will display before each term
					'with_front' => true, // Display the category base before "/locations/"
					'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
					),
		);

		return $args;

	}

	/**
	 * Sets up the arguments for a taxomony of the 'custom_metabox' type
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function custom_metabox_args( $tax_label_singular, $tax_label_plural, $slug, $description ) {

		$labels = Taxonomies::labels( $tax_label_singular, $tax_label_plural );

		$args = array(

			'labels' => $labels,

			// If the taxonomy should be publicly queryable.
			'public' => true,

            // Whether to generate a default UI for managing this taxonomy.
            'show_ui' => true,

			// Where to show the taxonomy in the admin menu.
            'show_in_menu' => true,

            // Makes this taxonomy available for selection in navigation menus.
			'show_in_nav_menus' => false,

			// Whether to allow the Tag Cloud widget to use this taxonomy.
			'show_tagcloud' => false,

			// Whether to show the taxonomy in the quick/bulk edit panel.
			'show_in_quick_edit' => false,

			// Provide a callback function name for the meta box display. If null uses default for categories or tags.
			'meta_box_cb' => false,

			// Whether to allow automatic creation of taxonomy columns on associated post-types table.
			'show_admin_column' => true,

			// Include a description of the taxonomy.
			'description' => $description,

			// Hierarchical taxonomy (like categories)
			'hierarchical' => true,

			'query_var' => true,
            'rewrite' => array(
					'slug' => $slug, // This controls the base slug that will display before each term
					'with_front' => true, // Display the category base before "/locations/"
					'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
					),
		);

		return $args;

	}

	/**
	 * Sets up the arguments for a taxomony of the 'alpha' type
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function alpha_args( $tax_label_singular, $tax_label_plural, $slug, $description ) {

		$labels = Taxonomies::labels( $tax_label_singular, $tax_label_plural );

		$args = array(

			'labels' => $labels,

			// If the taxonomy should be publicly queryable.
			'public' => true,

            // Whether to generate a default UI for managing this taxonomy.
            'show_ui' => false,

			// Where to show the taxonomy in the admin menu.
            'show_in_menu' => null,

            // Makes this taxonomy available for selection in navigation menus.
			'show_in_nav_menus' => false,

			// Whether to allow the Tag Cloud widget to use this taxonomy.
			'show_tagcloud' => null,

			// Whether to show the taxonomy in the quick/bulk edit panel.
			'show_in_quick_edit' => null,

			// Provide a callback function name for the meta box display. If null uses default for categories or tags.
			'meta_box_cb' => null,

			// Whether to allow automatic creation of taxonomy columns on associated post-types table.
			'show_admin_column' => false,

			// Include a description of the taxonomy.
			'description' => $description,

			// Hierarchical taxonomy (like categories)
			'hierarchical' => false,

			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
            'rewrite' => array(
					'slug' => $slug, // This controls the base slug that will display before each term
					'with_front' => true, // Display the category base before "/locations/"
					'hierarchical' => false // This will allow URL's like "/locations/boston/cambridge/"
					),
		);

		return $args;

	}

	/**
	 * Adds terms from a custom taxonomy to post_class
	 */
	public static function taxonomy_post_class( $classes ) {
		global $post;

		$custom_tax = Taxonomies::define_taxonomies();

		foreach ( $custom_tax as $ctax ) {

			if ( true == $ctax['display_post_class'] ) {

				$taxonomy = $ctax['taxonomy_name'];

			    $terms = get_the_terms( (int) $post->ID, $taxonomy );
			    if( !empty( $terms ) ) {
			        foreach( (array) $terms as $order => $term ) {
			            if( !in_array( $term->slug, $classes ) ) {
			                $classes[] = $term->slug;
			            }
			        }
			    }
			}
		}
	    return $classes;

	} // end taxonomy_post_class

}
