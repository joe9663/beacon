<?php
/**
 * OmniDash
 * 
 *          @wordpress-plugin
 *          Plugin Name: OmniDash
 *          Plugin URI: https://www.imh.com/
 *          Description: A plugin designed to improve your update management.
 *          Version: 1.0.0
 *          Author: IMH
 *          Author URI: https://www.imh.com/
 *          License: GPL-2.0+
 *          License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *          Text Domain: omnidash
 *          Domain Path: /languages
 *
 **/

// Front end API calls.
	if ( ! empty( $_GET['beacon_api'] ) ) {
		
		include( 'class-api.php' );
		
		add_action( 'template_redirect', 'Beacon\api::run' );
		
	}

// Dashboard
	add_action( 'admin_menu', 'od_admin_menu' );

	function od_admin_menu() {
		add_menu_page( 'OmniDash', 'OmniDash', 'manage_options', 'omnidash-admin-page.php', 'od_admin_page', 'dashicons-info-outline', 6  );
	}

	function od_admin_page() {
		$api_response = wp_remote_get( 'http://boldgrid-testing.local/?beacon_api=updates' );
		$contents = wp_remote_retrieve_body( $api_response );
		$site_updates = json_decode( $contents, true, 3 );
		?>
		<h1>Available Updates</h1>
		<?php

		function traverseArray( $array ) {
			foreach ( $array as $key => $value ) {
				echo '<tr>';
				if ( is_array( $value ) ) {
					echo '<td><h3>' . $key . '</h3></td>';
					traverseArray( $value );
				}	
				else {
					echo '<td>' . $key . '</td><td>' . $value . '</td></tr>';
				}
			}
		}

		echo '<table>';
		traverseArray( $site_updates );
		echo '</table>';
	}