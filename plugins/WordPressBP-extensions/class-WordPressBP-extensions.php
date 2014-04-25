<?php

class WordPressBP_extensions {

	protected $plugin_slug = 'WordPressBP-extensions';

	protected static $instance = null;

	private function __construct() {

		add_action('init', array($this, 'WordPressBP_textdomain'));

		/**
		 * Init functions for custom post types and taxonomies
		 */
		//add_action('init', array($this, 'createTaxonomies'), 0);
		//add_action('init', array($this, 'createPostTypes'));

		/**
		 * Init main query modification
		 */
		//add_action('pre_get_posts', array($this, 'modifyQuery'));
	}

	public static function get_instance() {
		if(self::$instance == null) self::$instance = new self;
		return self::$instance;
	}

	public static function activate($network_wide) {
		if(function_exists('is_multisite') && is_multisite()) {
			if($network_wide) {
				$blog_ids = self::get_blog_ids();
				foreach ($blog_ids as $blog_id) {
					switch_to_blog( $blog_id );
					self::single_activate();
				}
				restore_current_blog();
			} else {
				self::single_activate();
			}
		} else {
			self::single_activate();
		}
	}

	public static function deactivate($network_wide) {
		if(function_exists('is_multisite') && is_multisite()) {
			if($network_wide) {
				$blog_ids = self::get_blog_ids();
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_deactivate();
				}
				restore_current_blog();
			} else {
				self::single_deactivate();
			}
		} else {
			self::single_deactivate();
		}
	}

	private static function get_blog_ids() {
		global $wpdb;
		$sql = "SELECT blog_id FROM $wpdb->blogs WHERE archived = '0' AND spam = '0' AND deleted = '0'";
		return $wpdb->get_col($sql);
	}

	/**
	 * Activation functionality
	 */
	private static function single_activate() {
		// flush_rewrite_rules();
	}

	/**
	 * Deactivation functionality
	 */
	private static function single_deactivate() {
		// flush_rewrite_rules();
	}


	/**
	 * On plugins loaded hook
	 */
	public function WordPressBP_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters('plugin_locale', get_locale(), $domain);

		load_textdomain($domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/lang/' );
	}



	/**
	 * Custom methods
	 */
	// Create custom post types
	/*
	public function createPostTypes() {
		$domain = $this->plugin_slug;

		register_post_type('slug', array(
			'labels'              => array(
				'name'              => __('Names', $domain),
				'singular_name'     => __('Name', $domain)
			),
			'supports'            => array('title', 'editor', 'author', 'thumbnail', 'comments', 'revisions'),
			'taxonomies'          => array('category', 'post_tag'),
			'public'              => true,
			'exclude_from_search' => true,
			'has_archive'         => true,
			'rewrite'             => true
		));
	}
	*/

	// Create custom taxonomies
	/*
	public function createTaxonomies() {
		$domain = $this->plugin_slug;

		register_taxonomy('slug', array('post-type'), array(
			'labels'          => array(
				'name'          => __('Names', $domain),
				'singular_name' => __('Name', $domain)
			),
			'hierarchical'    => true,
			//'update_count_callback' => '_update_post_term_count', // uncomment if hierarchical == false
			'public'          => true,
			'rewrite'         => false
		));
	}
	*/

	// Modify main query
	/*
	public function modifyQuery($query) {
		if(!is_admin() && $query->is_main_query()) {

			if(is_archive() || is_single() || is_home()) {
				// Example: Include a custom post type in query
				$query->set('post_type', array('post', 'custom_post_type'));
			}

		}
	}
	*/

}
