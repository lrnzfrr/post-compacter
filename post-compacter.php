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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
require_once plugin_dir_path( __FILE__ ) . 'public/class-post-compacter.php';

register_activation_hook( __FILE__, 'post_compacter_activate' );
add_action( 'plugins_loaded', array( 'Post_Compacter', 'get_instance' ) );



if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once plugin_dir_path( __FILE__ ) . 'admin/class-post-compacter-admin.php';
	add_action( 'plugins_loaded', array( 'Post_Compacter_Admin', 'get_instance' ) );
} 
define('__POST_COMPACTER_PLUGIN_PATH__',plugin_dir_path(  __FILE__ ));


/**
 * Activation Post Compacter
 *
 * @return void
 */
function post_compacter_activate() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name      = $wpdb->prefix . 'post_compacter_redirects';

	$check = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name )) );
	if ( $check !== $table_name ) {
		$sql = 'CREATE TABLE ' . $table_name . "(
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            old_url VARCHAR(255),
            new_url VARCHAR(255),
            created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
            ) $charset_collate;";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		add_option( 'post_compacter_db_version', '1.1' );
		dbDelta( $sql );
	}
}
