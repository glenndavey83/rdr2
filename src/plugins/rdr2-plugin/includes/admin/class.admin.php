<?php

/**
 * WordPress settings API For CollectablesTracker Admin Settings class
 *
 * @author Glenn Davey
 */
class RDR2_CollectablesTracker_Admin  {

    /**
     * Hold settings api instance
     *
     * @var class instance
     */
    private $settings_api;
	public $version = '1.0.0';
    private $container = array();
	private static $_singleton;
	private $terms;

    /**
     * Constructor for the CollectablesTracker_Admin class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @return void
     */
    function __construct() {
        //$this->settings_api = new CollectablesTracker_Settings();
        //add_action( 'admin_init', array($this, 'do_updates' ) );
        //add_action( 'admin_menu', array($this, 'admin_menu') );
        //add_action( 'admin_head', array( $this, 'welcome_page_remove' ) );
        //add_action( 'admin_notices', array($this, 'update_notice' ) );
        //add_action( 'admin_notices', array($this, 'promotional_offer' ) );
    	//add_action( 'wp_before_admin_bar_render', array( $this, 'rdr2_admin_toolbar' ) );
    	
    	//parent::
    	
		add_action("admin_init", array($this, 'do_actions')); // Do Actions before Redirect
		add_action("admin_menu", array($this, "admin_add_menus")); // Add Menus to Admin Sidebar
		add_action('wp_dashboard_setup', array($this, 'admin_add_dashboard_widgets') );
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts')); // Enqueue Admin Scripts
    }


	// Initiate Class
	
	public static function init($inputdata = array()) {
		if (!self::$_singleton) 
			self::$_singleton = new RDR2_CollectablesTracker_Admin($inputdata);
        
        return self::$_singleton;
    }


    function dashboard_script() {
        //wp_enqueue_style( 'rdr2-admin-dash', DOKAN_PLUGIN_ASSEST . '/css/admin.css' );
        //wp_enqueue_style( 'rdr2-admin-report', DOKAN_PLUGIN_ASSEST . '/css/admin.css' );
        //wp_enqueue_style( 'jquery-ui' );
        //wp_enqueue_style( 'rdr2-chosen-style' );
        //wp_enqueue_script( 'jquery-ui-datepicker' );
        //wp_enqueue_script( 'wp-color-picker' );
        //wp_enqueue_script( 'rdr2-flot' );
        //wp_enqueue_script( 'rdr2-chart' );
        //wp_enqueue_script( 'rdr2-chosen' );

        //do_action( 'rdr2_enqueue_admin_dashboard_script' );
    }


    // Scripts and Styles for Admin Panel
    
    function admin_enqueue_scripts( $hook ) {
        wp_enqueue_style( 'rdr2-admin', RDR2_PLUGIN_ASSETS . '/css/admin.css', false, time() );

        //wp_enqueue_script( 'rdr2-tooltip' );
        
        wp_enqueue_script('rdr2-admin-top', RDR2_PLUGIN_ASSETS . '/js/admin-top.min.js', array('jquery'), FALSE, FALSE );
		wp_enqueue_script('rdr2-admin', RDR2_PLUGIN_ASSETS . '/js/admin.min.js', array('jquery'), FALSE, TRUE );
		wp_enqueue_script('rdr2-admin-tabs', RDR2_PLUGIN_ASSETS . '/js/jquery.easytabs.js', array('jquery'), FALSE, TRUE );

        //if ( 'plugins.php' == $hook ) {
        //    wp_enqueue_style( 'rdr2-plugin-list-css', RDR2_PLUGIN_ASSETS_DIR.'/css/plugin.css', false, null );
        //}

        do_action( 'rdr2_enqueue_admin_scripts' );
    }


	// Menus
	
