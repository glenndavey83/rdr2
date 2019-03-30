<?php

// RDR2 PROGRESS TRACKER
// THEME
// FUNCTIONS

// Don't call the file directly
if ( ! defined( 'ABSPATH' ) ) exit;

ini_set('display_errors', 1);
 
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
 
$progresstracker_theme = new RDR2_ProgressTracker_Theme();
 
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since ProgressTracker 1.0
 */

class RDR2_ProgressTracker_Theme {

    function __construct() {
    	
        //$this->lib_dir     = dirname( __FILE__ ) . '/lib/';
        //$this->inc_dir     = dirname( __FILE__ ) . '/includes/';
        //$this->classes_dir = dirname( __FILE__ ) . '/classes/';

        // Include necessary Files
        //$this->includes();

        // Initialize Actions and Filters
        //$this->init_filters();
		
		// Initialize Filters
        $this->init_actions();

        // Initialize Classes
        //$this->init_classes();
	}



    /**
     * Init action hooks
     *
     * @return void
     */
    function init_actions() {
        add_action( 'after_setup_theme', array( $this, 'setup' ) );
        //add_action( 'widgets_init', array( $this, 'widgets_init' ) );		
		
		// ACTIONS FOR BOTH FRONTEND AND ADMIN
				
		add_action('check_admin_referer', array($this, 'logout_without_confirm'), 10, 2); // Logout without confirmation
		add_action('wp_logout', array($this, 'auto_redirect_after_logout')); // Auto redirect after logout
		
		// ADMIN ACTIONS ONLY
		
		if (is_admin()) {
			add_action('user_register', array($this, 'register_user_in_cms'), 10, 1); // User Register?
		}
		
		// FRONTEND ACTIONS ONLY
		
		else {
						
			add_action('wp', array($this, 'register_scripts')); // Register Scripts
			add_action('wp', array($this, 'register_styles')); // Register Styles
			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 9999); // Enqueue Scripts
			add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'), 20); // Enqueue Styles
			//add_action('get_footer', array($this, 'enqueue_footer_styles')); // Enqueue Footer Styles
		}
    }



    /**
     * Setup progresstracker
     *
     * @uses `after_setup_theme` hook
     */
    function setup() {

        /**
         * Make theme available for translation
         * Translations can be filed in the /languages/ directory
         */
        load_theme_textdomain( 'progresstracker-theme', get_template_directory() . '/languages' );

        /**
         * Add default posts and comments RSS feed links to head
         */
        //add_theme_support( 'automatic-feed-links' );

        /**
         * Enable support for Post Thumbnails
         */
        add_theme_support( 'post-thumbnails' );

        /**
         * This theme uses wp_nav_menu() in one location.
         */
        register_nav_menus( array(
            'primary'  => __( 'Primary Menu', 'progresstracker-theme' ),
            //'top-left' => __( 'Top Left', 'progresstracker-theme' ),
            'footer'   => __( 'Footer Menu', 'progresstracker-theme' ),
        ) );

        //add_theme_support( 'woocommerce' );

        /*
         * This theme supports custom background color and image,
         * and here we also set up the default background color.
         */
        /*
        add_theme_support( 'custom-background', array(
            'default-color' => 'F7F7F7',
        ) );
		*/

        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
    }

	// Initialize Classes
	
    public function init_classes() {
        
    }



	/////////////////////////////////////////////
	// WORDPRESS TAMING

	
	// Register User in CMS
	
	function register_user_in_cms( $user_id ) {
	  // accessing the data of user, and then send a post request to CMS
	  $user_info = get_userdata($user_id);
	  echo 'Username: ' . $user_info->user_login . "\n";
	  echo 'User roles: ' . implode(', ', $user_info->roles) . "\n";
	  echo 'User ID: ' . $user_info->ID . "\n";
	}


	// Allow logout without confirmation
	
	function logout_without_confirm($action, $result) {
			
		if ($action !== "log-out")
			return;
		
		if (isset($_GET['_wpnonce']))
			return;
		
	    $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '';
	    $location = str_replace('&amp;', '&', wp_logout_url($redirect_to));
	    wp_redirect($location);
		exit();
	}
	
	
	
	// Redirect to Homepage after Log Out
	
	function auto_redirect_after_logout() {
		wp_redirect(home_url());
		exit();
	}
	

	// Remove Wordpress Logo
	
	function remove_wp_logo( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'wp-logo' );
	}
	
	
	
	////////////////////////////////////////////////
	// THEME SCRIPTS AND STYLES

    /**
     * Register scripts and styles
     *
     * @since ProgressTracker 1.0
     */
    function register_scripts() {
		// TOP			
		//wp_register_script('progresstracker-script-top', get_template_directory_uri() . '/js/functions_top.min.js', array('jquery'), FALSE, FALSE);
		
		// BOTTOM
		//wp_register_script('progresstracker-script', get_stylesheet_directory_uri() . '/assets/js/main.min.js', array('jquery'), FALSE, TRUE);
	}
	

	// Register Styles
	
	function register_styles() {
		wp_register_style("progresstracker-theme", get_stylesheet_directory_uri() . "/assets/css/rdr2-theme.css");
	}


    /**
     * Enqueue scripts and styles
     *
     * @since ProgressTracker 1.0
     */
    function enqueue_scripts() {
    	global $wp_query;
		
		//wp_enqueue_script('progresstracker-script-top');
		//wp_enqueue_script('progresstracker-script');

		wp_localize_script('jquery', 'ajaxpagination', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'query_vars' => json_encode( $wp_query->query )
		));
    }


	
	// Enqueue Styles 
	
	function enqueue_styles () {
		wp_enqueue_style('progresstracker-theme');
	}

}


