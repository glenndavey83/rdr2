<?php

// RDR2
// CLASS
// WORDPRESS PLUGIN


class RDR2 {





















	///////////////////////////////////////////////////////////////
	// GETTERS

	



	

	
	
	









	
	
	





	
	
	


	
	
	// Get Collection Data  Text
	// An experiment to construct the data from the database for the first time
	
	function get_collection_data_text() {
		$query = "
			SELECT c.NAME AS Collection, g.NAME AS `Group`, it.NAME AS Item, i.NAME AS Ingredient, p.NAME AS Part, q.NAME AS Quality, ci.Quantity 
			FROM " . $this->wpdb->craftable_collectables . " AS ci
			LEFT JOIN " . $this->wpdb->craftables . " AS it ON ci.Item = it.ID 
			LEFT JOIN " . $this->wpdb->craftable_groups . " AS g ON it.`Group` = g.ID 
			LEFT JOIN " . $this->wpdb->craftable_categories . " AS c ON g.Category = c.ID 
			LEFT JOIN " . $this->wpdb->craftable_sources . " AS l ON c.Location = l.ID 
			LEFT JOIN " . $this->wpdb->ingredients . " AS i ON ci.Ingredient = i.ID 
			LEFT JOIN " . $this->wpdb->ingredient_parts . " AS p ON ci.Part = p.ID 
			LEFT JOIN " . $this->wpdb->ingredient_qualities . " AS q ON ci.Quality = q.ID 
			ORDER BY ci.ID ASC
		";
		
		$results = $this->wpdb->get_results($query, ARRAY_A);
		
		return $results;		
	}
	
	
	
	
	

	



	



	



	
	
	

	
	
	
	
	// PORTAL
	
	// Get Portal Data
	
