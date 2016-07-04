<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Simple_Tracker
 * @subpackage Simple_Tracker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Simple_Tracker
 * @subpackage Simple_Tracker/admin
 * @author     Your Name <email@example.com>
 */
class Simple_Tracker_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $simple_tracker    The ID of this plugin.
	 */
	private $simple_tracker;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $simple_tracker       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $simple_tracker, $version ) {

		$this->simple_tracker = $simple_tracker;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Tracker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Tracker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->simple_tracker, plugin_dir_url( __FILE__ ) . 'css/simple-tracker-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Tracker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Tracker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->simple_tracker, plugin_dir_url( __FILE__ ) . 'js/simple-tracker-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Creates a new custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_post_type()
	 */
	public static function new_cpt_target() {

		$cap_type 	= 'post';
		$plural 	= 'Targets';
		$single 	= 'Target';
		$cpt_name 	= 'target';

		$opts['can_export']								= TRUE;
		$opts['capability_type']						= $cap_type;
		$opts['description']							= '';
		$opts['exclude_from_search']					= TRUE;
		$opts['has_archive']							= FALSE;
		$opts['hierarchical']							= FALSE;
		$opts['map_meta_cap']							= TRUE;
		$opts['menu_icon']								= 'dashicons-businessman';
		$opts['menu_position']							= 25;
		$opts['public']									= TRUE;
		$opts['publicly_querable']						= TRUE;
		$opts['query_var']								= TRUE;
		$opts['register_meta_box_cb']					= '';
		$opts['rewrite']								= FALSE;
		$opts['show_in_admin_bar']						= TRUE;
		$opts['show_in_menu']							= TRUE;
		$opts['show_in_nav_menu']						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['supports']								= array( 'title', 'editor', 'thumbnail', 'custom-fields', 'author' );
		$opts['taxonomies']								= array();

		$opts['capabilities']['delete_others_posts']	= "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']			= "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']			= "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']	= "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts']	= "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']		= "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']				= "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']				= "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']		= "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']	= "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']			= "publish_{$cap_type}s";
		$opts['capabilities']['read_post']				= "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']		= "read_private_{$cap_type}s";

		$opts['labels']['add_new']						= esc_html__( "Add New {$single}", 'simple-tracker' );
		$opts['labels']['add_new_item']					= esc_html__( "Add New {$single}", 'simple-tracker' );
		$opts['labels']['all_items']					= esc_html__( $plural, 'simple-tracker' );
		$opts['labels']['edit_item']					= esc_html__( "Edit {$single}" , 'simple-tracker' );
		$opts['labels']['menu_name']					= esc_html__( $plural, 'simple-tracker' );
		$opts['labels']['name']							= esc_html__( $plural, 'simple-tracker' );
		$opts['labels']['name_admin_bar']				= esc_html__( $single, 'simple-tracker' );
		$opts['labels']['new_item']						= esc_html__( "New {$single}", 'simple-tracker' );
		$opts['labels']['not_found']					= esc_html__( "No {$plural} Found", 'simple-tracker' );
		$opts['labels']['not_found_in_trash']			= esc_html__( "No {$plural} Found in Trash", 'simple-tracker' );
		$opts['labels']['parent_item_colon']			= esc_html__( "Parent {$plural} :", 'simple-tracker' );
		$opts['labels']['search_items']					= esc_html__( "Search {$plural}", 'simple-tracker' );
		$opts['labels']['singular_name']				= esc_html__( $single, 'simple-tracker' );
		$opts['labels']['view_item']					= esc_html__( "View {$single}", 'simple-tracker' );

		$opts['rewrite']['ep_mask']						= EP_PERMALINK;
		$opts['rewrite']['feeds']						= FALSE;
		$opts['rewrite']['pages']						= TRUE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $plural ), 'simple-tracker' );
		$opts['rewrite']['with_front']					= FALSE;

		$opts = apply_filters( 'simple-tracker-cpt-options', $opts );

		register_post_type( strtolower( $cpt_name ), $opts );

	} // new_cpt_target()

}
