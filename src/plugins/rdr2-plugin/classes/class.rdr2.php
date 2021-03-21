<?php

// RDR2
// CLASS
// WORDPRESS PLUGIN


class RDR2 {


    public $version = '1.0.0';
    private $container = array();
	private static $_singleton;
	private $terms;
	private $terms_extracted;
	public $card_number_prefix;
	private $results;
	private $account_ids;
	private $player;
	
	
	// Construct 
	
    public function __construct($vars = FALSE) {
    	
    	global $wpdb;
    	$this->terms = $vars;		
		
		$this->card_number_prefix = "601690";
		$this->per_page = 20;
		$this->player = FALSE;
		$this->player_id = FALSE;
		$this->player_games = FALSE;
		$this->player_progress = FALSE; 
		$this->game_id = FALSE;
		$this->id = FALSE;
		$this->statuses = array( "Inactive", "Active" );
		
		// Extract $_REQUEST Terms to Object Variables
		if (!$this->terms_extracted) 
			$this->extract_terms();
		
		$this->wpdb = $wpdb;
		$this->time_display_format = '%H:%i %p';
		$this->date_display_format = '%e/%m/%Y';
		
		
    }



	// Initiate Class
	
	public static function init($inputdata = array()) {
		if (!self::$_singleton) 
			self::$_singleton = new RDR2($inputdata);
        
        return self::$_singleton;
    }



	// Extract all REQUEST terms to Object Variables
	
