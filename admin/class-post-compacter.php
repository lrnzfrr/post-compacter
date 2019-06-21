<?php
class Post_Compacter_Admin {


	protected static $instance = null;

    protected $plugin_screen_hook_suffix = null;
    
    protected $plugin_slug = 'post-compacter';


	/**
	 * __construct
	 *
	 * @return void
	 */
	private function __construct() {

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
	}

	/**
	 * get_instance
	 *
	 * @return void
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
 
	 
 
	/**
	 * add_plugin_admin_menu
	 *
	 * @return void
	 */
	public function add_plugin_admin_menu() {
		add_menu_page(
            '',
            'Post Compacter',
            'manage_options',
            $this->plugin_slug,
			array(&$this, 'display_plugin_admin_page'),
			'dashicons-archive'
		); 
		add_submenu_page( $this->plugin_slug, 'Redirects', 'Redirects', 'manage_options', 'display_redirects', array(&$this, 'display_redirects') );
	 

	}

	public function display_redirects() {
		global $wpdb;
		if(isset($_POST) && isset($_POST['add_redirect'])) {
			$this->insert_redirect_data($_POST['post_compacter_redirect_from'],$_POST['post_compacter_redirect_to']);
		}

		if(isset($_POST) && isset($_POST['delete_redirect'])) {
			$this->delete_redirect($_POST['delete_redirect']);
		}

		$table_name = $wpdb->prefix."post_compacter_redirects";
		$sql = "SELECT * FROM  $table_name WHERE old_url LIKE '$old_url%' ORDER BY id DESC";
		$result = $wpdb->get_results ($sql);
		require('view/display_redirects.php');
	}
	/**
	 * display_plugin_admin_page
	 *
	 * @return void
	 */
	public function display_plugin_admin_page() {
		if(isset($_POST) && isset($_POST['post_compacter_action'])) {
			$action = $_POST['post_compacter_action'];
			if($action == 'insert_in_page') {
				$this->insert_in_page($_POST);
			} elseif($action == 'delete_posts') {
				$this->delete_posts($_POST);
			}
		} else {
			require('view/admin.php');
		}
		
	}

	/**
	 * insert_in_page
	 *
	 * @param  mixed $data
	 *
	 * @return void
	 */
	private function insert_in_page($data) {

		$appendBody = [];
		$redirects = [];
	 
		$page =  get_post(trim($data['post_compacter_page_id']));
		if(!$page) {
			throw new \Exception(__( 'No page found', $this->plugin_slug ));
		}
		$mainRedirect = get_permalink($data['post_compacter_page_id']);
		$postIds = explode("\n",trim($data['post_compacter_ids']));

		$args = array(
			'posts_per_page'   => 100,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post__in'         => $postIds
		);
		$posts = get_posts( $args );

		foreach($posts as $post) {
			$postID = $post->ID;
			$postDate =  date("d-m-Y H:i", strtotime($post->post_date_gmt));
	
			$authorName = get_the_author_meta( 'display_name' , $post->post_author ); 
			$bodyToAppend =  "<div id='pc_post_" . $postID . "' class='pc_archived_posts'><h2>" . $post->post_title . "</h2><div class='pc_info'><span class='pc_date'>" . __( 'updated at', $this->plugin_slug ). " " . $postDate ."</span> - <span class='pc_author'>$authorName</span></div><div id='pc_body_" . $postID . "' class='pc_posts_body'>" . $post->post_content. "</div></div>";
			$appendBody[] = $bodyToAppend;
			$this->insert_redirect_data(str_replace(home_url(), '', get_permalink($postID)),$mainRedirect . '#pc_post_'.$postID );
			$redirects[] =  str_replace(home_url(), '', get_permalink($postID)) . " " . $mainRedirect . '#pc_post_'.$postID ;
		}
		// mod Page / Post:
		$my_post = array(
			'ID'           => $data['post_compacter_page_id'],
			'post_content' => $page->post_content .  implode("<br>",$appendBody)
		);
		wp_update_post( $my_post );
		require('view/insert_in_page.php');
	}

	/**
	 * delete_posts
	 *
	 * @param  mixed $data
	 *
	 * @return void
	 */
	public function delete_posts($data) {
		$postIds = explode("\n",trim($data['post_compacter_ids']));
		foreach($postIds as $postID) {
			wp_delete_post($postID);
		}		
		require('view/delete_posts.php');
	}

	/**
	 * insert_redirect_data
	 *
	 * @param  mixed $old_url
	 * @param  mixed $new_url
	 *
	 * @return void
	 */
	public function insert_redirect_data($old_url,$new_url) {
		global $wpdb;
		$table_name = $wpdb->prefix."post_compacter_redirects";
		$created = current_time( 'mysql' );
		$wpdb->insert($table_name,compact('old_url','new_url','created'));
	}
	
	/**
	 * insert_redirect_data
	 *
	 * @param  mixed $old_url
	 * @param  mixed $new_url
	 *
	 * @return void
	 */
	public function delete_redirect($id) {
		global $wpdb;
		$table_name = $wpdb->prefix."post_compacter_redirects";
		$wpdb->delete( $table_name, compact('id') );
	}
}
