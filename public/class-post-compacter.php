<?php
class Post_Compacter
{
    protected static $instance = null;
    
    private function __construct() {
		add_action( 'init', array( $this, 'check_for_redirect' ) );
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
    }

	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
    }
	
	public function load_plugin_textdomain() {	
        load_plugin_textdomain( 'post-compacter', false, plugin_dir_path( __FILE__ )  . '/languages/');
	}

	public function check_for_redirect() {
		global $wpdb;
		$table_name = $wpdb->prefix."post_compacter_redirects";
		global $wp;
		$old_url = $_SERVER['REQUEST_URI']; 
	 
		$sql = "SELECT * FROM  $table_name WHERE old_url = '$old_url'";
		$result = $wpdb->get_results ($sql);
		if($result) {
			header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: " . $result[0]->new_url); 
			exit;
		}
	}
}