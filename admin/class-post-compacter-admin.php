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
 * Post Compacter Admin
 */
class Post_Compacter_Admin {

	/**
	 * Instance var
	 *
	 * @var $instance
	 */
	protected static $instance = null;


	/**
	 * Plugin slug
	 *
	 * @var $plugin_slug
	 */
	protected $plugin_slug = 'post-compacter';


	/**
	 * Contruct
	 *
	 * @return void
	 */
	private function __construct() {

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
	}

	/**
	 * Get Instance
	 *
	 * @return instance
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}



	/**
	 * Add plugin admin menu
	 *
	 * @return void
	 */
	public function add_plugin_admin_menu() {
		add_menu_page(
			'',
			'Post Compacter',
			'manage_options',
			$this->plugin_slug,
			array( &$this, 'display_plugin_admin_page' ),
			'dashicons-archive'
		);
		add_submenu_page( $this->plugin_slug, 'Redirects', 'Redirects', 'manage_options', 'post_compacter_display_redirects', array( &$this, 'display_redirects' ) );

	}

	/**
	 * Display Redirects
	 *
	 * @return void
	 */
	public function display_redirects() {
		global $wpdb;
		$items_per_page = 20;

		$page   = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		$offset = ( $page * $items_per_page ) - $items_per_page;

		if ( isset( $_POST ) && isset( $_POST['add_redirect'] ) ) {
			$this->insert_redirect_data( wp_unslash( $_POST['post_compacter_redirect_from'] ), wp_unslash( $_POST['post_compacter_redirect_to'] ) );
		}

		if ( isset( $_POST ) && isset( $_POST['delete_redirect'] ) ) {
			$this->delete_redirect( $_POST['delete_redirect'] );
		}

		$table_name = $wpdb->prefix . 'post_compacter_redirects';
		$search     = '';
		if ( isset( $_GET ) && isset( $_GET['search_redirect'] ) && trim( $_GET['search_redirect'] ) != '' ) {
			$search     = wp_unslash( $_GET['search_redirect'] );
			$conditions = " WHERE ( old_url LIKE '%$search%' OR new_url LIKE '%$search%')";
		}
		$sql       = "SELECT * FROM  $table_name  $conditions ORDER BY id DESC";
		$sql_count = "SELECT COUNT(1) FROM $table_name  $conditions";

		$total  = $wpdb->get_var( $wpdb->prepare( $sql_count ) );
		$result = $wpdb->get_results( $wpdb->prepare( $sql . ' LIMIT ' . $offset . ', ' . $items_per_page ), OBJECT );
		require 'view/display-redirects.php';
	}
	/**
	 * Display Plugin Admin Page
	 *
	 * @return void
	 */
	public function display_plugin_admin_page() {
		if ( isset( $_POST ) && isset( $_POST['post_compacter_action'] ) ) {
			$action = $_POST['post_compacter_action'];
			if ( 'insert_in_page' == $action ) {
				$this->insert_in_page( $_POST );
			} elseif ( 'delete_posts' == $action ) {
				$this->delete_posts( $_POST );
			}
		} else {
			require 'view/admin.php';
		}

	}

	/**
	 * Insert in page
	 *
	 * @param  mixed $data
	 * @throws exception
	 * @return void
	 */
	private function insert_in_page( $data ) {

		$append_body = [];
		$redirects   = [];

		$page = get_post( trim( $data['post_compacter_page_id'] ) );
		if ( ! $page ) {
			throw new \Exception( __( 'No page found', $this->plugin_slug ) );
		}
		$main_redirect = get_permalink( $data['post_compacter_page_id'] );
		$post_ids      = explode( "\n", trim( $data['post_compacter_ids'] ) );

		$args  = array(
			'posts_per_page' => 100,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'post__in'       => $post_ids,
		);
		$posts = get_posts( $args );

		foreach ( $posts as $post ) {
			$post_id   = $post->ID;
			$post_date = date( 'd-m-Y H:i', strtotime( $post->post_date_gmt ) );

			$author_name    = get_the_author_meta( 'display_name', $post->post_author );
			$body_to_append = "<div id='pc_post_" . $post_id . "' class='pc_archived_posts'><h2>" . $post->post_title . "</h2><div class='pc_info'><span class='pc_date'>" . __( 'updated at', $this->plugin_slug ) . ' ' . $post_date . "</span> - <span class='pc_author'>$author_name</span></div><div id='pc_body_" . $post_id . "' class='pc_posts_body'>" . $post->post_content . '</div></div>';
			$append_body[]  = $body_to_append;
			$this->insert_redirect_data( str_replace( home_url(), '', get_permalink( $post_id ) ), $main_redirect . '#pc_post_' . $post_id );
			$redirects[] = str_replace( home_url(), '', get_permalink( $post_id ) ) . ' ' . $main_redirect . '#pc_post_' . $post_id;

			// eliminare subito? 
			if(isset($_POST['delete_posts'])) {
				wp_delete_post( $post_id );
			}
		}
		// mod Page / Post:
		$my_post = array(
			'ID'           => $data['post_compacter_page_id'],
			'post_content' => $page->post_content . implode( '<br>', $append_body ),
		);
		wp_update_post( $my_post );
		require 'view/insert-in-page.php';
	}

	/**
	 * Delete Posts
	 *
	 * @param  mixed $data
	 *
	 * @return void
	 */
	public function delete_posts( $data ) {
		$post_ids = explode( "\n", trim( $data['post_compacter_ids'] ) );
		foreach ( $post_ids as $post_id ) {
			wp_delete_post( $post_id );
		}
		require 'view/delete-posts.php';
	}

	/**
	 * Insert Redirects Data
	 *
	 * @param  mixed $old_url
	 * @param  mixed $new_url
	 *
	 * @return void
	 */
	public function insert_redirect_data( $old_url, $new_url ) {
		global $wpdb;
		$old_url    = trim( $old_url );
		$new_url    = trim( $new_url );
		$table_name = $wpdb->prefix . 'post_compacter_redirects';
		$created    = current_time( 'mysql' );
		$wpdb->insert( $table_name, compact( 'old_url', 'new_url', 'created' ) );
	}

	/**
	 * Delete Redirect
	 *
	 * @param mixed $id
	 *
	 * @return void
	 */
	public function delete_redirect( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'post_compacter_redirects';
		$wpdb->delete( $table_name, compact( 'id' ) );
	}
}
