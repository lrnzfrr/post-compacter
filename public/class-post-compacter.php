
<?php
class Post_Compacter
{
    protected static $instance = null;
    
    private function __construct() {
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
}