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
		$importer->access_token = esc_attr(get_option('fgram_ig_token'));

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



    function funkstagram_ig_redirect() {

        // if the user was redirected with a code AND the user is an admin
        if ( isset($_REQUEST['code']) && current_user_can('manage_options') ){

            // set up parameters of explicit auth call to instagram
            $params = array(
                'client_id'         => get_option('fgram_api_key'),
                'client_secret'     => get_option('fgram_api_secret'),
                'grant_type'        => 'authorization_code',
                'redirect_uri'      => site_url('/wp-admin/admin-ajax.php?action=funkstagram_ig_redirect'),
                'code'              => $_REQUEST['code']
            );
            $token_url = 'https://api.instagram.com/oauth/access_token';

            // send post request to insta API
            $response = wp_remote_post( $token_url, array(
            	'method' => 'POST',
            	'timeout' => 45,
            	'redirection' => 5,
            	'httpversion' => '1.0',
            	'blocking' => true,
            	'body' => $params
                )
            );

            // decode JSON response from body
            $json = json_decode(wp_remote_retrieve_body($response), true);

            // is response is holding an access token
            if ( isset($json['access_token']) ){

                // save token
                update_option('fgram_ig_token', $json['access_token']);

                // save user data
                update_option('fgram_ig_userdata', $json['user']);

            }

        }

        // redirect to funkstagram settings page
        wp_redirect(site_url('/wp-admin/tools.php?page=funkstagram_settings'));
        exit;
    }

    // Link instagram redirect to
    add_action( 'wp_ajax_funkstagram_ig_redirect', 'funkstagram_ig_redirect' );


	// Helper function to get this directory
	if ( ! function_exists( 'pp' ) ) {
	    function pp() {
	        return plugin_dir_url( __FILE__ );
	    }
	}

?>