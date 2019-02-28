<?php

/*
Plugin Name: RDR2 Progress Tracker
Description: Red Dead Redemption 2 Progress Tracker Wordpress Plugin
Author: Glenn Davey
Version: 1.0
Text Domain: mk_framework
Domain Path: /languages/
*/

// Don't call the file directly
if ( ! defined( 'ABSPATH' ) ) exit;

ini_set('display_errors', 1);

if (!defined('DS')) define( 'DS', "/" ); // Directory separator
if (!defined( '__DIR__' )) define( '__DIR__', dirname( __FILE__ ) ); // DIR pseudo native constant
if (!defined('RDR2_DIR')) define( 'RDR2_DIR', __DIR__  . DS );
if (!defined('RDR2_CLASSES_DIR')) define( 'RDR2_CLASSES_DIR', RDR2_DIR . 'classes' . DS );

/*require RDR2_CLASSES_DIR . 'vendor' . DS . 'autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
*/

// Activation Hook
register_activation_hook( __FILE__, array( 'RDR2_ProgressTracker', 'activate' ) );


// Plugin
// Functions
// The jumping-off point for all logic 
// [Objects, Classes, Functions, Arrays, Global Variables]

date_default_timezone_set('Australia/Melbourne');


// Start Session
session_start();


// Globalize and use $rdr2 to access functions 
global $rdr2;


// Launch ProgressTracker Class
add_action("after_setup_theme", "LaunchProgressTracker");


function LaunchProgressTracker() {
	global $rdr2, $query_string;
	
	$inputdata = $_REQUEST;
	
	//print_r($inputdata);
	
	if (empty($inputdata)) 
		parse_str($query_string, $inputdata);
	
	$rdr2 = RDR2_ProgressTracker::init($inputdata);
}




/**
 RDR2_ProgressTracker class
 *
 * @class RDR2_ProgressTracker The class that holds the entire RDR2_ProgressTracker plugin
 */
final class RDR2_ProgressTracker {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Holds various class instances
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $container = array();
	
	private static $_singleton;
	private $terms;
	private $terms_extracted;
	
	var $first_name, $last_name, $state, $suburb, $sales, $game_id, $player_id; 


    /**
     * Initializes RDR2_ProgressTracker() class
     *
     * Checks for an existing RDR2_ProgressTracker() instance
     * and if it doesn't find one, creates it.
     */
    public static function init($inputdata = array()) {
		if (!self::$_singleton) 
			self::$_singleton = new RDR2_ProgressTracker($inputdata);
        
        return self::$_singleton;
    }



    /**
     * Constructor for RDR2_ProgressTracker class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses register_activation_hook()
     * @uses register_deactivation_hook()
     * @uses is_admin()
     * @uses add_action()
     */
    public function __construct($vars = FALSE) {
    	$this->terms = $vars;
        $this->define_constants();

        
        //register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		
		$this->init_plugin();
    }



    /**
     * Define all constants
     *
     * @return void
     */
    public function define_constants() {
    	// GLOBAL DEFINITIONS		
		if (!defined('RDR2_PREFIX')) define('RDR2_PREFIX', 'rdr2_');
        if (!defined('RDR2_PLUGIN_VERSION')) define( 'RDR2_PLUGIN_VERSION', $this->version );
        if (!defined('RDR2_FILE')) define( 'RDR2_FILE', __FILE__ );
        if (!defined('RDR2_INC_DIR')) define( 'RDR2_INC_DIR', RDR2_DIR . 'includes' . DS );
		if (!defined('RDR2_ADMIN_INC_DIR')) define( 'RDR2_ADMIN_INC_DIR', RDR2_INC_DIR . 'admin' . DS );
		if (!defined('RDR2_ADMIN_VIEWS_DIR')) define( 'RDR2_ADMIN_VIEWS_DIR', RDR2_ADMIN_INC_DIR . 'views' . DS );
		if (!defined('RDR2_PORTAL_INC_DIR')) define( 'RDR2_PORTAL_INC_DIR', RDR2_INC_DIR . 'portal' . DS );
		if (!defined('RDR2_PORTAL_VIEWS_DIR')) define( 'RDR2_PORTAL_VIEWS_DIR', RDR2_PORTAL_INC_DIR . 'views' . DS );
		if (!defined('RDR2_PARTIALS_DIR')) define( 'RDR2_PARTIALS_DIR', RDR2_INC_DIR . 'partials' . DS );
		if (!defined('RDR2_WIDGETS_DIR')) define( 'RDR2_WIDGETS_DIR', RDR2_INC_DIR . 'widgets' . DS );
        if (!defined('RDR2_LIB_DIR')) define( 'RDR2_LIB_DIR', RDR2_DIR . 'lib' . DS );
		if (!defined('RDR2_LANGUAGES_DIR')) define( 'RDR2_LANGUAGES_DIR', RDR2_DIR . 'languages' . DS );
		if (!defined('RDR2_PLUGIN_ASSETS')) define( 'RDR2_PLUGIN_ASSETS', plugins_url( 'assets', RDR2_FILE ) );
        if (!defined('RDR2_PLUGIN_ASSETS_DIR')) define( 'RDR2_PLUGIN_ASSETS_DIR', RDR2_DIR . 'assets' . DS );
		
        // Give a way to turn off loading styles and scripts from parent theme
        if (!defined('RDR2_LOAD_STYLE')) define('RDR2_LOAD_STYLE', true);
        if (!defined('RDR2_LOAD_SCRIPTS')) define('RDR2_LOAD_SCRIPTS', true);



    }



    /**
     * Load the plugin after WP User Frontend is loaded
     *
     * @return void
     */
    public function init_plugin() {
		
		// Includes
        $this->includes();
		
		// Initiate Classes
		//$this->init_classes();
		
		// Initiate Hooks
        $this->init_hooks();
		
	
		
		// Define variables

		$this->user = $this->get_user();
		//$this->user_id = get_current_user_id();
		$this->user_id = $this->user->ID;		
		$this->prefix = RDR2_PREFIX;
		$this->id = FALSE;
		$this->user_info = get_userdata($this->user_id);
		
		$this->orderby = FALSE;
		$this->order = FALSE;
		$this->page = FALSE;
		$per_page = $this->get_acf_option("per_page");
		$this->per_page = (!empty($per_page)) ? $per_page : 20;
		$this->hide_res = 1;
		$cache_stats = $this->get_acf_option("cache_stats" );
		$this->cache_stats = ($cache_stats !== FALSE) ? $cache_stats : 0;
		$this->data = FALSE;
		$this->merchant = FALSE;

		
		
		
		// Extract $_REQUEST Terms to Object Variables
		if (!$this->terms_extracted) 
			$this->extract_terms();
		
		// Do Loaded Hook Action
        do_action( 'rdr2_loaded');
		
    }



    /**
     * Include all the required files
     *
     * @return void
     */
    function includes() {

		require_once RDR2_CLASSES_DIR . 'class.core.php';
		require_once RDR2_CLASSES_DIR . 'class.rdr2.php';
		require_once RDR2_CLASSES_DIR . 'class.rdr2.search.php';
		require_once RDR2_CLASSES_DIR . 'class.lb-list-class.php';
		
		if (!is_admin() ) {
			require_once(ABSPATH . 'wp-admin/includes/screen.php');
	        require_once(ABSPATH . 'wp-admin/includes/class-wp-screen.php');
	        require_once(ABSPATH . 'wp-admin/includes/template.php');
	        $GLOBALS["hook_suffix"] = "";
		}
		
		
		// USER LOGGED IN
		if (is_user_logged_in()) {
	        
		}
		
		
		// ADMIN
        if (is_admin() ) {
			
			//require_once RDR2_ADMIN_INC_DIR . 'class.admin.php';
		}
		
		// NON-ADMIN 
        else {
			
        }

    }



    // Init Classes
    
    function init_classes() {
    	if (class_exists("RDR2"))
			$this->rdr2 = RDR2::init($this->terms);
		
		$this->player_id = $this->get_player_id_from_user_id($this->user_id);
		$this->game_id = $this->get_player_active_game();
    }
	


