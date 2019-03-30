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
	
	
	// Construct 
	
    public function __construct($vars = FALSE) {
    	global $wpdb;
    	$this->terms = $vars;		
		
		$this->card_number_prefix = "601690";
		$this->per_page = 20;
		$this->player_id = FALSE;
		$this->player_games = FALSE;
		$this->player_progress = FALSE; 
		$this->game_id = FALSE;
		$this->id = FALSE;
		$this->statuses = array("Inactive", "Active");
		
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

	
	// Get State Options
	
	function get_states() {
		$query = "SELECT * FROM rdr2_states";
		return $this->wpdb->get_results($query, ARRAY_A);
	}
	






	// PLAYERS
	
	
	// Get Player ID from Wordpress User ID
	
	function get_player_id( $user_id = FALSE ) {
		if ( !$user_id || !is_numeric($user_id) )
			return FALSE;
		
		global $wpdb;
		
		$query = "
			SELECT ID FROM " . $wpdb->players . "
			WHERE WP_User_ID = " . $user_id . "
			LIMIT 1
		";
		
		$result = $wpdb->get_var( $query );
		
		return $result;
	}	
	
	
	
	// Get Player Games
	
	function get_player_games($player_id = FALSE) {
		if (!$player_id)
			$player_id = $this->player_id;
		
		if (!$player_id)
			return false;
		
		$query = "
			SELECT ID, Created FROM " . $this->wpdb->player_games . "  
			WHERE Player = " . $player_id . "
			ORDER BY Created DESC 
		";
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		if (!is_array($results) || empty($results))
			return false;
		
		return $results;
	}
	
	
	
	// Get Player's Active Game
	
	function get_player_active_game($player_id = FALSE) {		
		if (!$player_id && $this->player_id)
			$player_id = $this->player_id;
		
		if (!$player_id)
			return FALSE;
		
		$query = "SELECT CurrentGame FROM " . $this->wpdb->players . " WHERE ID = " . $player_id . " LIMIT 1";
		$result = $game_id = $this->wpdb->get_var($query);
		
		if (empty($result) && $player_games = $this->get_player_games($player_id)) {
			$result = $game_id = $player_games[0]['ID'];
			$this->set_player_active_game($game_id, $player_id);
		}
		
		return $this->game_id = $result;
	}
	
	
	
	// Player Has Games
	
	function player_has_games($player_id = FALSE) {
		if (!$player_id)
			$player_id = $this->player_id;
		
		if (!$player_id)
			return false;
		
		if ($this->player_games == FALSE)
			$this->player_games = $this->get_player_games($player_id);
		
		return ($this->player_games !== FALSE && !empty($this->player_games));
	}
	
	
	
	// Get Player Game Count
	
	function get_player_games_count($player_id = FALSE) {
		if (!$player_id)
			$player_id = $this->player_id;
		
		if (!$player_id)
			return false;
		
		if ($this->player_games == FALSE)
			$this->player_games = $this->get_player_games($player_id);
		
		return (!empty($this->player_games)) ? count($this->player_games) : 0;
	}
	
	
	
	// Get Player Game Info
	
	function get_player_game_info($game_id = FALSE) {
		if (!$game_id && $this->game_id = $this->get_player_active_game())
			$game_id = $this->game_id;
		
		$query = "SELECT * FROM " . $this->wpdb->player_games . " WHERE ID = " . $game_id . " LIMIT 1";
		
		$result = $this->wpdb->get_row($query, ARRAY_A);
		
		return $this->player_game = $result;
	}
	
	
	
	// Get Player Progress
	
	function get_player_game_collectables($game_id = FALSE) {
		if (!$game_id)
			$game_id = $this->game_id;
		
		if (!$game_id)
			return false;
		
		$query = "
			SELECT ID FROM " . $this->wpdb->player_game_collectables . "
			WHERE Game = " . $game_id
		;
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return $this->player_game_collectables = $results; 
	}
	
	
	
	// Get Player Progress
	
	function get_player_game_items( $game_id = FALSE ) {
		if (!$game_id)
			$game_id = $this->game_id;
		
		if (!$game_id)
			return false;
		
		$query = "
			SELECT Item, Acquired FROM " . $this->wpdb->player_game_items . "
			WHERE Game = " . $game_id
		;
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return $this->player_game_items = $results; 
	}
	
	
	
	// Get Player Game Collected
	function get_player_game_collected( $game_id = FALSE ) {
		if (!$game_id)
			$game_id = $this->game_id;
		
		if (!$game_id) {
			//echo "Cannot get game id";
			return false;
		}
		
		$query = "
			SELECT Collectable, Collected 
			FROM " . $this->wpdb->player_game_collectables . " 
			WHERE Game = " . $game_id 
		;
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return $results;
	}	
	
	
	
	// Get Player Game Submitted
	function get_player_game_submitted( $game_id = FALSE ) {
		if (!$game_id)
			$game_id = $this->game_id;
		
		if (!$game_id) {
			//echo "Cannot get game id";
			return false;
		}
		
		$query = "
			SELECT ItemCollectable, Quantity 
			FROM " . $this->wpdb->player_game_item_collectables . " 
			WHERE Game = " . $game_id 
		;
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return $results;
	}	


	
	
	// Get Player Progress Count
	function get_player_progress_count($player_id = FALSE) {
		if (!$player_id)
			$player_id = $this->player_id;
		
		if (!$player_id)
			return false;
		
		if (!$this->player_progress)
			$this->player_progress = $this->get_player_game_collectables($player_id);
		
		$item_count = (empty($this->player_progress)) ? 0 : $this->player_progress;
		
		return $item_count;
	} 
	
	
	
	// Get Player Progress Percentage
	function get_player_progress_percentage() {
		
	}
	
	
	// Player Has Collected Items
	
	
	
	// Get Player Items
	function get_player_remaining_ingredients() {
		
	}





	// COLLECTIONS
	
	
	
	// Get Collection Locations
	
	function get_collection_locations() {
		
		$query = "
			SELECT ID, Name FROM " . $this->wpdb->collection_locations . " 
			WHERE 1 
		";
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return (!is_array($results) || empty($results)) ? FALSE : $results; 
	}
	
	
	
	// Get Collections
	
	function get_collections() {
		
		$query = "
			SELECT ID, Location, Name FROM " . $this->wpdb->collections . " 
			WHERE 1 
		";
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return (!is_array($results) || empty($results)) ? FALSE : $results; 
	}



	// Get Collection Items
	
	function get_collection_items() {
		$query = "
			SELECT ID, `Group`, Name FROM " . $this->wpdb->collection_items . " 
			WHERE 1 
		";
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return (!is_array($results) || empty($results)) ? FALSE : $results; 
	}



	// Get Collection Groups
	
	function get_collection_groups() {
		$query = "
			SELECT ID, Collection, Name FROM " . $this->wpdb->collection_groups . " 
			WHERE 1 
		";
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return (!is_array($results) || empty($results)) ? FALSE : $results; 
	}
	
	
	
	// Get Collection Collectables
	
	function get_collection_collectables() {
		$query = "
			SELECT * FROM " . $this->wpdb->collection_collectables . " 
			WHERE 1 
		";
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return (!is_array($results) || empty($results)) ? FALSE : $results; 
	}



	// Get Collection Item Collectables
	
	function get_collection_item_collectables() {
		$query = "
			SELECT * FROM " . $this->wpdb->collection_item_collectables . " 
			WHERE 1 
		";
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return (!is_array($results) || empty($results)) ? FALSE : $results; 
	}
	
	
	
	// Get Collection Data
	
	function get_collection_data() {
		$data = array(
			'collection_locations' => $this->get_collection_locations(),
			'collection_groups' => $this->get_collection_groups(),
			'collections' => $this->get_collections(),
			'collection_collectables' => $this->get_collection_collectables(),
			'collection_items' => $this->get_collection_items(),
			'collection_item_collectables' => $this->get_collection_item_collectables(),
			'ingredients' => $this->get_ingredients(),
			'ingredient_parts' => $this->get_ingredient_parts(),
			'ingredient_qualities' => $this->get_ingredient_qualities(),
			'ingredient_types' => $this->get_ingredient_types(),
		);
		
		return $data;
	}
	

	
	
	// Get Collection Data  Text
	// An experiment to construct the data from the database for the first time
	
	function get_collection_data_text() {
		$query = "
			SELECT c.NAME AS Collection, g.NAME AS `Group`, it.NAME AS Item, i.NAME AS Ingredient, p.NAME AS Part, q.NAME AS Quality, ci.Quantity 
			FROM " . $this->wpdb->collection_item_collectables . " AS ci
			LEFT JOIN " . $this->wpdb->collection_items . " AS it ON ci.Item = it.ID 
			LEFT JOIN " . $this->wpdb->collection_groups . " AS g ON it.`Group` = g.ID 
			LEFT JOIN " . $this->wpdb->collections . " AS c ON g.Collection = c.ID 
			LEFT JOIN " . $this->wpdb->collection_locations . " AS l ON c.Location = l.ID 
			LEFT JOIN " . $this->wpdb->ingredients . " AS i ON ci.Ingredient = i.ID 
			LEFT JOIN " . $this->wpdb->ingredient_parts . " AS p ON ci.Part = p.ID 
			LEFT JOIN " . $this->wpdb->ingredient_qualities . " AS q ON ci.Quality = q.ID 
			ORDER BY ci.ID ASC
		";
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return $results;		
	}
	
	
	
	
	
	// Get Ingredients
	
	function get_ingredients() {
		$query = "
			SELECT * FROM " . $this->wpdb->ingredients . " 
			WHERE 1 
		";
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return (!is_array($results) || empty($results)) ? FALSE : $results; 
	}
	


	// Get Ingredients Parts
	
	function get_ingredient_parts() {
		$query = "
			SELECT * FROM " . $this->wpdb->ingredient_parts . " 
			WHERE 1 
		";
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return (!is_array($results) || empty($results)) ? FALSE : $results; 
	}
	


	// Get Ingredient Qualities
	
	function get_ingredient_qualities() {
		$query = "
			SELECT * FROM " . $this->wpdb->ingredient_qualities . " 
			WHERE 1 
		";
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return (!is_array($results) || empty($results)) ? FALSE : $results; 
	}
	


	// Get Ingredient Types
	
	function get_ingredient_types() {
		$query = "
			SELECT * FROM " . $this->wpdb->ingredient_types . " 
			WHERE 1 
		";
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return (!is_array($results) || empty($results)) ? FALSE : $results; 
	}
	
	
	
	
	// Get Random Tips
	
	function get_tips() {
		$query = "SELECT * FROM " . $this->wpdb->tips . " WHERE 1";
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return (!is_array($results) || empty($results)) ? FALSE : $results; 
	}
	
	
	
	
	// PORTAL
	
	// Get Portal Data
	
	function get_portal_data() {
		$data = $this->get_collection_data();
		
		$data["player_game_collected"]  = $this->get_player_game_collected();
		$data["player_game_items"]  = $this->get_player_game_items();
		
		return $data;
	}
	
	
	
	
	
	
	




	////////////////////////////////////////////////////////////////////////////////////////////////
	// MAKERS


	// Make Player Name
	
	function make_player_name($player = array()) {
		if (empty($player) || !is_array($player))
			return FALSE;
		
		if (!isset($player["FirstName"]) && !isset($player["Surname"]) && !isset($player["Name"]))
			return FALSE;
		
		$wholename = (isset($player["Name"])) ? $player["Name"] : FALSE;
		$firstname = (isset($player["FirstName"])) ? $player["FirstName"] : FALSE;
		$lastname = (isset($player["Surname"])) ? $player["Surname"] : FALSE;
		
		$name = FALSE;
		
		if (!empty($firstname) && !empty($lastname))
			$name = $firstname . " " . $lastname;
		
		elseif (!empty($wholename)) 
			$name = $wholename;
		
		if (isset($player["AccountCode"]) && ($name == $player["AccountCode"]))
			$name = $player["AccountCode"] . " - [ No Name ]";
		
		return $name;
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



	// Make and return gender based on number
	
	function make_gender($gender_id = 0) {
		return (isset($this->genders[$gender_id])) ? $this->genders[$gender_id] : FALSE;
	}
	
	
	// Make Merchant Status
	
	function make_merchant_status($status_id = FALSE) {
		return (is_numeric($status_id) && isset($this->merchant_statuses[$status_id])) ? $this->merchant_statuses[$status_id] : FALSE;
	}
			
	
	
	
	
	
	
	
	
	
	
	
	////////////////////////////////////////////
	// DO ACTIONS
	
	
	// MAINTENANCE
	
	// transactions
	
	// Archive transactions by Merchant
	
	function archive_merchant_transactions($merchant_ids = array()) {
		if (empty($merchant_ids) || !is_array($merchant_ids))
			return FALSE;
		
		$merchant_ids = $this->flatten($merchant_ids);
		$table_columns = "MerchantID,DateOfSale,PlayerCounter,ProductCode,Quantity,TimeOfSale,SalePrice,CostPrice,SellPrice,transactionsPerson,Location,transactionsCategory,Notes,PointsAllocated,DatePointsCancelled,PointsUsed,CardNo,PK,RecId,OrgLocation,DeptNo,BatchNo,DateCreated";
		$where = (sizeof($merchant_ids) > 1) ? "IN (" . implode(",", $merchant_ids) . ")" : "= " . $merchant_ids[0];		
		
		$query = "
			REPLACE INTO " . $this->wpdb->transactions_archive . " (" . $table_columns . ") 
			SELECT " . $table_columns . "
			FROM rdr2_transactions
			WHERE MerchantID " . $where . "
		";
		
		//echo $query; die();
		
		return $this->wpdb->query($query);
	}

	
	
	// Delete Merchant transactions

	function delete_merchant_transactions($merchant_ids = array()) {
		if (empty($merchant_ids) || !is_array($merchant_ids) || sizeof($merchant_ids) < 1)
			return FALSE;
		
		$query = "DELETE FROM " . $this->wpdb->transactions . " WHERE MerchantID IN (" . implode(",", $merchant_ids) . ")";

		return $this->wpdb->query($query);
	}


	function archive_null_date_transactions() {
		
		$query = "
		INSERT INTO rdr2_transactions_archive 
		SELECT * FROM rdr2_transactions 
		WHERE DateOfSale IS NULL";
		
		$query2 = "
		DELETE FROM rdr2_transactions 
		WHERE DateOfSale IS NULL
		";
	}












	///////////////////////////////
	// SETTERS

	// Create New Player
	
	function create_new_player($user_id = FALSE) {
		if (!$user_id || !is_numeric($user_id)) 
			return FALSE;
		
		return $this->wpdb->insert( $this->wpdb->players, array("WP_User_ID" => $user_id), array("%d"));
	}
	

	
	// Set Player's Active Game
	function set_player_active_game($game_id = FALSE, $player_id = FALSE) {
		if (!$game_id)
			return FALSE;
		
		if (!$player_id && $this->player_id)
			$player_id = $this->player_id;
		
		if (!$player_id)
			return FALSE;
		
		return $this->wpdb->update($this->wpdb->players, array("CurrentGame" => $game_id), array("ID" => $player_id), array("%d"));
	}

	
	// Create Player Game
	
	function create_player_game($player_id = FALSE) {
		if (!$player_id && $this->player_id)
			$player_id = $this->player_id;
		
		if (!$player_id)
			return FALSE;
		
		$game_id = $this->wpdb->insert( $this->wpdb->player_games, array("Player" => $player_id), array("%d"));
		
		if (!$game_id)
			return FALSE;
		
		// Make new Game the Current Active Game for this Player
		$this->set_player_active_game($game_id, $player_id);
		
		return TRUE;
	}



	// Update Player Game Collectables

	function update_player_game_collectables($game_id = FALSE, $collectables = FALSE, $collected = FALSE) {		
		if (!$game_id) 
			$game_id = $this->game_id;
		
		if (!$game_id) 
			return FALSE;
		
		if (!$collectables || !is_array($collectables)) 
			return false;		
		
		$collectable_counts = array();
		
		foreach ($collectables as $collectable) 
			$collectable_counts[$collectable] = (isset($collectable_counts[$collectable])) ? $collectable_counts[$collectable] + 1 : 1;
		
		$collectables_unique = array();
		
		foreach ($collectable_counts as $id => $quantity) 
			$collectables_unique[] = array("Collectable" => $id, "Collected" => $quantity);
		
		if (!$collectables_unique)
			return FALSE;
		
		foreach ($collectables_unique as $n => $collectable) {
			$found = FALSE;
			
			foreach ($collected as $i => $row) {				
				if ($row["Collectable"] == $collectable["Collectable"]) {
					$found = $row;
					break;
				}
			}
			
			if ($found !== FALSE) {				
				if ($found["Collected"] !== $collectable["Collected"] )  {
					$update_id = $this->wpdb->update( $this->wpdb->player_game_collectables, array("Collected" => $collectable["Collected"]), array("Game" => $game_id, "Collectable" => $collectable["Collectable"]), "%d", "%d");
				}
			}
			else {
				$insert_id = $this->wpdb->insert( $this->wpdb->player_game_collectables, array("Game" => $game_id, "Collectable" => $collectable["Collectable"], "Collected" => $collectable["Collected"]), "%d");	
			}
			
		}
		
		return $collectables_unique;
	}



	// Update Player Game Collectable

	function update_player_game_collectable($game_id = FALSE, $collectable = FALSE, $quantity = FALSE) {		
		if (!$game_id) 
			$game_id = $this->game_id;
		
		if (!$game_id) {
			//echo "No game ID received!\n"; 
			return FALSE;
		}
		
		if (!$collectable) {
			//echo "No collectable ID received!\n";
			return false;
		}
		
		$data = array("Collected" => $quantity);
		$where = array("Game" => $game_id, "Collectable" => $collectable);
		$update_id = $this->wpdb->update( $this->wpdb->player_game_collectables, $data, $where, "%d", "%d");
		$result = ($update_id !== '0') ? $update_id : FALSE;
		
		return $result;
	}



	// Insert Player Game Collectable

	function insert_player_game_collectable($game_id = FALSE, $collectable = FALSE, $quantity = FALSE) {		
		if (!$game_id) 
			$game_id = $this->game_id;
		
		if (!$game_id) {
			//echo "No game ID received!\n"; 
			return FALSE;
		}
		
		if (!$collectable) {
			//echo "No collectable ID received!\n";
			return false;
		}
		
		$data = array("Game" => $game_id, "Collectable" => $collectable, "Collected" => $quantity);
		$insert_id = $this->wpdb->insert( $this->wpdb->player_game_collectables, $data, "%d");	
		$result = ($insert_id !== '0') ? $insert_id : FALSE;
		
		return $result;
	}



	// Update Player Game Item Collectable
	function update_player_game_item_collectable($game_id = FALSE, $itemcollectable = FALSE, $quantity = FALSE) {		
		if (!$game_id) 
			$game_id = $this->game_id;
		
		if (!$game_id) {
			//echo "No game ID received!\n"; 
			return FALSE;
		}
		
		if (!$itemcollectable) {
			//echo "No collectable ID received!\n";
			return false;
		}
		
		$data = array("Quantity" => $quantity);
		$where = array("Game" => $game_id, "ItemCollectable" => $itemcollectable);
		$update_id = $this->wpdb->update( $this->wpdb->player_game_item_collectables, $data, $where, "%d", "%d");
		$result = ($update_id !== '0') ? $update_id : FALSE;
		
		return $result;	
	}



	// Insert Player Game Item Collectable
	function insert_player_game_item_collectable($game_id = FALSE, $itemcollectable = FALSE, $quantity = FALSE) {		
		if (!$game_id) 
			$game_id = $this->game_id;
		
		if (!$game_id) {
			//echo "No game ID received!\n"; 
			return FALSE;
		}
		
		if (!$itemcollectable) {
			//echo "No collectable ID received!\n";
			return false;
		}
		
		$data = array("Game" => $game_id, "ItemCollectable" => $itemcollectable, "Quantity" => $quantity);
		
		$insert_id = $this->wpdb->insert( $this->wpdb->player_game_item_collectables, $data, "%d");	
		
		$result = ($insert_id !== '0') ? $insert_id : FALSE;
		
		return $result;
	}




	// Update Player Game Item 
	function update_player_game_item($game_id = FALSE, $item = FALSE, $acquired = FALSE) {		
		if (!$game_id) 
			$game_id = $this->game_id;
		
		if (!$game_id) {
			//echo "No game ID received!\n"; 
			return FALSE;
		}
		
		if (!$item) {
			//echo "No collectable ID received!\n";
			return false;
		}
		
		$data = array("Acquired" => $acquired);
		$where = array("Game" => $game_id, "Item" => $item);
		$update_id = $this->wpdb->update( $this->wpdb->player_game_items, $data, $where, "%d", "%d");
		$result = ($update_id !== '0') ? $update_id : FALSE;
		
		return $result;	
	}



	// Insert Player Game Item
	function insert_player_game_item($game_id = FALSE, $item = FALSE, $acquired = 0) {		
		if (!$game_id) 
			$game_id = $this->game_id;
		
		if (!$game_id) {
			//echo "No game ID received!\n"; 
			return FALSE;
		}
		
		if (!$item) {
			//echo "No collectable ID received!\n";
			return false;
		}
		
		$data = array("Game" => $game_id, "Item" => $item, "Acquired" => $acquired);
		
		$insert_id = $this->wpdb->insert( $this->wpdb->player_game_items, $data, "%d");	
		
		$result = ($insert_id !== '0') ? $insert_id : FALSE;
		
		return $result;
	}







	////////////////////////////////////////////////////////////////////////////////////////////
	// HELPER FUNCTIONS
	
	
	// Convert an Object's Variables to an Array
	
	function object_to_array($d = FALSE) {
		// Gets the properties of the given object
		// with get_object_vars function
		if (is_object($d)) 
			$d = get_object_vars($d);
	
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		if (is_array($d)) 
			return array_map(__FUNCTION__, $d);
	
		// Return array
		else 
			return $d;
	}
	

	// Flatten
	// Turns multi-dimensional array into a flat array
	function flatten(array $array) {
		$ret_array = array();
		foreach(new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $value)
			$ret_array[] = $value;
		return $ret_array;
	}


	// Get Last 4 Quarters
	
	function get_quarters() {
		$date_ranges = array();
		$date_ranges[] = $this->get_quarter(1);
		$date_ranges[] = $this->get_quarter(2);
		$date_ranges[] = $this->get_quarter(3);
		$date_ranges[] = $this->get_quarter(4);
		return $date_ranges;
	}
	


	// Get Quarter from Date
	
	function get_quarter_from_date($date = FALSE) {
		if (empty($date)) 
			$date = date("Y-m-d");
		
		$quarter = $this->get_quarter_from_timestamp(strtotime($date));
		
		return $quarter;
	}



	/**
	 * Return the quarter for a timestamp.
	 * @returns integer
	 */
	function get_quarter_from_timestamp($ts) {
	   return ceil(date('n', $ts) / 3);
	}





	/**
	 * gets quarter start/end date either for current date or previous according to $i
	 * i.e. $i=0 => current quarter, $i=1 => 1st full quarter, $i=n => n full quarter
	 * @param  $i
	 * @return array();
	 */
	function get_quarter($i = 0) {
		$y = date('Y');
		$m = date('m');
		
		if ($i > 0) {
			for ($x = 0; $x < $i; $x++) {
				if ($m <= 3) { $y--; }
				$diff = $m % 3;
				$m = ($diff > 0) ? $m - $diff:$m-3;
				if($m == 0) { $m = 12; }
			}
		}
		
		switch($m) {
			case $m >= 1 && $m <= 3:
				$start = $y.'-01-01 00:00:01';
				$end = $y.'-03-31 00:00:00';
				break;
			case $m >= 4 && $m <= 6:
				$start = $y.'-04-01 00:00:01';
				$end = $y.'-06-30 00:00:00';
				break;
			case $m >= 7 && $m <= 9:
				$start = $y.'-07-01 00:00:01';
				$end = $y.'-09-30 00:00:00';
				break;
			case $m >= 10 && $m <= 12:
				$start = $y.'-10-01 00:00:01';
				$end = $y.'-12-31 00:00:00';
		    		break;
		}
		return array(
			'start' => $start,
			'end' => $end,
			'start_nix' => strtotime($start),
			'end_nix' => strtotime($end)							
		);
	}

	
	
}


function searchForId($id, $array, $arr_key) {
	foreach ($array as $key => $val) {
		if ($val[$arr_key] === $id) {
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

function sayhello($also = FALSE) {
	echo "<h1>Hello, world!</h1>\n";
	if ($also) {
		if (is_array($also)) "<pre>" . print_r($also) . "</pre>\n";
		else echo "<p>" . $also . "</p>\n";
	}
}

// Echo Output in a Comment

function comment_echo($data = FALSE) {
	echo "\n <!-- \n";
	if ($data) print_r($data);
	else echo "Nothing to show here...";
	echo "\n //--> \n";	
}