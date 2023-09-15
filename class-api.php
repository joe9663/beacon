<?php
namespace Beacon;

class api {
	public static function run() {
		$endpoint = ! empty( $_GET['beacon_api'] ) ? $_GET['beacon_api'] : null;

		if ( 'updates' === $endpoint ) {
			self::all_updates();
			die();
			return;
		} else {
			return;
		}

		switch( $endpoint ) {
			case 'all':
				self::all_updates();
				die();
				break;
			case 'plugins':
				self::plugin_updates();
				die();
				break;
			case 'themes':
				self::theme_updates();
				die();
				break;
			case 'php':
				self::php_version();
				die();
				break;
			case 'wordpress':
				self::wordpress_updates();
				die();
				break;
			default:
				break;
		}
	}

	public static $updates_data = [];

	public static function all_updates() {

		self::plugins();
		self::themes();
		self::php();
		self::wp();

		echo json_encode( self::$updates_data );
	}

	public static function plugin_updates() {

		self::plugins();

		echo json_encode( self::$updates_data );
	}

	public static function theme_updates() {

		self::themes();
		
		echo json_encode( self::$updates_data );
	}

	public static function php_version() {

		self::php();

		echo json_encode( self::$updates_data );
	}

	public static function wordpress_updates() {

		self::wp();

		echo json_encode( self::$updates_data );
	}

	public static function plugins() {

		$plugins_data =[];

		foreach ( get_site_transient( 'update_plugins' )->response as $object ) {
			$plugins_data[$object->slug] = $object->new_version;
		}

		count( $plugins_data ) == 0 ? null : self::$updates_data['plugins'] = $plugins_data;
	}

	public static function themes() {

		$themes_data = [];

		foreach ( get_site_transient( 'update_themes' )->response as $object ) {
			$themes_data[$object['theme']] = $object['new_version'];
		}

		count( $themes_data ) == 0 ? null : self::$updates_data[ 'themes' ] = $themes_data;
	}

	public static function php() {

		self::$updates_data[ 'php' ][ 'version' ] = PHP_VERSION;
	}

	public static function wp() {

		require ABSPATH . WPINC . '/version.php';

		self::$updates_data[ 'wordpress' ][ 'core' ] = $wp_version;
	}
}