    /**
     * Initialize the actions
     *
     * @return void
     */
    function init_hooks() {
    	
		// AJAX Hooks
		add_action('wp_ajax__ajax_fetch_portal', array($this, '_ajax_fetch_portal_callback'));
		add_action('wp_ajax__ajax_fetch_player_collectables', array($this, '_ajax_fetch_player_collectables_callback'));
		add_action('wp_ajax__ajax_fetch_tips', array($this, '_ajax_fetch_tips_callback'));
		add_action('wp_ajax__ajax_update_player_game_collectables', array($this, '_ajax_update_player_game_collectables_callback'));
		add_action('wp_ajax__ajax_update_player_game_collectable', array($this, '_ajax_update_player_game_collectable_callback'));
		add_action('wp_ajax__ajax_insert_player_game_collectable', array($this, '_ajax_insert_player_game_collectable_callback'));
		
		//add_action('init', array($this, 'register_report_types'), 0 ); // Register Report Types Post Type
		add_action('init', array($this, 'wpdb_table_shortcuts' ), 1); // Custom Table Name Shortcuts
        add_action('init', array($this, 'localization_setup' )); // Localization Setups
     	add_action('init', array($this, 'init_classes'), 5); // Initialize classes
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_action_links' ) );
		add_action('wp_login', array($this, 'user_last_login'), 10, 2 );
		add_action('user_register', array($this, 'user_registration_save'), 10, 1); // Create new Player on User Registration
		add_action('admin_menu', array($this, 'stop_access_profile') );
		add_filter('login_redirect', array($this, 'player_login_redirect'), 10, 3 );
		add_action('wp_login', array($this, 'player_login'), 10, 2);
		
		
		// Shortcodes
		add_shortcode('player_portal', array($this, 'shortcode_player_portal')); // Player Portal
		add_shortcode('player_points_earned', array($this, 'shortcode_player_points_earned')); // Points Earned
		add_shortcode('player_since', array($this, 'shortcode_player_since')); // Player Since Year
		add_shortcode('player_name', array($this, 'shortcode_user_name')); // Player Name
		add_shortcode('author_last_login', array($this, 'shortcode_author_lastlogin')); // Author Last Login
		add_shortcode('last_login', array($this, 'shortcode_last_login')); // User Last Login
		
		
		add_action('admin_bar_menu', array($this, 'remove_wp_logo'), 999); // Remove "W" Wordpress Logo from Toolbar
		add_action('admin_bar_menu', array($this, 'toolbar_remove_comments_link'), 999); // Remove "Comments" Toolbar Link
		
		
        // Admin Actions
        if (is_admin()) {
        	
        	add_action("admin_init", array($this, 'do_actions')); // Do Actions before Redirect
			add_action("admin_menu", array($this, "admin_add_menus")); // Add Menus to Admin Sidebar
			add_action('wp_dashboard_setup', array($this, 'admin_add_dashboard_widgets') );
			add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts')); // Enqueue Admin Scripts
			add_action('admin_enqueue_scripts', array($this, 'my_deregister_heartbeat') );
			// Plugin Installer
			//add_action('in_plugin_update_message-rdr2/rdr2.php', array( 'ProgressTracker_Installer', 'in_plugin_update_message' ) );
		}
		
		// Frontend Actions
		else {
			add_action("template_redirect", array($this, 'do_actions')); // Do Actions before Redirect
			//add_action("template_redirect", array($this, 'dashboard_redirect')); // Dashboard Redirect
			add_filter('body_class', array($this, 'body_classes')); // Body Classes
			//add_action("init", array($this, "do_ajax")); // Do AJAX
			add_action('init', array($this, 'enqueue_scripts')); // Enqueue Frontend Scripts
			//add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 9999); // Enqueue Scripts
			
			// Re-Order Footer Scripts
			remove_action('wp_footer', 'wp_print_footer_scripts'); 
			add_action('wp_footer', 'wp_print_footer_scripts', 1);
			
			// Wordpress Frontend Taming
			add_action( 'enqueue_scripts', array($this, 'my_deregister_heartbeat') );
			//add_filter('wp_default_scripts', array($this, 'dequeue_jquery_migrate')); // Remove jQuery Migrate
			add_filter('style_loader_src', array($this, 'vc_remove_wp_ver_css_js'), 9999);
			add_filter('script_loader_src', array($this, 'vc_remove_wp_ver_css_js'), 9999);
			remove_action('wp_head', 'rel_canonical'); // Canonical links
			remove_action('wp_head', 'rsd_link'); // Weblog Client Link
			remove_action('wp_head', 'wlwmanifest_link'); // Windows Live Writer Manifest Link
			remove_action('wp_head', 'wp_shortlink_wp_head'); // Wordpress Post/Page Shortlink
			remove_action('wp_head', 'wp_generator'); // Wordpress Generator with Version info
			remove_action('wp_head', 'rest_output_link_wp_head'); // REST Json
			remove_action('wp_head', 'wp_oembed_add_discovery_links'); // OEMBED
			remove_action('template_redirect', 'rest_output_link_header', 11, 0); // REST Json
			remove_action('wp_head', 'feed_links', 2); // Feed links
			remove_action('wp_head', 'feed_links_extra', 3); // Extra feeds such as category feeds
			remove_action('wp_head', 'index_rel_link'); // Index Rel Link
			remove_action('wp_head', 'parent_post_rel_link'); // Parent Post Rel Link
			remove_action('wp_head', 'start_post_rel_link'); // Start Post Rel Link
			remove_action('wp_head', 'adjacent_posts_rel_link'); // Adjacent Posts Rel Link
			
		}

    }	
	

    /**
     * Plugin activation function
     *
     * Create user roles
     */
    public static function activate() {
        //require_once __DIR__ . '/includes/functions.php';
        //$installer = new ProgressTracker_Installer();
        //$installer->do_install();
		
		add_role( 
			'player', 
			'Player', 
			array(
				'read' => true,
				'edit_posts' => false,
			)
		);
		
		$player_role = get_role( 'player' );
		
		// Adding a new capability to role
		$player_role->add_cap( 'create_games' );
    }



    /**
     * Placeholder for Plugin deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate() {
		
    }



    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'rdr2', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
    }

	






	///////////////////////////////////////////////
	// ADMIN AREA

	// Menus
	
	function admin_add_menus() {
		
		
		// RDR2 CONTROL PANEL
		
		// Top Menu Item
		add_menu_page("Control", 'Control', 'manage_options', 'admin-control', array($this, 'admin_rdr2_control'), "dashicons-admin-tools", 3);
		
		// Remove duplicate menu hack 
		//add_submenu_page('admin-control', '', '',  'manage_options', 'admin-control');
		
		
		
		// PLAYERS / CARDS
		
		// Top Menu Item
		add_menu_page("Players", 'Players',  'manage_options', 'admin-players', array($this, 'admin_rdr2_players'), "dashicons-admin-users", 3);
		
		// Remove duplicate menu hack 
		//add_submenu_page('admin-players', '', '',  'manage_options', 'admin-players');
		
		// Statistics
		add_submenu_page('admin-players', 'Statistics', 'Statistics',  'manage_options', 'admin-player-statistics', array($this, 'admin_rdr2_player_statistics'));	


	}
	

	
	
	

	/////////////////////////////////////////
	// SHORTCODES

	
	// Points Earned
	
	public function shortcode_player_points_earned($atts = array()) {
		return 60;
	}
	

	// Player Since
	
	public function shortcode_player_since($atts = array()) {
		return 2015;
	}
	
	
	
	// Player Portal
	
	public function shortcode_player_portal($atts = array()) {
		ob_start(); 
		
		$this->print_player_portal();
		
		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
	}
	


	// User Name
	
	public function shortcode_user_name($atts = array()) {
		return $this->get_user_name();
	}



	/**
	 * Display last login time
	 *
	 */
	  
