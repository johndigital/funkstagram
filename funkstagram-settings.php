<?php

	/*
	 * admin Scripts and styles for plugin
	 */
	function funkstagram_admin_style() {
		wp_register_style( 'funk_css', pp() . '/css/funkstagram.admin.css' );
		wp_register_script( 'tagit_js', pp() . '/js/jquery.tagsinput.min.js' );
		wp_register_script( 'funk_js', pp() . '/js/funkstagram.admin.js' );

		if ( is_admin() ) {
			wp_enqueue_style( 'funk_css');
			wp_enqueue_script( 'tagit_js');
			wp_enqueue_script( 'funk_js');
		}
	}
	add_action( 'admin_init', 'funkstagram_admin_style' );


    /* Call Settings Page */
    function funkstagram_settings_page() {

        $params = array(
            'client_id'     => get_option('fgram_api_key'),
            'redirect_uri'  => site_url('/wp-admin/admin-ajax.php?action=funkstagram_ig_redirect'),
            'response_type' => 'code'
        );
        $auth_url = 'https://api.instagram.com/oauth/authorize/?' . http_build_query($params);

    ?>

		<div class="wrap">
			<h2>Funkstagram Options</h2>
			<form action="options.php" method="post" id="funkstagram_settings" data-cron="<?php echo site_url('/wp-admin/admin-ajax.php?action=funkstagram_import'); ?>">
				<?php settings_fields('funkstagram_settings'); ?>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><label>Enable auto-import:</label></th>
							<td>
								<input name="fgram_auto" type="checkbox" id="fgram_auto" <?php checked( get_option('fgram_auto') ); ?>  value="1">
								<p class="description">If enabled, it will import every 10 minutes.</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="fgram_api_key">Client ID:</label></th>
							<td>
								<input name="fgram_api_key" type="text" title="Client ID" id="fgram_api_key" value="<?php echo get_option('fgram_api_key'); ?>">
								<p class="description">http://instagram.com/developer/register/</p>
							</td>
                        </tr>
						<tr valign="top">
							<th scope="row"><label for="fgram_api_secret">Client Secret:</label></th>
							<td>
								<input name="fgram_api_secret" type="text" title="Client Secret" id="fgram_api_secret" value="<?php echo get_option('fgram_api_secret'); ?>">
							</td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label for="fgram_api_key">Authenticate Account:</label></th>
                            <td>
                                <?php if ( get_option('fgram_ig_token') && get_option('fgram_ig_userdata') ): ?>
                                    <?php $user = get_option('fgram_ig_userdata'); ?>

                                    <p>Authenticated as <strong><?php echo $user['full_name']; ?></strong></p>
                                    <a href="#" class="trash">Deauthenticate</a>

                                <?php elseif ( !empty(get_option('fgram_api_key')) ): ?>
                                    <a href="<?php echo $auth_url; ?>" class="button">Authenticate</a>

                                <?php else: ?>
                                    <p class="description">You must first set a client ID</p>

                                <?php endif; ?>
                            </td>
                        </tr>
						<tr valign="top">
							<th scope="row"><label for="fgram_tag_list">Redirect URL:</label></th>
							<td>
                                <code><?php echo site_url('/wp-admin/admin-ajax.php?action=funkstagram_ig_redirect'); ?></code>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="fgram_att_page">Page to attach to:</label></th>
							<td>
								<?php
								$args = array(
								    'id'            	=> 'fgram_att_page',
								    'name'          	=> 'fgram_att_page',
								    'selected'			=>  get_option('fgram_att_page'),
								    'show_option_none'  => 'None',
								    'option_none_value' => 0
								);
								wp_dropdown_pages( $args ); ?>
								<p class="description">All images will be attached to this page</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="fgram_default_status">Image default status:</label></th>
							<td>
								<?php $selected = get_option('fgram_default_status'); ?>
								<select name="fgram_default_status" id="fgram_default_status">
									<option <?php selected( $selected, 'draft' ); ?> value="draft">Draft</option>
									<option <?php selected( $selected, 'pending_review' ); ?> value="pending_review">Pending Review</option>
									<option <?php selected( $selected, 'published' ); ?> value="published">Published</option>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="fgram_user_list">Users to import:</label></th>
							<td>
								<input name="fgram_user_list" type="text" id="fgram_user_list" value="<?php echo get_option('fgram_user_list'); ?>">
								<p class="description">If left empty, all posts for specified tags will be imported. Do not include @ symbol.</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="fgram_tag_list">Filter by tags:</label></th>
							<td>
								<input name="fgram_tag_list" type="text" id="fgram_tag_list" value="<?php echo get_option('fgram_tag_list'); ?>">
								<p class="description">If left empty, all posts for specified users will be imported. Do not include # symbol.</p>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<input type="button" class="button hide-if-no-js" name="fgram_import" id="fgram_import" value="Import Now">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
				</p>
			</form>
		</div><!-- END Wrap -->

		<?php
	}

	/* Save Takeover Settings */
	function funkstagram_settings_init(){
		register_setting('funkstagram_settings', 'fgram_api_key');
		register_setting('funkstagram_settings', 'fgram_api_secret');
		register_setting('funkstagram_settings', 'fgram_att_page');
		register_setting('funkstagram_settings', 'fgram_default_status');
		register_setting('funkstagram_settings', 'fgram_user_list');
		register_setting('funkstagram_settings', 'fgram_tag_list');
		register_setting('funkstagram_settings', 'fgram_auto');
	}
	add_action('admin_init', 'funkstagram_settings_init');

	function funkstagram_add_settings() {
		add_submenu_page( 'tools.php', 'Funkstagram', 'Funkstagram', 'manage_options', 'funkstagram_settings', 'funkstagram_settings_page' );
	}

	add_action('admin_menu','funkstagram_add_settings');


	/* Add settings help menu dropdown */
	function funkstagram_plugin_help($contextual_help, $screen_id, $screen) {

		if ($screen_id == 'tools_page_funkstagram_settings') {

			$contextual_help = wp_remote_get( trailingslashit( pp() ) . 'funkstagram-help.php' );

		}

		if ( wp_remote_retrieve_response_code( $contextual_help ) == 200 ) {
			return wp_remote_retrieve_body( $contextual_help );
		}

	}

	add_filter('contextual_help', 'funkstagram_plugin_help', 10, 3);

?>