	function get_portal_data() {
		$data = $this->get_collection_data();
		
		$data["player_game_collected"]  = $this->get_player_game_collected();
		$data["player_game_crafted"]  = $this->get_player_game_crafted();
		
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












	//////////////////////////////////////////////////////////////
	// SETTERS

	
	// Create New Player
	
	function create_new_player( $user_id = FALSE ) {
		if ( ! $user_id || ! is_numeric( $user_id ) ) 
			return FALSE;
		
		return $this->wpdb->insert( $this->wpdb->players, array( 'WP_User_ID' => $user_id ), array( "%d" ) );
	}
	

	
	// Set Player's Active Game
	
	function set_player_active_game( $game_id = FALSE, $player_id = FALSE ) {
		if ( ! $game_id )
			return FALSE;
		
		if ( ! $player_id && $this->player_id )
			$player_id = $this->player_id;
		
		if ( ! $player_id )
			return FALSE;
		
		return $this->wpdb->update( $this->wpdb->players, array( 'CurrentGame' => $game_id ), array( 'ID' => $player_id ), '%d' );
	}


	
	// Create Player Game
	
	function create_player_game( $player_id = FALSE ) {
		if ( ! $player_id && $this->player_id )
			$player_id = $this->player_id;
		
		if ( ! $player_id )
			return FALSE;
		
		$game_id = $this->wpdb->insert( $this->wpdb->player_games, array( 'Player' => $player_id ), array( '%d' ) );
		
		if ( ! $game_id )
			return FALSE;
		
		// Make new Game the Current Active Game for this Player
		$this->set_player_active_game( $game_id, $player_id );
		
		return TRUE;
	}



	// Update Player Game Collectables
	// Is this still used?
	/*
	function update_player_game_collected( $game_id = FALSE, $collectables = FALSE, $collected = FALSE ) {		
		if ( ! $game_id ) 
			$game_id = $this->game_id;
		
		if ( ! $game_id ) 
			return FALSE;
		
		if ( ! $collectables || ! is_array( $collectables ) ) 
			return false;		
		
		$collectable_counts = array();
		
		foreach ( $collectables as $collectable ) 
			$collectable_counts[ $collectable ] = ( isset( $collectable_counts[ $collectable ] ) ) ? $collectable_counts[ $collectable ] + 1 : 1;
		
		$collectables_unique = array();
		
		foreach ( $collectable_counts as $id => $quantity ) 
			$collectables_unique[] = array( 'Collectable' => $id, 'Collected' => $quantity );
		
		if ( ! $collectables_unique )
			return FALSE;
		
		// For each Unique Collectable
		foreach ( $collectables_unique as $n => $collectable ) {
			$found = FALSE;
			
			foreach ( $collected as $i => $row ) {				
				if ( $row[ 'Collectable' ] == $collectable[ 'Collectable' ] ) {
					$found = $row;
					break;
				}
			}
			
			if ( $found !== FALSE ) {				
				if ( $found[ 'Collected' ] !== $collectable[ 'Collected' ] )  {
					$update_id = $this->wpdb->update( $this->wpdb->player_game_collected, array( 'Collected' => $collectable[ 'Collected' ] ), array( 'Game' => $game_id, 'Collectable' => $collectable[ 'Collectable' ] ), '%d', '%d' );
				}
			}
			else {
				$insert_id = $this->wpdb->insert( $this->wpdb->player_game_collected, array( 'Game' => $game_id, 'Collectable' => $collectable[ 'Collectable' ], 'Collected' => $collectable[ 'Collected'] ), '%d' ); 
			}
			
		}
		
		// Return unique Player Game Collectables
		return $collectables_unique;
	}
	*/


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



	// Insert Player Game Collected Collectable

	function insert_player_game_collectable( $game_id = FALSE, $collectable = FALSE, $quantity = 1 ) {		
		if ( ! $game_id ) 
			$game_id = $this->game_id;
		
		if ( ! $game_id ) {
			//echo "No game ID received!\n"; 
			return FALSE;
		}
		
		if ( ! $collectable ) {
			//echo "No Collectable ID received!\n";
			return false;
		}
		
		$data = array( 'Game' => $game_id, 'Collectable' => $collectable, 'Collected' => $quantity );
		
		$insert_id = $this->wpdb->insert( $this->wpdb->player_game_collected, $data, '%d' );	
		
		$result = ( $insert_id !== '0' ) ? $insert_id : FALSE;
		
		return $result;
	}



	// Update Player Game Submitted Collectables
	
	function update_player_game_item_collectable( $game_id = FALSE, $collectable = FALSE, $quantity = FALSE ) {		
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
		
		if ( $quantity === FALSE || ! is_numeric( $quantity ) ) {
			//echo "No valid quantity received!\n";
			return false;
		}
		
		$data = array( 'Quantity' => $quantity );
		
		$where = array( 'Game' => $game_id, 'Collectable' => $collectable );
		
		$update_id = $this->wpdb->update( $this->wpdb->player_game_submitted, $data, $where, '%d', '%d' );
		
		$result = ( $update_id !== '0' ) ? $update_id : FALSE;
		
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
		
		$data = array( 'Game' => $game_id, 'Collectable' => $collectable, 'Quantity' => $quantity );
		
		$insert_id = $this->wpdb->insert( $this->wpdb->player_game_submitted, $data, '%d' );	
		
		$result = ( $insert_id !== '0' ) ? $insert_id : FALSE; 
		
		return $result;
	}




	// Update Player Game Craftable 
	
	function update_player_game_craftable( $game_id = FALSE, $craftable = FALSE, $acquired = FALSE ) {		
		if ( ! $game_id ) 
			$game_id = $this->game_id;
		
		// ABORT if no Game ID for updating
		if ( ! $game_id ) {
			//echo "No Game ID received!\n"; 
			return FALSE;
		}
		
		// ABORT if no Craftable ID for updating
		if ( ! $craftable ) {
			//echo "No Craftable ID received!\n";
			return false;
		}
		
		// ABORT if no Acquired boolean for updating
		if ( ! $acquired ) {
			//echo "No Acquired boolean value received!\n";
			return false;
		}
		
		$data = array( 'Acquired' => $acquired ); 
		
		$where = array( 'Game' => $game_id, 'Craftable' => $craftable );
		
		$update_id = $this->wpdb->update( $this->wpdb->player_game_crafted, $data, $where, '%d', '%d' );
		
		$result = ( $update_id !== '0' ) ? $update_id : FALSE;
		
		return $result;	
	}



	// Insert Player Game Item
	function insert_player_game_craftable( $game_id = FALSE, $craftable = FALSE, $acquired = 1 ) {		
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