	function shortcode_author_lastlogin() { 
	    $last_login = get_the_author_meta('last_login');
	    $the_login_date = human_time_diff($last_login);
	    return $the_login_date; 
	} 
	
	
	
	// Shortcode - Get User Last Login
	
	function shortcode_last_login($atts = array()) {
		if (empty($atts) || !is_array($atts))
			return FALSE;
			
		$user_id = FALSE;
		 
		$id_pool = array("user_id", "user", "id", "ID", "WP_User_ID");
		
		foreach ($id_pool as $id_var) 
			if (isset($atts[$id_var]) && !empty($atts[$id_var]))
				$user_id = $atts[$id_var];
		
		return (empty($user_id) || !is_numeric($user_id)) ? FALSE : $this->get_user_last_login($user_id); 
	}
















	//////////////////////////////////////////////////
	// DO ACTIONS
	
	
	
	public function do_actions() {
		
		if (!isset($_REQUEST["action"]))
			return FALSE;
		
		$action = $_REQUEST["action"];
		
		switch ($action) {
			case "addcard" : 
				if ($this->user_add_card())
					$this->do_status_refresh("added", "Card");
				else 
					$this->do_status_refresh("error", "Card");
			break;
			case "create-merchant-account" : 
				if ($this->create_merchant_account($this->id))
					$this->do_status_refresh('created', 'Merchant Account', array('page' => $this->page, 'id' => $this->id));
				else 
					$this->do_status_refresh('error', 'Merchant Account', array('page' => $this->page, 'id' => $this->id));
			break;
			case "delete-merchant-account" : 
				if ($this->delete_merchant_account())
					$this->do_status_refresh("deleted", "Merchant Account", array("page" => $this->page, "id" => $this->id));
				else 
					$this->do_status_refresh("error", "No Merchant Portal Account User ID Found", array("page" => $this->page, "id" => $this->id));
			break;
			case "archive-inactive-merchant-transactions" : 
				if ($this->archive_inactive_merchant_transactions())
					$this->do_status_refresh("archived", "Inactive Merchant Transactions", array("page" => $this->page, "id" => $this->id));
				else 
					break;
			case "export-spreadsheet" : 
				//$this->make_data_export_spreadsheet();
				
				if ($this->make_data_export_spreadsheet())
					$this->do_status_refresh("success", "Spreadsheet successfully exported", array("page" => $this->page));
				else 
					$this->do_status_refresh("error", "Unable to save spreadsheet", array("page" => $this->page));
				 
			break;
			case "download-file" :
				$this->do_download_file();
			break;
			case "flush" :
				$this->flush_admin_statistics();
				$this->do_status_refresh("success", "Admin Statistics Cache Flushed", array("page" => $this->page));
			break;
		}
	}
	
	
	
	// Do Status Refresh
	
	function do_status_refresh($status = "updated", $message = FALSE, $query = array()) {		
		if (!empty($status))
			$query["status"] = $status;
		
		if (!empty($message))
			$query["message"] = $message;
		
		return $this->do_query_refresh($query);
	}
	
	
	// Do Action Refresh

	function do_action_refresh($action = "updated", $query = array()) {
		if (!empty($action))
			$query["action"] = $action;
		
		return $this->do_query_refresh($query);
	}
	
	
	
	// Do Query Refresh
	
	function do_query_refresh($query = array(), $url = FALSE) {
		$location = (!empty($url)) ? $url : $_SERVER['PHP_SELF'];
		
		if (!empty($query) && is_array($query))
			$location .= "?" . http_build_query($query);
		
		wp_redirect($location);
		exit;
	} 
	
	
	
	// Download File
	
	function do_download_file($filename = FALSE) {
		if (!$filename)
			$filename = $this->filename;
		
		if (empty($filename))
			return FALSE;
		
		$uploads_dir = $this->get_uploads_dir();
		$filepath = $uploads_dir . $filename; 
		
		// Check if file exists on server
		if (!file_exists($filepath))
			$this->do_status_refresh("error", "Unable to find file for downloading");
		
		$uploads_url = $this->get_uploads_url();
		$fileurl = $uploads_url . $filename;
		
		$this->do_query_refresh(FALSE, esc_url($fileurl));
	}
	
	
	// Delete List of Option Values
	
	function delete_option_values($list = array()) {
		if (empty($list) || !is_array($list) || sizeof($list) < 1)
			return FALSE;
		
		foreach ($list as $option) {
			$this->delete_option_value($option);
		}
		
		return TRUE; 
	}
	
	
	
	// Delete Option Value
	
	function delete_option_value($option = FALSE) {
		if ($option === FALSE || empty($option))
			return FALSE;
		
		$prefix = $this->prefix;
			
		return delete_option($prefix . $option);
	}
	

	
	
	// Player Login redirect
	
	function player_login_redirect($redirect_to, $request, $user) {
	    return (isset($user->roles) && is_array($user->roles) && in_array('player', $user->roles)) ? home_url() : $redirect_to;
	}




	
	
	
	// Dashboard Redirect

	public function dashboard_redirect() {
		global $pagename;
			
		if (!(is_page() && $pagename == "dashboard" && is_user_logged_in() == TRUE))
			return FALSE;
		
		$page_redirect = FALSE;
		
		if ($this->get_user_has_role("player") == TRUE)
			$page_redirect = "/dashboard/player/";
		elseif ($this->get_user_has_role("merchant") == TRUE)
			$page_redirect = "/dashboard/merchant/";
		
		if ($page_redirect == FALSE)
			return FALSE;
		
		wp_redirect($page_redirect);
		exit;

	}

	
	
	// On New Player Registration Save...
	
	function user_registration_save($user_id) {
		if (!$user_id) 
			return FALSE;
		
		if (!$this->get_user_has_role("player", $user_id)) {
			echo "Cannot get user role for " . $user_id;
			print_r($this->get_user_has_role("player", $user_id));
			die();
			return FALSE;
		}
		
		// Always hide Admin Bar for Players (fallback to the global hiding)
		update_user_meta( $user_id, 'show_admin_bar_front', false );
		
	    // Create Player ID to match
		$this->rdr2->create_new_player($user_id);	
	}



	// Player Login
	
	function player_login($user_login, $user) {
		if (!(isset($user->roles) && is_array($user->roles) && in_array('player', $user->roles)))
			return FALSE;
		
		$this->player_id = $this->get_player_id_from_user_id($user->ID);
		
		if (!$this->rdr2->player_has_games($this->player_id))
			// Create their first game playthrough for them
			$this->rdr2->create_player_game($this->player_id);
		
		$this->game_id = $this->rdr2->get_player_active_game($this->player_id);
	}
	
	
	// Stop Players from accessing their profile
	
