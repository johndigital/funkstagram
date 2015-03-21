<?php 

/*
 *
 *	Plugin Name: Funkstagram
 *	Plugin URI: http://funkhaus.us
 *	Description: A simple Instagram image importer
 *	Author: Funkhaus
 *	Version: 1.3
 *	Author URI: http://Funkhaus.us
 *	Requires at least: 3.8
 * 
 */

 	// get funkstagram core and settings
	require_once('funkstagram-class.php');
	require_once('funkstagram-settings.php');

	// add metadata to attachments
	require_once('funkstagram-meta.php');

	function funkstagram_import() {
		$importer = new Funkstagram;


	// ------------Settings-----------//

		// STRING: Client api key
		$importer->api_key = esc_attr(get_option('fgram_api_key'));

		// INTEGER: ID of page to upload to
		$importer->page_id = esc_attr(get_option('fgram_att_page'));

		// STRING: Comma separated list of Instagram IDs
		$importer->user_ids = esc_attr(get_option('fgram_user_list'));

		// STRING: Comma separated list of tags
		$importer->tags = esc_attr(get_option('fgram_tag_list'));


	// ------------------------------//

		$importer->import();

	}

	// Set ten minute interval for cron
	function fgram_set_interval( $schedules ) {
		$schedules['ten_minutes'] = array(
			'interval' => 600,
			'display' => __('Every ten minutes')
		);
		return $schedules;
	}
	add_filter( 'cron_schedules', 'fgram_set_interval' );

	// If auto is set, schedule hourly cron
	if ( get_option('fgram_auto') ) {
		if ( ! wp_next_scheduled( 'fgram_cron' ) ) {
			wp_schedule_event( time(), 'ten_minutes', 'fgram_cron' );
		}

	// If auto is not set, clear cron
	} else {
		wp_clear_scheduled_hook( 'fgram_cron' );
	}

	// Hook import function to cron hook
	add_action( 'fgram_cron', 'funkstagram_import' );


	// Link function to admin-ajax
	add_action( 'wp_ajax_funkstagram_import', 'funkstagram_import' );
	add_action( 'wp_ajax_nopriv_funkstagram_import', 'funkstagram_import' );

	// Helper function to get this directory
	if ( ! function_exists( 'pp' ) ) {
	    function pp() {
	        return plugin_dir_url( __FILE__ );
	    }
	}

?>