<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.player.it
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
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
require_once( plugin_dir_path( __FILE__ ) . 'public/class-post-compacter.php' );
add_action( 'plugins_loaded', array( 'Post_Compacter', 'get_instance' ) );

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

    require_once( plugin_dir_path( __FILE__ ) . 'admin/class-post-compacter.php' );
    add_action( 'plugins_loaded', array( 'Post_Compacter_Admin', 'get_instance' ) );
}