	function stop_access_profile() {
		
		if (!$this->get_user_has_role("player"))
			return;
		
		remove_menu_page('profile.php');
		remove_submenu_page('users.php', 'profile.php');
		
		if (defined('IS_PROFILE_PAGE') && IS_PROFILE_PAGE === true) {
			wp_die( 'You are not permitted to change your own profile information.');
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////
	// SETTERS
	
	
	
    // WPDB Table Shortcuts
    
    function wpdb_table_shortcuts() {
        global $wpdb;
		
        $wpdb->collections = 'rdr2_collections';
		$wpdb->collection_collectables = 'rdr2_collection_collectables';
        $wpdb->collection_groups = 'rdr2_collection_groups';
        $wpdb->collection_group_atts = 'rdr2_collection_group_atts';
		$wpdb->collection_item_collectables = 'rdr2_collection_item_collectables';
		$wpdb->collection_items = 'rdr2_collection_items';
		$wpdb->collection_item_atts = 'rdr2_collection_item_atts';
		$wpdb->collection_item_tips = 'rdr2_collection_item_tips';
		$wpdb->collection_locations = 'rdr2_collection_locations';
		
		$wpdb->ingredients = 'rdr2_ingredients';
		$wpdb->ingredient_qualities = 'rdr2_ingredient_qualities';
		$wpdb->ingredient_parts = 'rdr2_ingredient_parts';
		$wpdb->ingredient_types = 'rdr2_ingredient_types';

        $wpdb->players = 'rdr2_players';
		$wpdb->player_games = 'rdr2_player_games';
		$wpdb->player_game_items = 'rdr2_player_game_items';
		$wpdb->player_game_collectables = 'rdr2_player_game_collectables';

		$wpdb->tips = 'rdr2_tips';
		
    }



	// Set Option
	
	public function set_option($key = FALSE, $value = FALSE, $prefix = FALSE) {
		if (!$key)
			return FALSE;
			
		if ($prefix == FALSE && isset($this->prefix))
			$prefix = $this->prefix;
		
		$update = update_option($prefix . $key, $value);
		
		return $update;
	}
	
	
	
	// Set User Last Login
	
	function user_last_login( $user_login, $user ) {
		update_user_meta( $user->ID, 'last_login', time() );
	}
	
	

	// Player Game Collectables
	
	// AJAX - Update Player Game Collectables Response

	public function _ajax_update_player_game_collectables_callback() {
		
		if (!$this->collectables)
			die();
			
		$result = $this->rdr2->update_player_game_collectables($this->game_id, $this->collectables, $this->collected);
		
		//print_r($result);
		
		echo json_encode($result);
		
		//echo ($result == FALSE) ? 0 : 1;
		
		die();
	}
		
		
	public function _ajax_update_player_game_collectable_callback() { 
		if (!$this->collectable || !is_numeric($this->quantity)) 
			die();
		
		echo $result = $this->rdr2->update_player_game_collectable($this->game_id, $this->collectable, $this->quantity);
		
		die();
	}		
		
		
		
	public function _ajax_insert_player_game_collectable_callback() {
		if (!$this->collectable || !is_numeric($this->quantity)) 
			die();
			
		echo $result = $this->rdr2->insert_player_game_collectable($this->game_id, $this->collectable, $this->quantity);
		
		die();
	}				
		
		
		
		
		
		
		
		
		
		
		
		
	


	////////////////////////////////////////////////////////////////////////////////////////////////////
	// GETTERS
	
	
	// Get Cached Data from Saved Options or RDR2 Class
	
	function get_rdr2_data($option_name = FALSE, $function_name = FALSE, $args = FALSE) {
		if ($option_name == FALSE)
			return FALSE;
		
		$data = FALSE;
		
		// See if Data is saved as an Option
		if ($this->cache_stats == 1) 
			$data = $this->get_option($option_name);
		
		// If no data found and RDR2 Method exists
		if (empty($data) && method_exists($this->rdr2, $function_name))
			// Get Data from RDR2 Class Method
			$data = $this->rdr2->$function_name($args);
		
		// If still no data return false
		if (empty($data))
			return FALSE;
		
		// Set Option with new RDR2 Data
		if ($this->cache_stats == 1)
			$this->set_option($option_name, $data);
		
		// Return Data
		return $data;
	}
	
	






	// PLAYERS

	
	// Get Player ID from User ID 
	function get_player_id_from_user_id($user_id = FALSE) {
		if (!$user_id) 
			$user_id = $this->user_id;
		
		if (!$user_id)
			return FALSE;
		
		return $this->player_id = $this->rdr2->get_player_id( $user_id );
	}
	
	
	// Get Active Player ID
	function get_active_player_id() {
		return ($this->player_id) ? $this->player_id : $this->player_id = $this->get_player_id_from_user_id();		
	}
	
	
	// Get Player Active Game
	function get_player_active_game($player_id = FALSE) {
		if ($this->game_id)
			return $this->game_id;
		
		if (!$player_id) 
			$player_id = $this->get_active_player_id();
		
		return $this->game_id = $this->rdr2->get_player_active_game($player_id);
	}
	
	
	// Get Player
	function get_players($args = array()) {
		$current_page = max( 1, (isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 0 ) );
		$offset = ($current_page == 1) ? 0 : (($current_page - 1) * $this->per_page) + 1;
		
		$search = new RDR2_Search();
		$search->args = $args;
		$search->new_search = TRUE;
		$search->count_limit = $offset . ", " . $this->per_page;
		$search->results_per_page = FALSE;
		$search->limit = $this->per_page;
		$search->orderby = (!empty($this->orderby)) ? $this->orderby : "PointsBalance";
		$search->order = (!empty($this->order)) ? $this->order : "DESC";
		$search->clean = (isset($_REQUEST["clean"])) ? $_REQUEST["clean"] : 0;
		$search->custom_order = "MemberNumber " . $search->order;
		$search->block = $current_page;
		$results = $search->get_search_results();
		
		if (!is_array($results) || empty($results))
			return false;

		// Format Data
		
		foreach ($results as $i => $result) {
			
			// Location
			$location = "";
			
			if (!empty($result["Suburb"]))
				$location .= $result["Suburb"];
			if (!empty($result["Suburb"]) && !empty($result["State"]))
				$location .= ", ";
			if (!empty($result["State"]))
				$location .= $result["State"];
			
			$results[$i]["Location"] = $location;
			
			// Date Created
			if (!empty($result["DateCreated"]))
				$result["DateCreated"] = $result["DateCreated"];
			
			// Date of Last Transaction
			if (!empty($result["DateOfLastTransaction"]))
				$result["DateOfLastTransaction"] = $result["DateOfLastTransaction"];
			
			$player = array("Name" => $result["Name"], "FirstName" => $result["FirstName"], "Surname" => $result["Surname"]);
			$results[$i]["Name"] = $this->rdr2->make_player_name($player);
			
			//print_r($result);
		}
		
		return $results;
	}
	
	
	
	// Get Total Player Count
	
	function get_player_total() {
		$clean = (isset($this->clean)) ? $this->clean : 0;
		$option_name = ($clean == 1) ? "player_total_clean" : "player_total";
		$player_total = $this->get_rdr2_data($option_name, "get_player_total", array("clean" => $clean));
		return $player_total;
	}
	
	
	
	
	
	

	
	
	








	// WORDPRESS GETTERS

	
	// Get Option
	
	public function get_option($key = FALSE, $prefix = FALSE) {
		if ($key == FALSE)
			return FALSE;
		
		if ($prefix == FALSE && isset($this->prefix))
			$prefix = $this->prefix;
		
		return get_option($prefix . $key);
	}
	
	
	// Get User
	public function get_user($user_id = FALSE) {
		return wp_get_current_user();
	}
	
	
	
	// Get User Name
	public function get_user_name() {
		return (!empty($this->get_user())) ? $this->user->display_name : FALSE;
	}
	
	
	
	// Get User Email
	public function get_user_email() {
		return (!empty($this->get_user())) ? $this->user->user_email : FALSE;
	} 
	
	
	// Get User Roles
	
	public function get_user_roles($user_id = FALSE) {		
		if ($user_id !== FALSE)
			$this->user_info = get_userdata($user_id);
		
		return (!empty($this->user_info->roles)) ? $this->user_info->roles : FALSE;
	}
	
	
	// Get If User Has Role
	public function get_user_has_role($role = FALSE, $user_id = FALSE) {
		if ($role == FALSE || empty($role)) 
			return FALSE;
		
		$user_roles = $this->get_user_roles($user_id);
		
		return (empty($user_roles) || !is_array($user_roles)) ? FALSE : in_array($role, $user_roles);
	}
	

	
	// Get User's Last Login
	public function get_user_last_login($user_id = FALSE) {		
		$last_login = ($user_id) ? get_user_meta($user_id, 'last_login') : FALSE;
		return (!empty($last_login[0])) ? human_time_diff($last_login[0]) : "Never";
	} 
	
	
		
	// Get Page Slug
	function get_page_slug_from_id($id = FALSE) {		
		return ($id !== FALSE) ? basename(get_permalink($id)) : FALSE;
	}


	// Get any Child Pages
	
	public function get_child_pages($id = FALSE) {
		
		//if ($this->child_pages)
		//	return $this->child_pages;
		
		if (!$id) {
			global $post;
			
			if ($post)
				$id = $post->ID;
		}
	
		// WP_Query arguments
		$args = array (
			'post_parent'            => $id,
			'post_type'              => array( 'page' ),
			'post_status'            => array( 'publish' ),
			'nopaging'               => true,
			'posts_per_page'         => '-1',
			'orderby'				=> 'menu_order',
			'order'					=> 'ASC',
		);
		
		// The Query
		return $this->child_pages = new WP_Query( $args );
		
	}	


	// Get Sibling Pages
	
	public function get_sibling_pages($id = FALSE) {
		// Get Parent
		global $post;
		
		if (!isset($post->post_parent) || $post->post_parent == 0)
			return FALSE;
		else 
			$parent_id = $post->post_parent;
		
		// Get Parent's Children
		if (!($siblings = $this->get_child_pages($parent_id)))
			return FALSE;
		
		return $siblings;
		
	}
	
	
	
	// Get Uploads Dir
	
	function get_uploads_dir() {
		$wp_upload_dir = wp_upload_dir();
		return $wp_upload_dir["basedir"] . DS;
	}



	// Get Uploads URL
	
	function get_uploads_url() {
		$wp_upload_dir = wp_upload_dir();
		return $wp_upload_dir["baseurl"] . DS;
	}


    /**
     * Magic getter to bypass referencing objects
     *
     * @since 1.0.0
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};	
    }
	
	
	
	// Get option value from ACF Custom Options Admin Page
	
	public function get_acf_option($key = FALSE) {
		if ($key == FALSE || !function_exists("get_field"))
			return FALSE;
		
		return get_field($key, 'option');
	}



    /**
     * Get the plugin path.
     *
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }
	
	

    /**
     * Get the template path.
     *
     * @return string
     */
    public function template_path() {
        return apply_filters( 'rdr2_template_path', 'rdr2/');
    }	

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//////////////////////////////////////////////////
	// PRINTERS
	
	
	// Print Player Dashboard
	
	public function print_player_portal() {
		$player_id = $this->get_active_player_id();
		$game_id = $this->get_player_active_game($player_id);
		
		// If first game and no items added, show the new player message
		if ($this->rdr2->get_player_games_count($player_id) == 1 && $this->rdr2->get_player_progress_count($game_id) == 0)
			$this->print_player_portal_first_game_message();		
		?>
		<div class="DynamicContainer">
			<h3>Game</h3>
			<form id="player-portal-filter" method="get" class="">
				<input type="hidden" name="ajaxaction" id="ajaxaction" value="_ajax_fetch_portal" />
				<?php wp_nonce_field( 'ajax-portal-nonce', '_ajax_portal_nonce' ); ?>
				<?php wp_nonce_field( 'ajax-player-nonce', '_ajax_player_nonce' ); ?>
				<?php wp_nonce_field( 'ajax-tip-nonce', '_ajax_tip_nonce' ); ?>
				<?php $this->print_player_game_controller($game_id); ?>
			</form>
		</div>
		<form class="DeadForm" id="items-filter" method="get" ><input type="hidden" name="game_id" id="game_id" value="<?php echo $game_id; ?>" /><?php wp_nonce_field( 'ajax-items-nonce', '_ajax_update_items_nonce' ); ?><div id="PlayerItemsPortal" class="DynamicContainer PlayerPortal"></div></form>
		<form class="DeadForm" id="collectables-filter" method="get" ><input type="hidden" name="game_id" id="game_id" value="<?php echo $game_id; ?>" /><?php wp_nonce_field( 'ajax-collectables-nonce', '_ajax_update_collectables_nonce' ); ?><div id="PlayerGamesPortal" class="DynamicContainer PlayerPortal"></div></form>
		<div id="RandomTip" class="DynamicContainer"></div>
		<?php
	}
	
	
	// AJAX - Player Portal Response

	public function _ajax_fetch_portal_callback() {
		
		echo json_encode($this->rdr2->get_portal_data());
		
		die();
	}


	// AJAX - Player Portal Response

	public function _ajax_fetch_tips_callback() {
		
		echo json_encode($this->rdr2->get_tips());
		
		die();
	}

	
	// AJAX - Player Ingredients Response

	public function _ajax_fetch_player_collectables_callback() {
		
		echo json_encode($this->rdr2->get_player_game_collectables($this->game_id));
		
		die();
	}
	
	
	
	// Print Portal Game Controller
	
	private function print_player_game_controller($game_id = FALSE) {
		$game_id = ($game_id !== FALSE) ? $game_id : $this->get_player_active_game();
		$game_info = $this->rdr2->get_player_game_info($game_id);
	?>
	<input type="hidden" name="game_id" id="game_id" value="<?php echo $game_id; ?>" />
	<div id="PlayerGameController">
		<div>
			<label for="GameNumber">Playthrough number:</label> <input type="number" name="game_number" id="GameNumber" value="<?php echo $game_info["Number"]; ?>" min="1" max="255">
		</div>
		<div>
			<label for="GameName">Name:</label> <input type="text" name="game_name" id="GameName" placeholder="Give your playthrough a handy nickname"> 
		</div>
		<div>
			<label for="GameDescription">Description:</label><textarea name="game_description" id="GameDescription"  placeholder="Give your playthrough a description if needed"></textarea> 	
		</div>
		<input type="submit" value="Save" id="SubmitGame" />
	</div>
	<?php
	} 

	
	
	
	// Print Player Add First Game
	
	public function print_player_portal_first_game_message() { 
	?>
		<h2>Welcome, pardner!</h2><p>We have created your first game playthrough, so start collecting!</p></h3>
	<?php
		
	}
	
	
	
	// Print Game Edit
	
	
	
	
	
	
	// ADMIN
	
	
	
	// Print Admin Header
	
	function print_admin_header($title = FALSE, $buttons = FALSE) {
		?>
	
	<div class="admin-header">
		<?php if (!empty($title)) : ?><h1><?php echo $title; ?></h1><?php endif; ?><span class="buttons"><?php $this->print_back_button(); ?><?php if (!empty($buttons)) : ?><?php echo $buttons; ?><?php endif; ?></span>
	</div>
	
		<?php
	}


	// Print Admin Page
	
	function print_admin_page($slug = FALSE, $title = FALSE, $function = FALSE, $message = FALSE, $buttons = FALSE) {			
		
		?>
<div class="wrap rdr2-admin <?php echo $slug; ?>">
	<?php $this->print_admin_header($title, $buttons); ?>
	<?php if (!empty($message)) : ?>
	<div id="message" class="updated fade">
		<p><?php echo $message; ?></p>
	</div>
	<?php endif; ?>
	<?php 
	if (!empty($function)) :
		if (is_array($function)) : 
			$ref = $function[0];
			$func = $function[1]; 
			$ref->$func(); 
		else :
			$function();
		endif; 	
	endif; ?>
</div>
	<?php
	}



	
	






	
	
	// Admin Form	
	
	function print_back_button() {
		
		?>
		<a href="javascript:history.go(-1);" class="btn button"><span class="dashicons dashicons-arrow-left-alt2"></span> Back</a>
		<?php
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	////////////////////////
	// ADMIN 
	
	
	

	// Admin Add Dashboard Widgets

	function admin_add_dashboard_widgets() {
		
		$widgets = array(
			array('main_menu_dashboard_widget', 'ProgressTracker Main Menu', array($this, 'admin_main_menu_dashboard_widget')),
			//array('transactions_dashboard_widget', 'ProgressTracker Transactions', array($this, 'admin_transactions_dashboard_widget')),
			//array('players_dashboard_widget', 'ProgressTracker Players', array($this, 'admin_players_dashboard_widget')),
			//array('merchants_dashboard_widget', 'ProgressTracker Merchants', array($this, 'admin_merchants_dashboard_widget')),
			
		);
		
		if (!isset($widgets) || empty($widgets) || !is_array($widgets))
			return FALSE;
		
		foreach ($widgets as $i => $widget)
			wp_add_dashboard_widget($widget[0], $widget[1], $widget[2]);
		
	}
	
	
	
	// Admin Main Menu Dashboard Widget
	
	function admin_main_menu_dashboard_widget() {
		//$menu = $this->menus["Main"]; 
		
		$args = array(
			'menu' => 'admin-main-menu', 
			'menu_class' => 'accordion-menu', 
		); 
		
		wp_nav_menu($args);
	}
	
	
	
	// Admin Players Dashboard Widget
	
	function admin_players_dashboard_widget() {
		$this->print_player_statistics();
	}



	// Admin Merchants Dashboard Widget
	
	function admin_merchants_dashboard_widget() {
		$this->print_admin_merchant_statistics();
	}



	// Admin Merchants Dashboard Widget
	
	function admin_transactions_dashboard_widget() {
		$this->print_admin_transactions_statistics();
	}
		
	
	
	// Admin Lucky Buys Control Panel
	
	function admin_rdr2_control() {
		include_once(RDR2_ADMIN_VIEWS_DIR . 'admin-control.php');
	}



	// Admin ProgressTracker Players Control Panel
	
	function admin_rdr2_players() {
		$id = (isset($this->id)) ? $this->id : FALSE;
		$player = FALSE;
		$message = (isset($_REQUEST['saved'])) ? get_bloginfo('name') . ' options successfully saved.' : FALSE;
		$buttons = "<a href=' . " . $_SERVER['PHP_SELF'] . "?page=admin-players' class='btn button'><span class='dashicons dashicons-arrow-up-alt2'></span> Players</a>";
		
		if ($id !== FALSE) {		
			$player = (!empty($this->player)) ? $this->player : $this->player = $this->rdr2->get_player(array("card_number" => $id));	
			$title = "Card " . $id;
			
			if (!empty($player) && is_array($player)) {
				$player_name = $this->rdr2->make_player_name($player);
				$title .= " - " . $player_name;
			}
			$this->print_admin_page("players", $title, array($this, 'print_admin_player'), $message, $buttons); 
		} 
		
		else {
			$this->print_admin_page("players", "Cards", array($this, 'print_admin_players'), $message);
		}
		
	}
	
	

	// Admin ProgressTracker Player Statistics Panel
	
	function admin_rdr2_player_statistics() {
		$message = (isset($_REQUEST['saved'])) ? get_bloginfo('name') . ' options successfully saved.' : FALSE;
		$this->print_admin_page("player-statistics", "Player Statistics", array($this, 'print_player_statistics'), $message);
	}



	// Admin ProgressTracker Merchants Control Panel
	
	function admin_rdr2_merchants() {
		$id = (isset($this->id)) ? $this->id : FALSE;
		
		$buttons = '<a href="?page=admin-merchants" class="btn button"><span class="dashicons dashicons-arrow-up-alt2"></span> Merchants</a>';
		
		if (!isset($message))
			$message = (isset($_REQUEST['saved'])) ? get_bloginfo('name') . ' options successfully saved.' : FALSE;
		
		// Single Merchant
		if (!empty($id)) {
			$merchant = (!empty($this->merchant)) ? $this->merchant : $this->merchant = $this->rdr2->get_merchant(array("site_number" => $id));	
			$title = 'Merchant ' . $id;
			$title .= (!empty($merchant) && is_array($merchant) && isset($merchant["Name"])) ? " - " . $merchant["Name"] : FALSE;
			$this->print_admin_page("merchant", $title, array($this, 'print_admin_merchant'), $message, $buttons);
		}
		
		// All Merchants
		else
			$this->print_admin_page("merchants", "Merchants", array($this, 'print_admin_merchants'), $message);
	}
	
	
	




	// AJAX - Merchant Table List Response

	public function _ajax_fetch_merchants_callback() {
		$GLOBALS["hook_suffix"] = "";
		$merchant_list_table = $this->get_merchant_list_table();
	    $merchant_list_table->ajax_response();
	}
	
	
	
	// Merchant Player Table List Ajax Response

	public function _ajax_fetch_merchant_players_callback() {
		$id = $this->id;
		$merchant_players = $this->get_merchant_players($id);
		$total = $merchant_players["result_count"];
		$GLOBALS["hook_suffix"] = "";
		$player_list_table = new Player_List_Table($merchant_players["results"], $total, $this->per_page);
	    $player_list_table->ajax_response();
	}



	// Admin ProgressTracker Merchants Stores Page
	
	function admin_rdr2_merchant_stores() {
		$id = (isset($this->id)) ? $this->id : FALSE;
		$message = (isset($_REQUEST['saved'])) ? get_bloginfo('name') . ' options successfully saved.' : FALSE;
		
		// Single Merchant Store
		if ($id !== FALSE) 
			$this->print_admin_page("merchant-stores", "Merchant Stores", array($this, 'print_admin_merchant_store'), $message); 
		
		// All Merchant Stores
		else 
			$this->print_admin_page("merchant-stores", "Merchant Stores", array($this, 'print_admin_merchant_stores'), $message);			
		
	}



	// Merchant Stores Table List Ajax Response

	public function _ajax_fetch_merchant_stores_callback() {
		$id = $this->id;
		$orderby = (isset($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : "MerchantID";
		$order = (isset($_REQUEST['order'])) ? $_REQUEST['order'] : "ASC";
		$hide_res = (isset($_REQUEST["hide_res"])) ? $_REQUEST["hide_res"] : 1;
		$this->rdr2->per_page = $this->per_page;
		$merchant_stores = $this->rdr2->get_merchant_stores(array("id" => $id, "hide_res" => $hide_res));
		$GLOBALS["hook_suffix"] = "";
		$merchant_stores_table = new Merchant_Stores_List_Table($merchant_stores["results"], $merchant_stores["result_count"], $this->per_page);
	    $merchant_stores_table->ajax_response();
	}



	// Admin ProgressTracker Merchants Groups Panel
	
	function admin_rdr2_merchant_groups() {
		/*		
		$merchants_total = $this->get_merchant_total();
		$merchants_status_act = $this->get_merchants_status_act();
		$merchants_status_res = $this->get_merchants_status_res();
				
		$stats_array = array(
			"Merchants Total" => $merchants_total,
			"Merchants Status ACT" => $merchants_status_act,
			"Merchants Status RES" => $merchants_status_res,
		);
		*/
		
		$merchant_groups = $this->rdr2->get_merchant_groups();
			
		include_once(RDR2_ADMIN_VIEWS_DIR . 'admin-merchants-groups.php');
	}



	// Admin ProgressTracker Merchants Statistics Panel
	
	function admin_rdr2_merchant_statistics() {
		$message = (isset($_REQUEST['saved'])) ? get_bloginfo('name') . ' options successfully saved.' : FALSE;	
		$this->print_admin_page("merchant-statistics", "Merchant Statistics", array($this, 'print_admin_merchant_statistics'), $message);
	}



	// Admin Lucky Buys Transactions Page
	
	function admin_rdr2_transactions() {
		$message = (isset($_REQUEST['saved'])) ? get_bloginfo('name') . ' options successfully saved.' : FALSE;
		$this->print_admin_page("transactions", "Transactions", array($this, 'print_admin_transactions'), $message);
	}	


	// Admin ProgressTracker Merchants Statistics Page
	
	function admin_rdr2_transactions_statistics() {
		$message = (isset($_REQUEST['saved'])) ? get_bloginfo('name') . ' options successfully saved.' : FALSE;	
		$this->print_admin_page("transactions-statistics", "Transactions Statistics", array($this, 'print_admin_transactions_statistics'), $message);
	}


	// Admin Lucky Buys Reports
	
	function admin_rdr2_reports() {
		$message = (isset($_REQUEST['saved'])) ? get_bloginfo('name') . ' options successfully saved.' : FALSE;
		
		include_once(RDR2_ADMIN_VIEWS_DIR . 'admin-reports.php');
	}
	
	
	





	















	////////////////////////////////////////////////
	// MAKERS


	// Make Player Name
	
	function make_player_name($player = array()) {
		if (empty($player) || !is_array($player))
			return FALSE;
		
		if (!isset($player["FirstName"]) && !isset($player["Surname"]) && !isset($player["Name"]))
			return FALSE;
		
		$name = FALSE;
		
		if (isset($player["Name"])) 
			$name = $player["Name"];
		
		else {
			if (isset($player["FirstName"])) 
				$name = $player["FirstName"];
			if (isset($player["Surname"]))
				$name .= " " . $player["Surname"];
		}
		
		return $name;
	}
	
	
	
	// Make Merchant Names 
	
	function make_merchant_names($merchant_name = FALSE, $primary_contact = FALSE) {
		if ($merchant_name == FALSE && $primary_contact == FALSE)
			return FALSE;
		
		$names = array();
		$names["first"] = FALSE;
		$names["last"] = FALSE;
		/*
		if (!empty($primary_contact)) {
			$prefixes = ["Mr ", "Ms ", "Mrs ", "Miss ", "Dr "];
			$clean_name = str_replace($prefixes, "", $primary_contact); // Remove useless name prefixes
			$clean_name = trim(preg_replace('/\s*\([^)]*\)/', '', $clean_name)); // Remove anything in brackets and trim whitespace
			
			if (strlen($clean_name) > 1) { 
				if (strpos($clean_name, " ")) {  // eg. John[space]Smith
					$arr = explode(' ', $clean_name);
					$names["first"] = $arr[0];  // eg. John
					unset($arr[0]);
					$names["last"] = implode(" ", $arr);  // eg. Smith
				}
				else 
					$names["first"] = $clean_name; // eg. "Jo"
			}
		}*/
		
		if (empty($names["first"]) && !empty($merchant_name)) {
			$names["first"] = $merchant_name;  // eg. John's Garage
		}
		
		return $names; 
	}
	
	
	
	// Make Full Card Number
	
	function make_full_card_number($card_number = FALSE) {
		if ($card_number == FALSE)
			return FALSE;
		
		$prefix = (isset($this->card_number_prefix)) ? $this->card_number_prefix : "601690";
		
		$card_number = (substr($card_number, 0, strlen($prefix)) === $prefix) ? $card_number : $prefix . $card_number; 
			
		return $card_number;
		
	}
	


	// Make Partial Card Number
	
	function make_partial_card_number($card_number = FALSE) {
		if ($card_number == FALSE)
			return FALSE;
		
		$prefix = (isset($this->card_number_prefix)) ? $this->card_number_prefix : "601690";
		
		$card_number = (substr($card_number, 0, strlen($prefix)) === $prefix) ? substr($card_number, strlen($prefix)) : $card_number; 
			
		return $card_number;
	}
	
	
	
	// Make Data Export Spreadsheet
	
	function make_data_export_spreadsheet($data = array(), $filename = FALSE) {
		//if (!empty($data) || !is_array($data))
		//	return FALSE;
		
		$use_cache = FALSE;
		$max_xlsx = 20000;
		$max = 26000;

		$uploads_dir = $this->get_uploads_dir();
		
		// If no filename passed
		if (!$filename) {
				
			// Create default filename (no extension yet)
			$filename = $this->page . (($this->data) ? '-' . $this->data : '');
		}
		
		//$filepath = $uploads_dir . $filename;
		
		// If filename exists already on server
		//if (file_exists($filepath))
		
			// Download existing generated file
		//	$this->do_action_refresh("download-file", array("filename" => $filename, "page" => $this->page));
		
		
		// Otherwise get fresh data  
		if (!empty($this->data)) {
			switch ($this->data) {
				case "merchant-players" :
					$id = (isset($this->id)) ? $this->id : FALSE;
					
					if (!$id) break;
					
					// Merchant Players
					$merchant_players = $this->get_merchant_players($id);
					$total = $merchant_players["result_count"];
					$file_ext = ($total > $max_xlsx) ? ".csv" : ".xlsx";
					$filename = (($this->page) ? $this->page . "-" : '') . $id . (($this->data) ? '-' . $this->data : '') . '-' . date('Ymd') . $file_ext;
					$filepath = $uploads_dir . $filename;
					
					// If filename exists already on server
					if (file_exists($filepath))
					
						// Download existing generated file
						$this->do_action_refresh("download-file", array("filename" => $filename, "page" => $this->page));
					
					// Chunk large data ?

					$this->rdr2->per_page = ($total > $max) ? $max : $total;
					$this->rdr2->paged = 1;
					$results = $this->rdr2->get_players(array("clean" => 1));
					$data = (!empty($results) && isset($results["results"])) ? $results["results"] : array();
				break;	
			}
		} elseif (!empty($this->page)) {
			switch ($this->page) {
				case "rdr2-players" :
					$order = (isset($this->order)) ? $this->order : "DESC";
					$orderby = (isset($this->orderby)) ? $this->orderby : "PointsBalance";
					$total = $this->rdr2->get_player_total(array("clean" => 1));
					$file_ext = ($total > $max_xlsx) ? ".csv" : ".xlsx";
					$filename = (($this->page) ? $this->page . "-" : '') . (($this->data) ? '-' . $this->data : '') . '-' . date('Ymd') . $file_ext;
					$filepath = $uploads_dir . $filename;
					
					// If filename exists already on server
					if (file_exists($filepath))
					
						// Download existing generated file
						$this->do_action_refresh("download-file", array("filename" => $filename, "page" => $this->page));
					
					// Chunk large data ?

					$this->rdr2->per_page = ($total > $max) ? $max : $total;
					$this->rdr2->paged = 1;
					$results = $this->rdr2->get_players(array("clean" => 1));
					$data = (!empty($results) && isset($results["results"])) ? $results["results"] : array();
				break;	

			}
		}
		
		if (empty($data) || !is_array($data))
			return FALSE;
		
		//print_r($data);
		//exit();
		if ($use_cache) {
			$pool = new \Cache\Adapter\Apcu\ApcuCachePool();
			$simpleCache = new \Cache\Bridge\SimpleCache\SimpleCacheBridge($pool);
			\PhpOffice\PhpSpreadsheet\Settings::setCache($simpleCache);
		}
		// Create a new Spreadsheet Object
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		
		// Set Spreadsheet Properties
		$spreadsheet->getProperties()
			->setCreator("ProgressTracker")
			->setLastModifiedBy("ProgressTracker")
			->setTitle("ProgressTracker Players Export")
			->setSubject("ProgressTracker Players Export")
			->setDescription("ProgressTracker Players Export")
			->setKeywords("luckbuys rewards players export xlsx theloyaltygroup")
			->setCategory("ProgressTracker Data Export");
		
		// Import data to Spreadsheet
		$spreadsheet->getActiveSheet()->fromArray($data, NULL, 'A1');
		
		if ($total > $max_xlsx) {
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
			$writer->save($filepath);
		}
		else {
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
			$writer->save($filepath);
		}
		
		// Download Spreadsheet
		$this->do_action_refresh("download-file", array("filename" => $filename, "page" => $this->page));
	}



	


	
	///////////////////////////////////////////////////////
	// WORDPRESS TAMING FUNCTIONS


	// Remove Wordpress Logo
	
	function remove_wp_logo( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'wp-logo');
	}
	
	
	
	// Remove Comments Link from Toolbar for Editors
	
	function toolbar_remove_comments_link ( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( "comments" );
	}
	
	
	
	// Disable WP Emojicons
	
	public function disable_wp_emojicons() {
		// All actions related to emojis
		remove_action('admin_print_styles', 'print_emoji_styles');
		remove_action('wp_head', 'print_emoji_detection_script', 7 );
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
		remove_filter('the_content_feed', 'wp_staticize_emoji');
		remove_filter('comment_text_rss', 'wp_staticize_emoji');
	
		// Filter to remove TinyMCE emojis
		add_filter('tiny_mce_plugins', array($this, 'disable_emojicons_tinymce'));
	}	

	
	
	// Remove WP version param from enqueued scripts
	
	function vc_remove_wp_ver_css_js( $src ) {
	    if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) )
	        $src = remove_query_arg( 'ver', $src );
	    return $src;
	}
	
	
	// Remove Menus 
	
	public function remove_menus(){
		// Remove the following menu items from Admin Sidebar
		//remove_menu_page( 'index.php');                  //Dashboard
		//remove_menu_page( 'jetpack');                    //Jetpack* 
		//remove_menu_page( 'edit.php');                   //Posts
		//remove_menu_page( 'upload.php');                 //Media
		//remove_menu_page( 'edit.php?post_type=page');    //Pages
		remove_menu_page( 'edit-comments.php');          //Comments
		//remove_menu_page( 'themes.php');                 //Appearance
		//remove_menu_page( 'plugins.php');                //Plugins
		//remove_menu_page( 'users.php');                  //Users
		//remove_menu_page( 'tools.php');                  //Tools
		//remove_menu_page( 'options-general.php');        //Settings  
	}


	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since ProgressTracker 1.0
	 *
	 * @param array $classes Classes for the body element.
	 * @return array (Maybe) filtered body classes.
	 */
	public function body_classes( $classes ) {
		global $post;

		// Adds a class of group-blog to sites with more than 1 published author.
		if ( is_multi_author() ) 
			$classes[] = 'group-blog';
	
		// Adds a class of no-sidebar to sites without active sidebar.
		if ( !is_active_sidebar( 'sidebar-1' ) ) 
			$classes[] = 'no-sidebar';
	
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() )
			$classes[] = 'hfeed';
		
		if ( isset( $post ) )
			$classes[] = $post->post_type . '-' . $this->get_page_slug_from_id($post->ID);

		if (isset($post->post_parent)) {
			$slug = $this->get_page_slug_from_id($post->post_parent); 
			$classes[] = "page-is-child";
			$classes[] = "parent-" . $slug;
		}
		
		if ($this->get_child_pages()->have_posts() || $this->get_sibling_pages()) 
			$classes[] = "sidebar";
		
		return $classes;
	}
	
	
	function my_deregister_heartbeat() {
	    global $pagenow;
	
	    if ( 'post.php' != $pagenow && 'post-new.php' != $pagenow ) {
	         wp_deregister_script('heartbeat');
	         wp_register_script('heartbeat', false);
	     }
	}





	//////////////////////////////////////////////////
	// SCRIPTS AND STYLES
	
	
    // Scripts and Styles for Admin Panel
    
    function admin_enqueue_scripts( $hook ) {
        wp_enqueue_style('rdr2-admin', RDR2_PLUGIN_ASSETS . '/css/admin.css', false, time());

        //wp_enqueue_script( 'rdr2-tooltip');
        
		wp_enqueue_script('rdr2-admin-top', RDR2_PLUGIN_ASSETS . '/js/admin-top.min.js', array('jquery'), FALSE, FALSE);
		wp_enqueue_script('rdr2-admin', RDR2_PLUGIN_ASSETS . '/js/admin.min.js', array('jquery'), FALSE, TRUE);
		wp_enqueue_script('rdr2-admin-tabs', RDR2_PLUGIN_ASSETS . '/js/jquery.easytabs.js', array('jquery'), FALSE, TRUE);

        //if ( 'plugins.php' == $hook ) {
        //    wp_enqueue_style( 'rdr2-plugin-list-css', RDR2_PLUGIN_ASSETS_DIR.'/css/plugin.css', false, null );
        //}

        do_action( 'rdr2_enqueue_admin_scripts');
    }
	
	
	
	// Frontend Scripts
	
	function enqueue_scripts( $hook ) {
		//wp_enqueue_style( 'rdr2-wp-list-tables', '/wp-admin/css/list-tables.css');
		wp_enqueue_style( 'rdr2-wp-common', '/wp-admin/css/common.css');
		
		//wp_enqueue_script('rdr2-admin-top', RDR2_PLUGIN_ASSETS . '/js/admin-top.min.js', array('jquery'), FALSE, FALSE );
		//wp_enqueue_script('rdr2-admin', RDR2_PLUGIN_ASSETS . '/js/admin.min.js', array('jquery'), FALSE, TRUE );
		wp_enqueue_script('rdr2-frontend', RDR2_PLUGIN_ASSETS . '/js/main.min.js', array('jquery'), FALSE, TRUE );
		wp_enqueue_script('rdr2-admin-tabs', RDR2_PLUGIN_ASSETS . '/js/jquery.easytabs.js', array('jquery'), FALSE, TRUE );
		
		wp_enqueue_style('font-awesome', "https://use.fontawesome.com/releases/v5.7.2/css/all.css" );
		
		wp_enqueue_style( 'dashicons' );
		
		wp_localize_script('jquery', 'database', array(
			'collection_locations' => json_encode( $this->rdr2->get_collection_locations() ),
			'collection_groups' => json_encode( $this->rdr2->get_collection_groups() ),
			'collections' => json_encode( $this->rdr2->get_collections() ),
			'collection_collectables' => json_encode( $this->rdr2->get_collection_collectables() ),
			'collection_items' => json_encode( $this->rdr2->get_collection_items() ),
			'collection_item_collectables' => json_encode( $this->rdr2->get_collection_item_collectables() ),
			'ingredients' => json_encode( $this->rdr2->get_ingredients() ),
			'ingredient_parts' => json_encode( $this->rdr2->get_ingredient_parts() ),
			'ingredient_qualities' => json_encode( $this->rdr2->get_ingredient_qualities() ),
			'ingredient_types' => json_encode( $this->rdr2->get_ingredient_types() ),
			'player_game_collected' => json_encode( $this->rdr2->get_player_game_collected( $this->game_id ) ),
			'player_game_items' => json_encode( $this->rdr2->get_player_game_items( $this->game_id ) ),
		));
			 
	}
	


	// Plugin Action Links
	
    function plugin_action_links($links) {
        $links[] = '<a href="' . admin_url( 'admin.php?page=admin-settings' ) . '">' . __( 'Settings', 'rdr2' ) . '</a>';
        //$links[] = '<a href="https://docs.wedevs.com/docs/rdr2/" target="_blank">' . __( 'Documentation', 'rdr2' ) . '</a>';
        return $links;
    }


	// Exclude Subscribers From MyCred
	
	function exclude_subscriber_from_mycred($reply, $user_id) {
		if (!user_can($user_id, 'edit_posts'))
			return true;
			
		return $reply;
	}
	



	//////////////////////////////////////////////
	// HELPERS
	
	
	// Convert an Object's Variables to an Array
	
	function object_to_array($d = FALSE) {
		// Gets the properties of the given object
		// with get_object_vars function
		if (is_object($d)) $d = get_object_vars($d);
	
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		if (is_array($d)) return array_map(__FUNCTION__, $d);
	
		// Return array
		else return $d;
	}


	
	// Extract all REQUEST terms to Object Variables
	
	function extract_terms($terms = FALSE) {		
		$this->terms_extracted = FALSE;
		
		if ($terms)
			$this->terms = $terms;
		
		if (empty($this->terms))
			return FALSE;
		
		if (is_object($this->terms)) 
			$this->terms = $this->object_to_array($this->terms);
		
		if (is_array($this->terms))
			foreach ($this->terms as $_key => $_value) 
				if ($this->$_key = $_value) 
					$this->terms_extracted = TRUE;
		
		return $this->terms_extracted;
	}	
	
	
	// Truncate respecting words 
	
	function truncate($string = FALSE, $length = 50, $append = "&hellip;") {
		if (empty($string))
			return FALSE;
		
		$string = trim($string);
	
		if (strlen($string) > $length) {
			$string = wordwrap($string, $length);
			$string = explode("\n", $string, 2);
			
			if (!empty($append))
				$string = $string[0] . $append;
		}
	
		return $string;
	}
	

	// Truncate by Characters
	function truncByChars($string = FALSE, $length = 50, $append = "&hellip;") {
	   if (strlen($string) > $length) {
		   $append_length = ($append == "&hellip;") ? 3 : strlen($append);
		   $string = substr_replace($string, $append, $length - $append_length);
	   }
	   return $string;
	}
	

} // RDR2_ProgressTracker



