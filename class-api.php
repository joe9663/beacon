<?php
namespace Beacon;

class api {
	public static function run() {
		$endpoint = ! empty( $_GET['beacon_api'] ) ? $_GET['beacon_api'] : null;

		if ( 'showmethemoney' === $endpoint ) {
			self::updates();
			die();
			return;
		} else {
			return;
		}
	}

	public static $updates_data = [];

	public static function updates() {

		self::plugins();
		self::themes();
		self::php();
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