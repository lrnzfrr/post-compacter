<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.flor.it
 * @since             1.0.0
 * @package           Post_Compacter
 *
 * @wordpress-plugin
 * Plugin Name:       Post Compacter
 * Plugin URI:        https://www.flor.it
 * Description:       This Plugin compact posts in a single page / post
 * Version:           1.0.0
 * Author:            Lorenzo Ferri
 * Author URI:        https://www.flor.it
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       post-compacter
 */

/**
 * Public Class Post_Compacter
 */
class Post_Compacter {
	/**
	 * Instance
	 *
	 * @var instance
	 */
	protected static $instance = null;

	/**
	 * Construct Method
	 *
	 * @return void
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'check_for_redirect' ) );
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Get Instance
	 *
	 * @return instance
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Load Plugin
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'post-compacter', false, '/post-compacter/languages/');
	}

	/**
	 * Check Redirects
	 *
	 * @return void
	 */
	public function check_for_redirect() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'post_compacter_redirects';
		global $wp;
		$old_url  = '';
		$request_uri = false;

		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$request_uri = wp_unslash( $_SERVER['REQUEST_URI'] );
			$old_url = $request_uri;
		}

		if ( false !== $request_uri ) {
			if( strstr( $request_uri, '?' ) ) {
				$tmp_str = explode( '?', $request_uri );
				$old_url = $tmp_str[0];
			}
		} else {
			return;
		}
	 
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM  $table_name WHERE old_url =%s", $wpdb->esc_like( $old_url ) ) );
		if ( $result ) {
			header( 'HTTP/1.1 301 Moved Permanently' );
			header( 'Location: ' . $result[0]->new_url );
			exit;
		}
	}
}