	public function extract_terms($terms = FALSE) { 
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









	///////////////////////////////////////////////////////////////
	// GETTERS

	

	// PLAYERS
	
	
	// Get Player ID from Wordpress User ID
	
	function get_player_id( $user_id = FALSE ) {
		// ABORT if no User ID passed
		if ( ! $user_id || ! is_numeric( $user_id ) )
			return FALSE;
		
		global $wpdb;
		
		$query = '
			SELECT ID FROM ' . $wpdb->players . '
			WHERE WP_User_ID = ' . $user_id . '
			LIMIT 1
		';
		
		$result = $wpdb->get_var( $query );
		
		return $result;
	}
	
	
	
	// Get Player Info
	
	function get_player_info( $player_id = FALSE ) {
		
		if ( ! $player_id )
			$player_id = $this->player_id;
		
		if ( ! $player_id )
			return false;
		
		if ( $this->player !== FALSE )
			return $this->player;
		
		global $wpdb;
		
		$query = '
			SELECT * FROM ' . $wpdb->players . '
			WHERE ID = ' . $player_id . '
			LIMIT 1
		';
		
		$result = $wpdb->get_row( $query );
		
		return $this->player = $result;
		
	}



	// Get Player Games Count
	
	function get_player_games_count( $player_id = FALSE ) {
		
		if ( ! $player_id )
			$player_id = $this->player_id;
		
		if ( ! $player_id )
			return false;
		
		if ( $this->player_games == FALSE )
			$this->player_games = $this->get_player_games( $player_id );
		
		return ( ! empty( $this->player_games ) ) ? count( $this->player_games ) : 0;
	}



	// Player Has Games
	
	function player_has_games($player_id = FALSE) {
		
		if ( ! $player_id )
			$player_id = $this->player_id;
		
		if ( ! $player_id )
			return false;
		
		if ( $this->player_games == FALSE )
			$this->player_games = $this->get_player_games( $player_id );
		
		return ( $this->player_games !== FALSE && ! empty( $this->player_games ) );
	}
	


	// Get Player Games
	
	function get_player_games( $player_id = FALSE ) {
		
		if ( $this->player_games )
			return $this->player_games;
		
		if ( ! $player_id )
			$player_id = $this->player_id;
		
		if ( ! $player_id )
			return FALSE;
		
		$query = '
			SELECT ID, Created 
			FROM ' . $this->wpdb->player_games . '  
			WHERE Player = ' . $player_id . '
			ORDER BY Created DESC 
		';
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		if ( ! is_array( $results ) || empty( $results ) )
			return FALSE;
		
		return $this->player_games = $results;
	}



	// Get Player's Active Game
	
	function get_player_active_game( $player_id = FALSE ) {
		
		// ABORT if no Player ID passed		
		if ( ! $player_id && $this->player_id )
			$player_id = $this->player_id;
		
		if ( ! $player_id )
			return FALSE;
		
		$query = '
			SELECT CurrentGame 
			FROM ' . $this->wpdb->players . ' 
			WHERE ID = ' . $player_id . ' 
			LIMIT 1
		';
		
		$result = $game_id = $this->wpdb->get_var( $query );
		
		if ( empty( $result ) && $player_games = $this->get_player_games( $player_id ) ) {
			
			$result = $game_id = $player_games[ 0 ][ 'ID' ];
			
			// Set Active Player Game
			$this->set_player_active_game( $game_id, $player_id );
		}
		
		return $this->game_id = $result;
	}



	// Get Player Game Info
	
	function get_player_game_info( $game_id = FALSE ) {
		
		if ( ! $game_id && $this->game_id = $this->get_player_active_game() )
			$game_id = $this->game_id;
		
		$query = '
			SELECT * 
			FROM ' . $this->wpdb->player_games . ' 
			WHERE ID = ' . $game_id . ' 
			LIMIT 1
		';
		
		$result = $this->wpdb->get_row( $query, ARRAY_A );
		
		return $this->player_game = $result;
	}
	


	// Get Player Game Collected Count
	
	function get_player_game_collected_count( $player_id = FALSE ) {
		
		if ( ! $player_id )
			$player_id = $this->player_id;
		
		if ( ! $player_id )
			return false;
		
		if ( ! $this->player_progress )
			$this->player_progress = $this->get_player_game_collected( $player_id );
		
		$item_count = ( empty( $this->player_progress ) ) ? 0 : $this->player_progress;
		
		return $item_count;
	} 



	// Get Player Game Collected
	
	function get_player_game_collected( $game_id = FALSE ) {
		
		if ( ! $game_id )
			$game_id = $this->game_id;
		
		if ( ! $game_id ) {
			//echo "Cannot get game id";
			return FALSE;
		}
		
		$query = '
			SELECT Collectable, Collected 
			FROM ' . $this->wpdb->player_game_collected . ' 
			WHERE Game = ' . $game_id 
		;
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		return $results;
	}	



	// Get Player Game Submitted
	
	function get_player_game_submitted( $game_id = FALSE ) {
		
		if ( ! $game_id )
			$game_id = $this->game_id;
		
		if ( ! $game_id ) {
			//echo "Cannot get game id";
			return false;
		}
		
		$query = '
			SELECT CraftableCollectable, Quantity 
			FROM ' . $this->wpdb->player_game_submitted . ' 
			WHERE Game = ' . $game_id 
		;
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		return $results;
	}	
	


	// Get Player Game Crafted 
	
	function get_player_game_crafted( $game_id = FALSE ) {
		
		if ( ! $game_id )
			$game_id = $this->game_id;
		
		if ( ! $game_id )
			return false;
		
		$query = '
			SELECT Craftable, Acquired FROM ' . $this->wpdb->player_game_crafted . '
			WHERE Game = ' . $game_id
		;
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		return $this->player_game_crafted = $results; 
	}
	





	// CRAFTABLES
	
	
	
	// Get Craftable Sources
	
	function get_craftable_sources() {
		
		$query = '
			SELECT ID, Name 
			FROM ' . $this->wpdb->craftable_sources . ' 
			WHERE 1 
		';
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		return ( ! is_array( $results ) || empty( $results ) ) ? FALSE : $results; 
	}



	// Get Collections
	
	function get_craftable_categories() {
		
		$query = '
			SELECT ID, Source, Name, Inactive 
			FROM ' . $this->wpdb->craftable_categories . ' 
			WHERE 1 
		';
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		return ( ! is_array( $results ) || empty( $results ) ) ? FALSE : $results; 
	}



	// Get Craftable Groups
	
	function get_craftable_groups() {
			
		$query = '
			SELECT ID, Category, Name 
			FROM ' . $this->wpdb->craftable_groups . ' 
			WHERE 1 
		';
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		return ( ! is_array( $results ) || empty( $results ) ) ? FALSE : $results; 
	}



	// Get Craftables
	
	function get_craftables() {
		
		$query = '
			SELECT ID, Craftable_Group, Name 
			FROM ' . $this->wpdb->craftables . ' 
			WHERE 1 
		';
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		return ( ! is_array( $results ) || empty( $results ) ) ? FALSE : $results; 
	}
	
	

	// Get Craftable Collectables
	
	function get_craftable_collectables() {
		
		$query = '
			SELECT * FROM ' . $this->wpdb->craftable_collectables . ' 
			WHERE 1 
		';
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		return ( ! is_array( $results ) || empty( $results ) ) ? FALSE : $results; 
	}



	// Get Collectables
	
	function get_collectables() {
		
		$query = '
			SELECT * 
			FROM ' . $this->wpdb->collectables . ' 
			WHERE 1 
		';
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		return ( ! is_array( $results ) || empty( $results ) ) ? FALSE : $results; 
	}
	
	

	// Get Ingredients
	
	function get_ingredients() {
		
		$query = '
			SELECT * 
			FROM ' . $this->wpdb->ingredients . ' 
			WHERE 1 
		';
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		return ( ! is_array( $results ) || empty( $results ) ) ? FALSE : $results; 
	}



	// Get Ingredients Parts
	
	function get_ingredient_parts() {
		
		$query = '
			SELECT * 
			FROM ' . $this->wpdb->ingredient_parts . ' 
			WHERE 1 
		';
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		return ( ! is_array( $results ) || empty( $results ) ) ? FALSE : $results; 
	}



	// Get Ingredient Qualities
	
	function get_ingredient_qualities() {
		
		$query = '
			SELECT * 
			FROM ' . $this->wpdb->ingredient_qualities . ' 
			WHERE 1 
		';
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		return ( ! is_array( $results ) || empty( $results ) ) ? FALSE : $results; 
	}
	
	
	
	// Get Ingredient Types
	
	function get_ingredient_types() {
		$query = '
			SELECT * 
			FROM ' . $this->wpdb->ingredient_types . ' 
			WHERE 1 
		';
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		return ( ! is_array( $results ) || empty( $results ) ) ? FALSE : $results; 
	}



	// Get Collection Data
	
	function get_collection_data() {
		$data = array(
		
			'craftable_sources' => $this->get_craftable_sources(),
			'craftable_groups' => $this->get_craftable_groups(),
			'craftable_categories' => $this->get_craftable_categories(),
			'collectables' => $this->get_collectables(),
			'craftables' => $this->get_craftables(),
			'craftable_collectables' => $this->get_craftable_collectables(),
			'ingredients' => $this->get_ingredients(),
			'ingredient_parts' => $this->get_ingredient_parts(),
			'ingredient_qualities' => $this->get_ingredient_qualities(),
			'ingredient_types' => $this->get_ingredient_types(),
			
		);
		
		return $data;
	}
	






	// Get Random Tips
	
	function get_tips() {
			
		$query = '
			SELECT * 
			FROM ' . $this->wpdb->tips . ' 
			WHERE 1'
		;
		
		$results = $this->wpdb->get_results( $query, ARRAY_A );
		
		return ( ! is_array( $results ) || empty( $results ) ) ? FALSE : $results; 
	}







	///////////////////////////////////////////////////////////////////
	// SETTERS

	
	// INSERT

	
	// Insert Player Game Collected Collected

	function insert_player_game_collected( $game_id = FALSE, $collectable = FALSE, $quantity = 1 ) {
				
		if ( ! $game_id ) 
			$game_id = $this->game_id;
		
		if ( ! $game_id ) {
			
			echo "No game ID received!\n"; 
			
			return FALSE;
		}
		
		if ( ! $collectable ) {
			
			echo "No Collectable ID received!\n";
			
			return false;
		}
		
		$data = array( 'Game' => $game_id, 'Collectable' => $collectable, 'Collected' => $quantity );
		
		$insert_id = $this->wpdb->insert( $this->wpdb->player_game_collected, $data, '%d' );	
		
		$result = ( $insert_id !== '0' ) ? $insert_id : FALSE;
		
		return $result;
	}



	// Insert Player Game Submitted Collectables
	
	function insert_player_game_submitted( $game_id = FALSE, $collectable = FALSE, $quantity = 1 ) { 
		if ( ! $game_id ) 
			$game_id = $this->game_id;
		
		if ( ! $game_id ) {
			//echo "No Game ID received!\n"; 
			return FALSE;
		}
		
		if ( ! $collectable ) {
			//echo "No Collectable ID received!\n";
			return false;
		}
		
		$data = array( 'Game' => $game_id, 'CraftableCollectable' => $collectable, 'Quantity' => $quantity );
		
		$insert_id = $this->wpdb->insert( $this->wpdb->player_game_submitted, $data, '%d' );	
		
		$result = ( $insert_id !== '0' ) ? $insert_id : FALSE; 
		
		return $result;
	}



	// Insert Player Game Crafted
	
	function insert_player_game_crafted( $game_id = FALSE, $craftable = FALSE, $acquired = 1 ) {
				
		if ( ! $game_id ) 
			$game_id = $this->game_id;
		
		if ( ! $game_id ) {
			//echo "No Game ID received!\n"; 
			return FALSE;
		}
		
		if ( ! $craftable ) {
			//echo "No Craftable ID received!\n";
			return false;
		}
		
		$data = array( 'Game' => $game_id, 'Craftable' => $craftable, 'Acquired' => $acquired );
		
		$insert_id = $this->wpdb->insert( $this->wpdb->player_game_crafted, $data, '%d' );	
		
		$result = ( $insert_id !== '0' ) ? $insert_id : FALSE;
		
		return $result;
	}
	
	
	
	// UPDATE
	
	
	// Update Player Game Collected Collectable

	function update_player_game_collected( $game_id = FALSE, $collectable = FALSE, $quantity = FALSE ) {
		
		if ( ! $game_id ) 
			$game_id = $this->game_id;
		
		if ( ! $game_id ) {
			//echo "No game ID received!\n"; 
			return FALSE;
		}
		
		if ( ! $collectable ) {
			//echo "No collectable ID received!\n";
			return false;
		}
		
		if ( $quantity === FALSE || ! is_numeric( $quantity ) ) {
			//echo "No valid quantity received!\n";
			return false;
		}
		
		$data = array( 'Collected' => $quantity );
		
		$where = array( 'Game' => $game_id, 'Collectable' => $collectable );
		
		$update_id = $this->wpdb->update( $this->wpdb->player_game_collected, $data, $where, '%d', '%d' );
		
		$result = ( $update_id !== '0' ) ? $update_id : FALSE;
		
		return $result;
	}
	


	// Update Player Game Submitted 
	
	function update_player_game_submitted( $game_id = FALSE, $collectable = FALSE, $quantity = FALSE ) {
				
		if ( ! $game_id ) 
			$game_id = $this->game_id;
		
		if ( ! $game_id ) {
			
			//echo "No Game ID received!\n"; 
			
			return FALSE;
		}
		
		if ( ! $collectable ) {
			
			//echo "No Craftable Collectable ID received!\n";
			
			return false;
		}
		
		if ( $quantity === FALSE || ! is_numeric( $quantity ) ) {
			
			echo "No valid quantity received!\n";
			
			return false;
		}
		
		$data = array( 'Quantity' => $quantity );
		
		$where = array( 'Game' => $game_id, 'CraftableCollectable' => $collectable );
		
		$update_id = $this->wpdb->update( $this->wpdb->player_game_submitted, $data, $where, '%d', '%d' );
		
		$result = ( $update_id !== '0' ) ? $update_id : FALSE;
		
		return $result;	
	}



	// Update Player Game Craftable 
	
	function update_player_game_craftable( $game_id = FALSE, $craftable = FALSE, $acquired = FALSE ) {		
		if ( ! $game_id ) 
			$game_id = $this->game_id;
		
		// ABORT if no Game ID for updating
		if ( ! $game_id ) {
				
			echo "No Game ID received!\n"; 
			
			return;
		}
		
		// ABORT if no Craftable ID for updating
		if ( ! $craftable ) {
				
			echo "No Craftable ID received!\n";
			
			return;
		}
		
		// ABORT if no Acquired boolean for updating
		if ( $acquired === false ) {
			
			echo "No Acquired boolean value received!\n";
			
			return;
		}
		
		$data = array( 'Acquired' => $acquired ); 
		
		$where = array( 'Game' => $game_id, 'Craftable' => $craftable );
		
		$update_id = $this->wpdb->update( $this->wpdb->player_game_crafted, $data, $where, '%d', '%d' );
		
		$result = ( $update_id !== '0' ) ? $update_id : FALSE;
		
		return $result;	
	}





	// FILTERS
	
	
	// Update Player Filter Remaining

	function update_player_filter_remaining( $player_id = FALSE, $filter = NULL ) {
		
		if ( ! $player_id )
			$player_id = $this->player_id;
		
		if ( ! $player_id )
			return FALSE;
		
		if ( $filter === NULL || ! is_numeric( $filter ) ) 
			return FALSE;
		
		
		$data = array( 'FilterRemaining' => $filter );
		
		$where = array( 'ID' => $player_id );
		
		$update_id = $this->wpdb->update( $this->wpdb->players, $data, $where, '%d', '%d' );
		
		return $update_id;
	}


	
}








// HELPERS


function searchForId( $id, $array, $arr_key ) {
	
	foreach ( $array as $key => $val ) {
		if ( $val[ $arr_key ] === $id ) {
			return $key;
		}
	}
	
	return null;
}








////////////////////////////
// DEBUGGING FUNCTIONS
////////////////////////////


// SAYHELLO FUNCTION :D
// Handy debugging tool
// Usage: sayhello([string || array]);

// Prints "Hello world!" anywhere it is placed
// Prints $also if set and equals TRUE
// Prints nothing if $also is set but equals FALSE

function helloWorld( $also = FALSE ) {
	
	echo '<h1>Hello, world!</h1>\n';
	
	if ( $also === FALSE )
		return;
	
	if ( is_array( $also ) ) {
			
		echo '<p><pre>';
		
		print_r( $also );
		
		echo "</pre></p>\n";
		
		return;
	}
	
	echo '<p>' . $also . '</p>\n';
}



// Echo $data in a Comment

function commentEcho( $data = FALSE ) {
	
	if ( $data === FALSE)
		return;
		
	echo '\n <!-- ';
	
	if ( is_array( $data ) ) {
		echo '\n';
		print_r($data);
		echo '\n';
	}
	else
		echo $data;
	
	echo ' //--> \n';
	
}

