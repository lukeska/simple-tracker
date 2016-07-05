<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.versionebeta.com
 * @since      1.0.0
 *
 * @package    Simple_Tracker
 * @subpackage Simple_Tracker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Simple_Tracker
 * @subpackage Simple_Tracker/public
 * @author     Your Name <email@example.com>
 */
class Simple_Tracker_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $simple_tracker    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $simple_tracker       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/all.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// just register the main script, add to the page only when needed
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/simple-tracker-public.js', null, $this->version, true );

		$data = array(
			'nonce' => wp_create_nonce( 'simple-tracker' ),
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'pluginurl' => plugin_dir_url( __FILE__ ),
			'trackerinfourl' => get_permalink( get_page_by_path( 'tracker' ) ),
			'trackerswitchyear' => __('Visualizza tutto l\'anno', 'simple-tracker'),
			'trackerswitchmonth' => __('Visualizza solo il mese corrente', 'simple-tracker'),
			'trackerinfo' => sprintf(__('Utile questo tracker, vero? <a href="%s" target="_blank">Scopri di più</a>'), get_permalink( get_page_by_path( 'tracker' ) ))
		);

		wp_localize_script( $this->plugin_name, 'SimpleTracker', $data );
	}

	public function register_shortcodes() {
		add_shortcode( 'simple-tracker', array( $this, 'show_simple_tracker' ) );
	}

	public function register_ajax_calls() {

		add_action( 'wp_ajax_nopriv_get_target_data', array( $this, 'ajax_get_target_data' ) );
		add_action( 'wp_ajax_get_target_data', array( $this, 'ajax_get_target_data' ) );

		add_action( 'wp_ajax_update_target_data', array( $this, 'ajax_update_target_data' ) );

	}

	public function show_simple_tracker( $atts = array() ) {

		$trackerAtts = shortcode_atts( array(
	        'id' => '',
	    ), $atts );

		if($trackerAtts['id'] == '') return '';

		$target_id = $trackerAtts['id'];
		$target_post = get_post($target_id);

		if($target_post == null) return '';

		$tracker_is_editable = 0;

		if(current_user_can('administrator') || get_current_user_id () == $target_post->post_author)
			$tracker_is_editable = 1;

		$color = get_post_meta($target_id,'st_color', true);

		// enqueue main script and style only when shortcode is called 
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name );

		ob_start();
		
		echo '<div class="simple-tracker-wrapper">';
		echo '<div id="simple-tracker" data-target_id="' . $trackerAtts['id'] . '" data-is_editable="' . $tracker_is_editable . '">';
		echo '<div v-if="dataLoaded">';
		echo '<trackerheader :min-year="minYear" :year="year" :color="color" :title="title" :data-loaded="dataLoaded" :content="content" :monthly-view="monthlyView"></trackerheader>';
		echo '<months :activities="activities" :year="year" :is-editable="isEditable" :refreshing-data="refreshingData" color="' . $color . '" :monthly-view="monthlyView" ></months>';
		echo '</div>';
		echo '<div class="simple-tracker-loading" v-else><loader color="' . $color . '"></loader></div>';
		echo '</div>';
		echo '</div>';

		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function ajax_update_target_data() {

		$target_id = (isset($_POST['target_id'])) ? $_POST['target_id'] : false;
		$activity_date = (isset($_POST['activity_date'])) ? $_POST['activity_date'] : false;
		$activity_result = (isset($_POST['activity_result'])) ? $_POST['activity_result'] : 0;

		if(!$target_id || !$activity_date)
			return $this->_ajax_return(false);

		$target_post = get_post($target_id);

		if(!current_user_can('administrator') && !get_current_user_id () == $target_post->post_author)
			return $this->_ajax_return(false);

		$post_meta_name = 'st_track_' . substr($activity_date, 0, 4);

		$track_data = json_decode(get_post_meta($target_id , $post_meta_name, true), true);

	    $track_data[$activity_date] = $activity_result;

		update_post_meta($target_id ,$post_meta_name, json_encode($track_data));

		return $this->_ajax_return();
	}

	public function ajax_get_target_data() {

		$year = $_GET['year'];
		$target_id = $_GET['target_id'];

		$target_post = get_post($target_id);

		if($target_post == null) return $this->_ajax_return(false);

		$tracker_is_editable = false;

		if(current_user_can('administrator') || get_current_user_id () == $target_post->post_author)
			$tracker_is_editable = true;

		$color = get_post_meta($target_id ,'st_color', true);

		$track_data = get_post_meta($target_id ,'st_track_' . $year, true);

		if($track_data) $track_data = json_decode($track_data, true);
		else $track_data = array();

		$retVal = array(
			'title' => $target_post->post_title,
			'content' => $target_post->post_content,
			'color' => $color,
			'tracking_data' => $track_data,
			'is_editable' => $tracker_is_editable
		);

		return $this->_ajax_return($retVal);
	}

	private function _ajax_return( $response = true ) {
		echo json_encode( $response );
		exit;
	}

}
