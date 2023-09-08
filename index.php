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
		$websites = array(
			'http://boldgrid-testing.local/',
			'http://boldgrid-testing.local/',
			'http://boldgrid-testing.local/',
			'http://boldgrid-testing.local/',
		);

		$table_content = '';
		
		function traverseArray( $array ) {
			foreach ( $array as $key => $value ) {
				if ( is_array( $value ) ) {
					echo '<tr><td><h3>' . $key . '</h3></td></tr>';
					traverseArray( $value );
				}	
				else {
					echo '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
				}
			}
		}

		?>
		<h1>Available Updates</h1>
		<?php
		foreach ( $websites as $website ) {
			echo '<table style="border: 1px solid black;">';
			echo '<th><h2>' . $website . '</h2></th>';
			$api_response = wp_remote_get( $website . '?beacon_api=updates' );
			if ( is_wp_error( $api_response ) ) {
				$table_content =  print( '<tr><td>Error establishing connection to remote site.</td></tr>' );
			} else {
				$contents = wp_remote_retrieve_body( $api_response );
				$site_updates = json_decode( $contents, true, 3 );
				$table_content = traverseArray( $site_updates );
			}
			$table_content;
			echo '</table>';
		}	
	}