	function admin_add_menus() {
		
		// RDR2 CONTROL 
		
		// Top Menu Item
		add_menu_page("Control", 'Control', 'manage_options', 'rdr2-control', array($this, 'admin_rdr2_control'), "dashicons-admin-tools", 3);
		
		// Remove duplicate menu hack 
		//add_submenu_page('rdr2-control', '', '',  'manage_options', 'rdr2-control');
		
		
		
		// MERCHANTS

		// Top Menu Item
		add_menu_page("Merchants", 'Merchants',  'manage_options', 'rdr2-merchants', array($this, 'admin_rdr2_merchants'), "dashicons-store", 2);
		
		// Remove duplicate menu hack 
		//add_submenu_page('rdr2-merchants', '', '',  'manage_options', 'rdr2-merchants');

		// Locations
		//add_submenu_page('rdr2-merchants', 'Locations', 'Locations',  'manage_options', 'rdr2-merchants-locations', array($this, 'admin_rdr2_merchant_locations'));	

		// Groups
		//add_submenu_page('rdr2-merchants', 'Groups', 'Groups',  'manage_options', 'rdr2-merchants-groups', array($this, 'admin_rdr2_merchant_groups'));	

		// Statistics
		add_submenu_page('rdr2-merchants', 'Statistics', 'Statistics',  'manage_options', 'rdr2-merchants-statistics', array($this, 'admin_rdr2_merchant_statistics'));	
		
		
		
		// PLAYERS / CARDS
		
		// Top Menu Item
		add_menu_page("Cards", 'Cards',  'manage_options', 'rdr2-players', array($this, 'admin_rdr2_players'), "dashicons-images-alt2", 3);
		
		// Remove duplicate menu hack 
		//add_submenu_page('rdr2-players', '', '',  'manage_options', 'rdr2-players');
		
		// Statistics
		add_submenu_page('rdr2-players', 'Statistics', 'Statistics',  'manage_options', 'rdr2-player-statistics', array($this, 'admin_rdr2_player_statistics'));	



		// SALES
		/*
		// Top Menu Item
		add_menu_page("Transactions", 'Transactions',  'manage_options', 'rdr2-transactions', array($this, 'admin_rdr2_transactions'), "dashicons-cart", 3);
		
		// Remove duplicate menu hack 
		add_submenu_page('rdr2-transactions', '', '',  'manage_options', 'rdr2-transactions');

		// Statistics
		add_submenu_page('rdr2-transactions', 'Statistics', 'Statistics',  'manage_options', 'rdr2-transactions-statistics', array($this, 'admin_rdr2_transactions_statistics'));	
		*/


		// REPORTS 
		
		// Top Menu Item
		add_menu_page("Reports", 'Reports',  'manage_options', 'rdr2-reports', array($this, 'admin_rdr2_reports'), "dashicons-chart-bar", 3);
		
		// Remove duplicate menu hack 
		//add_submenu_page('rdr2-reports', '', '',  'manage_options', 'rdr2-reports');

	}

	
	
	// Toolbar
	// Unused

    function rdr2_admin_toolbar() {
        global $wp_admin_bar;

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $args = array(
            'id'     => 'rdr2',
            'title'  => __( 'CollectablesTracker', 'rdr2' ),
            'href'   => admin_url( 'admin.php?page=rdr2' )
        );

        $wp_admin_bar->add_menu( $args );

        $wp_admin_bar->add_menu( array(
            'id'     => 'rdr2-dashboard',
            'parent' => 'rdr2',
            'title'  => __( 'CollectablesTracker Dashboard', 'rdr2' ),
            'href'   => admin_url( 'admin.php?page=rdr2' )
        ) );

        $wp_admin_bar->add_menu( array(
            'id'     => 'rdr2-withdraw',
            'parent' => 'rdr2',
            'title'  => __( 'Withdraw', 'rdr2' ),
            'href'   => admin_url( 'admin.php?page=rdr2-withdraw' )
        ) );

        $wp_admin_bar->add_menu( array(
            'id'     => 'rdr2-pro-features',
            'parent' => 'rdr2',
            'title'  => __( 'PRO Features', 'rdr2' ),
            'href'   => admin_url( 'admin.php?page=rdr2-pro-features' )
        ) );

        $wp_admin_bar->add_menu( array(
            'id'     => 'rdr2-settings',
            'parent' => 'rdr2',
            'title'  => __( 'Settings', 'rdr2' ),
            'href'   => admin_url( 'admin.php?page=rdr2-settings' )
        ) );

        /**
         * Add new or remove toolbar
         *
         * @since 2.5.3
         */
        do_action( 'rdr2_render_admin_toolbar', $wp_admin_bar );
    }








	//////////////////////////////////////////////////////
	// PRINTERS
	
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



	// Print Admin Players
	
	public function print_admin_players() {
		$player_filters = $this->get_player_filters();
		$player_list_table = $this->get_players_list_table();
		$player_list_table->prepare_items();
		include_once(RDR2_ADMIN_VIEWS_DIR . '/rdr2-players.php');
	}



	// PAGES

	// Admin CollectablesTracker Players Control Panel
	
	function admin_rdr2_players() {
		$id = (isset($this->id)) ? $this->id : FALSE;
		$player = FALSE;
		$message = (isset($_REQUEST['saved'])) ? get_bloginfo('name') . ' options successfully saved.' : FALSE;
		$buttons = "<a href=' . " . $_SERVER['PHP_SELF'] . "?page=rdr2-players' class='btn button'><span class='dashicons dashicons-arrow-up-alt2'></span> Players</a>";
		
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
	
	
	
	// Flush Admin Statistics
	
	private function flush_admin_statistics() {
		
		// Get list of options and getters
		
		$statistics = array(
			"player_total" => "get_player_total",
			"player_total_clean" => "get_player_total_clean",
			"players_missing_barcode" => "get_players_missing_barcode",
			"players_missing_creation_date" => "get_players_missing_creation_date",
			"players_per_year" => "get_players_per_year",
			"players_with_points" => "get_players_with_points",
			"merchant_total" => "get_merchant_total",
			"merchants_status_act" => "get_merchants_status_act",
			"merchants_status_res" => "get_merchants_status_res",
		);
		
		
		// Delete option values
		
		if (!isset($statistics) || !is_array($statistics))
			return FALSE;
		
		$statistics_list = array_keys($statistics);
		
		return $this->delete_option_values($statistics_list);
		
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
	


	// Regenerate Admin Statistics
	
	private function regenerate_admin_statistics() {
		
	}

}

//$settings = new CollectablesTracker_Admin();
