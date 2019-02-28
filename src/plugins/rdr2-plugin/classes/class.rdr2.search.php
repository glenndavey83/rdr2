<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////
// SEARCH OBJECT
// For '/search' page

class RDR2_Search extends RDR2 {

	var $players, $merchants;
	var $columns, $vars, $terms, $data, $results, $clean, $custom_order, $nodata;
	var $sql_from, $results_per_page, $ids, $new_search, $start_index, $result_count, $random, $batch, $archived;
	var $limit, $limit_sql, $what, $sql, $wpdb, $order_sql, $orderby, $order;

	function __construct($vars = FALSE) {
		global $wpdb;
		//parent::__construct($this->terms);
			
		// Default variables
		$this->wpdb = $wpdb;
		$this->results_per_page = 20;
		
		$this->ids = FALSE;
		$this->new_search = FALSE;
		$this->start_index = 0;
		$this->limit_sql = FALSE;
		$this->what = "players";
		$this->orderby = FALSE;
		$this->order = "ASC";
		$this->players = TRUE;
		$this->show_queries = FALSE;
		$this->stores = TRUE;
		$this->columns = array();
		$this->nodata = FALSE;
		$this->archived = FALSE;		
		$this->time_display_format = '%I:%i %p';
		$this->date_display_format = '%e/%m/%Y';
		
		// Now load variables from parent
		$this->terms = $vars;
		
		$this->extract_terms($this->terms);
		
		// New SQL Object
		$this->sql = new sql;
		
		// Tables
		$this->tables = array(
			"players" => array(
				"primary_column_name" => "AccountCode",
				"abbrev" => "c",
				"orderby" => "DateCreated",
				"order" => "DESC",
				"orderby_options" => array("AccountCode", "MerchantID", "StatusID", "Name", "FirstName", "Surname", "DateCreated", "MemberNumber", "CardBarcodeNo", "Location", "StateID", "SuburbID", "DateOfLastSale"),
			),
			"merchants" => array(
				"primary_column_name" => "ID",
				"abbrev" => "m",
				"orderby" => "ID",
				"order" => "DESC",
				"orderby_options" => array("ID", "Name", "StatusID", "SiteGroupID", "InceptionDate", "Stores"),
			),
			"merchant_stores" => array(
				"primary_column_name" => "ID",
				"abbrev" => "s",
				"orderby" => "MerchantID",
				"order" => "ASC",
				"orderby_options" => array("MerchantID", "ID", "Name", "Location", "StatusID", "SiteGroupID"),
			),
			"transactions" => array(
				"primary_column_name" => "ID",
				"abbrev" => "t",
				"orderby" => "DateOfSale",
				"order" => "DESC",
				"orderby_options" => array("MerchantID", "ProductCode", "CardNo", "Name", "SalePrice", "PointsAllocated", "DateOfSale"),
			),
		);

		foreach ($this->tables as $table => $attrs) {
			$this->tables[$table]["prefix"] = $attrs["abbrev"] . ".";
			$this->tables[$table]["primary_column"] = $this->tables[$table]["prefix"] . $attrs["primary_column_name"];
		}
		
		$this->sql_from = " FROM " . $this->wpdb->players . " " . $this->tables["players"]["abbrev"];
	}
	
	
	// Get Search Results
	/*
	function get_search_results($count = FALSE) {
		return ($this->what == "merchants") ? $this->get_merchant_search_results() : $this->get_player_search_results();	
	}
	*/
	
	
	
	// Get Search Results Data
	
	function get_search_results_data($count = FALSE) {
		
		switch ($this->what) {
			case "transactions" : 
				return $this->get_transactions_search_results($count);
			break;
			case "stores" : 
				return $this->get_merchant_store_search_results($count);
			break;
			case "merchants" : 
				return $this->get_merchant_search_results($count);
			break;
			case 'players' :
				return $this->get_player_search_results($count);
			break;
			default :
				return FALSE;
			break;
		}
	}
	
	
	
	// Get Search Results
	
	function get_search_results() {
		
		// Don't do the search if we already have the search results stored
		// Note: you can call the get_search_results() data again by destroying the saved data (set $search->results = FALSE)
		
		if (!$this->data) {
		
		// First we need a LIST of IDS
		
			// 1. Get the list from the database 
			if (!$this->results && !$this->ids) {
				$this->ids = $this->get_search_results_data(TRUE);
				//$this->results = $this->ids = $this->flatten($this->results);
				//$this->data["params"] = $this->interpret_search_params();
			}
			
			// -- OR --
			
			// 2. Get the list of ids passed to the class
			elseif ($this->ids) {
				$this->no_order = TRUE;
			}
			
			
			
			// If no IDs exit the search
			if (empty($this->ids))
				return FALSE;
			
			
			// REMOVED SESSION SEARCH IDS CODE
		

			// Count results
			$this->data["total"] = 
			$this->data["result_count"] = 
			$this->data["results_count"] = 
			$this->data["result_total"] = 
			$this->data["results_total"] = $this->get_result_count();
			
			// If we have Player IDs for which to fetch data ... 
			// and we're allowed to fetch data ...
			
			if (!empty($this->ids) && !$this->nodata) {
				
				// Pagination required...
				
				if (!empty($this->results_per_page) && ($this->result_count > $this->results_per_page)) {
					$this->data["results_per_page"] = $this->results_per_page;
					
					// Get Player Key from ID
					//if ($this->id && !$this->player_key) 
					//	$this->player_key = $this->get_player_key($this->id, $this->results);
					
					// Pagination data
					$this->data["pagination"] = $this->get_pagination();
					
					// Create LIMIT SQL
					$this->batch = $this->paginate->get_batch();
					$this->limit_sql = " LIMIT " . $this->batch;
					$this->start_index = $this->paginate->get_start_index();
					$this->end_index = (isset($this->paginate->end_player_index)) ? $this->paginate->end_player_index : FALSE;
				}
				
				elseif ($this->limit) 
					$this->batch = $this->limit; 
				
				//print_r($this->ids);
				
				// If IDs are an Object, convert to an Array
				if (is_object($this->ids)) 
					$this->ids = $this->objectToArray($this->ids);
				
				
				if (is_array($this->ids)) {
					
					// If Random is set shuffle Search IDs	
					if ($this->random) 
						shuffle($this->ids);
					
					// If Paginated crop results to current page
					if ($this->batch && sizeof($this->ids) > $this->batch) 
						$this->ids = array_slice($this->ids, $this->start_index, $this->batch);
					
					// Full results
					$this->data["results"] = $this->results = $this->get_search_results_data(FALSE);
					$this->data["query"] = $this->query;
					$this->data["ids"] = $this->ids;
				}
				else
					return $this->ids;
				
			} // IDs
			
		} // !$this->data
		
		//print_r($this->results); die();
		return $this->results;
	}





















	//////////////////////////////////////////////////////////////////
	// PLAYERS

	
	// Get Player Results
	
	function get_player_search_results($count = FALSE) {
		$this->table = "players";
		$this->filter = new filter; 
		
		// No automatic "WHERE"
		$this->filter->where = TRUE;
		$this->sql_join = FALSE;
		
		$this->default_orderby = $this->tables[$this->table]["orderby"];
		$this->default_order = $this->tables[$this->table]["order"];
		$this->orderby_options = $this->tables[$this->table]["orderby_options"];
		$this->orderby = (in_array($this->orderby, $this->orderby_options)) ? $this->orderby : FALSE;
		
		$this->primary_column_name = $this->tables[$this->table]["primary_column_name"];
		$this->primary_abbrev = $this->tables[$this->table]["abbrev"];
		$this->primary_prefix = $this->tables[$this->table]["prefix"];
		$this->primary_column = $this->tables[$this->table]["primary_column"];
		
		$this->sql_from = " FROM " . $this->wpdb->players . " " . $this->primary_abbrev;
		
		// WHERE ...
		
		// Count total results
		if ($count) {
			
			$this->add_column($this->primary_column);
			
			if (!empty($this->count_limit) && empty($this->limit_sql)) 
				$this->limit_sql = "LIMIT " . $this->count_limit;
			
			// Filter incoming data
			$this->do_player_filter();
			
		} 
		
		
		// Get limited results from Search IDs
		if (!$count) { 
			$this->limit_sql = FALSE;			
			
			$this->add_columns(array(
				$this->primary_prefix . "Name", 
				$this->primary_prefix . "AccountCode", 
				$this->primary_prefix . "CardBarcodeNo", 
				$this->primary_prefix . "MerchantID", 
				$this->primary_prefix . "MemberNumber",  
				$this->primary_prefix . "FirstName", 
				$this->primary_prefix . "Surname", 
				$this->primary_prefix . "DateCreated", 
			));
			
			// Suburbs
			$this->add_column("su.Name AS Suburb");
			$this->add_join("LEFT JOIN " . $this->wpdb->suburbs . " su ON " . $this->primary_prefix . "SuburbID = su.ID");

			// State
			$this->add_column("st.Abbrev AS State");
			$this->add_join("LEFT JOIN " . $this->wpdb->states . " st ON " . $this->primary_prefix . "StateID = st.ID");
			
			$this->add_column("CONCAT(su.Name, ', ', st.Abbrev) AS Location");
			// LIMIT ... 
			// If no pagination set but limit set...
			//if (!empty($this->limit)) 
			//	$this->limit_sql = "LIMIT " . $this->limit;
			
			$this->filter->add($this->primary_column . " IN (" . implode(', ', $this->ids) . ")");
		}
		
		
		// ORDER ...
		$this->order_sql = FALSE;
		
		if ($count || !$this->random) : 
		
			// Set Defaults
			if (empty($this->orderby)) 
				$this->orderby = $this->default_orderby;
			
			if (empty($this->order)) 
				$this->order = $this->default_order;
			
			// Add Order filters
			if ($this->order_sql) 
				$this->order_sql .= ", ";
			
			if (!empty($this->orderby)) 
				$this->order_sql .= " " . $this->primary_prefix . $this->orderby . " ";
			
			if (!empty($this->order)) 
				$this->order_sql .= " " . strtoupper($this->order);
		endif;
		
		$this->order_sql = ($this->custom_order) ? $this->order_sql . ", " . $this->custom_order : $this->order_sql;

		// If order set, add "ORDER BY" to
		if ($this->order_sql) 
			$this->order_sql = " ORDER BY " . $this->order_sql;
		

		/////////////////////////
		// SEARCH Query
		
		if ($count) // Counting 
			$this->query = 
			$this->query1 = 
			$this->sql->select($this->show_columns()) . " " . $this->sql_from . " " . $this->sql_join . " " . $this->filter->show() . " " . $this->group_sql . " " . $this->order_sql . " " . $this->limit_sql . " ";
			
		else // Getting
			$this->query = 
			$this->query2 = 
			$this->sql->select($this->show_columns()) . " " . $this->sql_from . " " . $this->sql_join . " " . $this->filter->show() . " " . $this->order_sql . " " . $this->limit_sql . " ";
		
		
		//echo $this->query; //die();
		
		// Debug Queries
		//if ($this->show_queries) 
		//	sayhello($this->query); // print_r($_REQUEST); 
		
		// Get Data
		if ($count == TRUE) 
			$this->results = $this->wpdb->get_col($this->query);
		
		else
			$this->results = $this->wpdb->get_results($this->query, ARRAY_A);
		
		
		// Return results
		return $this->results;
	}


	// Get Player Total

	function get_player_total($args = array()) {
		$this->table = "players";
		$this->filter = new filter;
		$this->filter->where = TRUE;
		$this->sql_join = FALSE;
		$this->primary_column_name = $this->tables[$this->table]["primary_column_name"];
		$this->primary_abbrev = $this->tables[$this->table]["abbrev"];
		$this->primary_prefix = $this->tables[$this->table]["prefix"];
		$this->primary_column = $this->tables[$this->table]["primary_column"];
		$this->add_column("COUNT(*) AS TotalPlayers");
		$this->sql_from = " FROM " . $this->wpdb->players . " " . $this->primary_abbrev;
		$this->do_player_filter();
		echo 
		$this->query = $this->sql->select($this->show_columns()) . " " . $this->sql_from . " " . $this->sql_join . " " . $this->filter->show() . " " . $this->group_sql . " " . $this->order_sql . " " . $this->limit_sql . " ";
		$this->result = $this->wpdb->get_var($this->query);
		return $this->result;
	}
	


	
	// FILTERS //////.......................
	// Create filters for each search term
	
	function do_player_filter() {
		$args = FALSE;
		
		if (isset($this->args))
			$args = $this->args;
		
		if ($this->clean == 1) 
			$this->filter->add($this->clean_where_player());

		// Handle arguments
		if (is_array($args) && !empty($args)) {
			
			//print_r($args);
			
			// Cycle through argument array
			foreach ($args as $arg => $value) {
				
				// If Card Numbers have been passed
				switch ($arg) {
					
					case "first_name" :
						if (!empty($value))
							$this->filter->add($this->primary_prefix . "FirstName = '" . $value . "'");
					break;
					
					case "last_name" :
						if (!empty($value))
							$this->filter->add($this->primary_prefix . "Surname = '" . $value . "'");
					break;
					
					case "state" :
						if (is_numeric($value))
							$this->filter->add($this->primary_prefix . "StateID = " . $value);
					break;

					case "suburb" :
						if (is_numeric($value))
							$this->filter->add($this->primary_prefix . "SuburbID = " . $value);
					break;
						
					case "status" : 				
						if (is_numeric($value))
							$this->filter->add($this->primary_prefix . "StatusID = " . $value);
					break;
					
					case "merchant_status" :
						if (!is_numeric($value))
							break;
						
						switch ($value) {
							
							// Only Players of Active Merchants
							
							case 1 : 
								
								// LEFT JOIN
								$this->add_join("LEFT JOIN " . $this->wpdb->merchants . " " . $this->tables["merchants"]["abbrev"] . " ON " . $this->primary_prefix . "MerchantID = " . $this->tables["merchants"]["primary_column"]);
								$this->filter->add($this->tables["merchants"]["prefix"] . "StatusID = 1");
																			
								// GROUP BY
								$this->group_sql = "GROUP BY " . $this->primary_column;
								
							break;
							case 2 : 
								// LEFT JOIN
								$this->add_join("LEFT JOIN " . $this->wpdb->merchants . " " . $this->tables["merchants"]["abbrev"] . " ON " . $this->primary_prefix . "MerchantID = " . $this->tables["merchants"]["primary_column"]);
								$this->filter->add($this->tables["merchants"]["prefix"] . "StatusID = 0");
																			
								// GROUP BY
								$this->group_sql = "GROUP BY " . $this->primary_column;
							break; 
							default : 
								
							break;
						}
						
					break;
					
					case "card_numbers" : 
					
						$card_numbers = $value;
						$comma = ",";
						
						if (empty($card_numbers))
							break;
						
						// If we have an array of card numbers
						if (is_array($card_numbers))
							$card_numbers = implode($comma, $card_numbers);
						
						// If we have a comma-separated string already
						if (strpos($card_numbers, $comma) !== FALSE)
							$this->filter->add($this->primary_column . " IN (" . $card_numbers . ")");
						else 
							// Add this one card number to the query
							$this->filter->add($this->primary_column . " = '" . $card_numbers . "'");
						
					break;
					
					case "MerchantID" : 
						
						$merchants_number = $value;
						
						if (empty($merchants_number))
							break;
						
						// Add this one Merchant to the query
						$this->filter->add("MerchantID = " . $merchants_number);
					break;
					
					case "sales" : 
						if (!is_numeric($value))
							break;
						
						switch ($value) {
							// Having Sales
							case 1 : 
								$this->add_join("LEFT JOIN " . $this->wpdb->transactions . " " . $this->tables["transactions"]["abbrev"] . " ON " . $this->primary_column . " = " . $this->tables["transactions"]["prefix"] . "CardNo");
								//$this->filter->add("COUNT(" . $this->tables["transactions"]["prefix"] . "CardNo) > 0");
								
								// GROUP BY
								$this->group_sql = "GROUP BY " . $this->primary_column . " HAVING COUNT(" . $this->tables["transactions"]["prefix"] . "CardNo) > 0";
							break;
							
							// Having No Sales
							case 2 : 
								
							break;
						}
						
					break;
				}
			}
		}
	}





















	//////////////////////////////////////////////////////////////////
	// MERCHANTS
	
	
	// Get Merchant Results
	
	function get_merchant_search_results($count = FALSE) {
		$this->table = "merchants";
		$this->filter = new filter;
		$this->filter->where = TRUE;
		$this->sql_join = FALSE;
		$this->group_sql = FALSE;
		
		$this->primary_column_name = $this->tables[$this->table]["primary_column_name"];
		$this->primary_abbrev = $this->tables[$this->table]["abbrev"];
		$this->primary_prefix = $this->tables[$this->table]["prefix"];
		$this->primary_column = $this->tables[$this->table]["primary_column"];
		
		$this->default_orderby = $this->tables[$this->table]["orderby"];
		$this->default_order = $this->tables[$this->table]["order"];
		$this->orderby_options = $this->tables[$this->table]["orderby_options"];
		$this->orderby = (in_array($this->orderby, $this->orderby_options)) ? $this->orderby : FALSE;
		
		$this->sql_from = " FROM " . $this->wpdb->merchants . " " . $this->primary_abbrev;
		
		// Order By
		if (!empty($this->orderby)) {
			switch ($this->orderby) {
				// Locations
				case "Stores" :
					$this->stores = TRUE;
					$this->orderby = FALSE;
					$this->order_sql = "COUNT(" . $this->tables["merchant_stores"]["prefix"] . "ID) " . strtoupper($this->order);
				break;
			
				case "Location" : 
					$this->orderby = FALSE;
					$this->order_sql = "CONCAT(" . $this->primary_prefix . "Suburb, ', ', " . $this->primary_prefix . "State) " . strtoupper($this->order);
				break;
				
				default :
					
				break;
			}
		}
		
		// Count total results
		if ($count) {

			$this->add_column($this->primary_column);
			
			if (!empty($this->count_limit) && empty($this->limit_sql)) 
				$this->limit_sql = "LIMIT " . $this->count_limit;			
							
			// Filter incoming data
			$this->do_merchant_filter();			
		
		}


		// Get limited results
		if (!$count) { 
			
			//$this->add_column($this->primary_prefix . $this->primary_column);

			$this->add_columns(array( 
				$this->primary_prefix . "Name", 
				$this->primary_prefix . "StatusID",
				$this->primary_prefix . "InceptionDate",
			));
			
			// Suburbs
			$this->add_column("su.Name AS Suburb");
			$this->add_join("LEFT JOIN " . $this->wpdb->suburbs . " su ON " . $this->primary_prefix . "SuburbID = su.ID");

			// State
			$this->add_column("st.Abbrev AS State");
			$this->add_join("LEFT JOIN " . $this->wpdb->states . " st ON " . $this->primary_prefix . "StateID = st.ID");
			
			$this->add_column("CONCAT(su.Name, ', ', st.Abbrev) AS Location");
			
			// Locations
			if ($this->stores) 
				$this->add_column("COUNT(" . $this->tables["merchant_stores"]["prefix"] . "ID) AS Stores");
			
			// Site Group	
			$this->add_column("g.Code AS " . "SiteGroup");
			$this->add_join("LEFT JOIN " . $this->wpdb->merchant_site_groups . " g ON " . $this->primary_prefix . "SiteGroupID = g.ID");
			
			$this->filter->add($this->primary_column . " IN (" . implode(', ', $this->ids) . ")");
		}
	
		
		// MERCHANT STORES
		
		if ($this->stores) {
			// LEFT JOIN
			$this->add_join("LEFT JOIN " . $this->wpdb->merchant_stores . " " . $this->tables["merchant_stores"]["abbrev"] . " ON " . $this->primary_column . " = " . $this->tables["merchant_stores"]["prefix"] . "MerchantID");
			// GROUP BY
			$this->group_sql = "GROUP BY " . $this->primary_column;		
		}
				
		
		
		// ORDER BY
		if ($count || !$this->random) {
					
			if (empty($this->orderby)) $this->orderby = $this->default_orderby;
			if (empty($this->order)) $this->order = $this->default_order;
			
			// Add Order filters
			if (!$this->order_sql) {
				// ORDER ...
				if ($this->custom_order) 
					$this->order_sql = $this->custom_order . ", ";
		
				if (!empty($this->orderby)) $this->order_sql .= " " . $this->primary_prefix . $this->orderby . " ";
				if (!empty($this->order)) $this->order_sql .= " " . strtoupper($this->order);
			}
		}
	
		
		// DO MERCHANT QUERY
		if ($count) $this->query = $this->query1 = $this->sql->select($this->show_columns()) . " " . $this->sql_from . " " . $this->sql_join . " " . $this->filter->show() . " " . $this->group_sql . " " . $this->show_order() . " " . $this->limit_sql . " ";
		else $this->query = $this->query2 = $this->sql->select($this->show_columns()) . " " . $this->sql_from . " " . $this->sql_join . " " . $this->filter->show() . " " . $this->group_sql . " " . $this->show_order() . " " . $this->limit_sql . " ";

		//if ($this->show_queries == "true") 
			//echo $this->query;
		
		// Get Data
		if ($count == TRUE)
			$this->results = $this->wpdb->get_col($this->query);
		else
			$this->results = $this->wpdb->get_results($this->query, ARRAY_A);
		
		// RETURN MERCHANT RESULTS
		return $this->results;
		
	} 
			
			
			
	// Merchant Filter	
		
	function do_merchant_filter() {
		$args = FALSE;
		
		if (isset($this->args))
			$args = $this->args;
		
		$this->filter->add($this->clean_where_merchant());
		
		// Handle arguments
		if (is_array($args) && !empty($args)) {
			
			// Cycle through argument array
			foreach ($args as $arg => $value) {
				switch ($arg) {
					
					case "hide_res" :
						if ($value !== '0')
							$this->filter->add($this->primary_prefix . "Status = 1");
					break;
					
					case "status" :
						if ($value == '2')
							$this->filter->add($this->primary_prefix . "StatusID = 0");
						if ($value == '1')
							$this->filter->add($this->primary_prefix . "StatusID = 1");
					break;
									
					case "search_id" : 
						if (empty($value))
							break;
						
						$this->filter->add($this->primary_column . " = " . $value);
					break;
				
				}
			}
		}
	}


















	//////////////////////////////////////////////////////////////////
	// MERCHANT LOCATIONS
	

	// Get Merchant Store Results
	
	function get_merchant_store_search_results($count = FALSE) {
		$this->table = "merchant_stores";
		$this->filter = new filter; 
		$this->filter->where = TRUE;
		$this->sql_join = FALSE;
		$this->default_orderby = $this->tables[$this->table]["orderby"];
		$this->default_order = $this->tables[$this->table]["order"];
		$this->orderby_options = $this->tables[$this->table]["orderby_options"];
		$this->primary_column_name = $this->tables[$this->table]["primary_column_name"];
		$this->primary_abbrev = $this->tables[$this->table]["abbrev"];
		$this->primary_prefix = $this->tables[$this->table]["prefix"];
		$this->primary_column = $this->tables[$this->table]["primary_column"];
		
		$this->sql_from = " FROM " . $this->wpdb->merchant_stores . " " . $this->primary_abbrev;
		//$this->columns = FALSE;
		
		// Locations
		switch ($this->orderby) {	
			case "Location" : 
				$this->orderby = FALSE;
				$this->order_sql = "CONCAT(" . $this->primary_prefix . "Suburb, ', ', " . $this->primary_prefix . "State) " . strtoupper($this->order);
			break;
			case "Name" :
				$this->orderby = FALSE;
				$this->order_sql = $this->primary_prefix . "Name";
			break;
			default :
				$this->orderby = (in_array($this->orderby, $this->orderby_options)) ? $this->orderby : FALSE;
			break;
		}
		
		
		// Count total results
		if ($count) {
			
			$this->add_column($this->primary_column);
			
			if (!empty($this->count_limit) && empty($this->limit_sql)) 
				$this->limit_sql = "LIMIT " . $this->count_limit;
			
			// Filter incoming data
			$this->do_merchant_store_filter();
			
		} 
		
		
		// Get limited results from Search IDs
		if (!$count) { 
			$this->limit_sql = FALSE;
			
			$this->add_columns(array(
				$this->primary_prefix . "ID", 
				$this->primary_prefix . "MerchantID", 
				$this->primary_prefix . "Name",     
				$this->primary_prefix . "GroupNo", 
				$this->primary_prefix . "NoOfTerms",  
				$this->primary_prefix . "TermNo", 
				$this->primary_prefix . "Status", 
				$this->primary_prefix . "SiteGroupID", 
				$this->primary_prefix . "TerminationDate", 
				$this->primary_prefix . "DateCreated",
			));


			// Suburbs
			$this->add_column("su.Name AS Suburb");
			$this->add_join("LEFT JOIN " . $this->wpdb->suburbs . " su ON " . $this->primary_prefix . "SuburbID = su.ID");

			// State
			$this->add_column("st.Abbrev AS State");
			$this->add_join("LEFT JOIN " . $this->wpdb->states . " st ON " . $this->primary_prefix . "StateID = st.ID");

			// State
			$this->add_column("p.Postcode AS Postcode");
			$this->add_join("LEFT JOIN " . $this->wpdb->postcodes . " p ON " . $this->primary_prefix . "PostcodeID = p.ID");

			// Site Group
			$this->add_column("g.Name AS SiteGroup");
			$this->add_join("LEFT JOIN " . $this->wpdb->merchant_site_groups . " g ON " . $this->primary_prefix . "SiteGroupID = g	.ID");

			
			$this->add_column("CONCAT(su.Name, ', ', st.Abbrev) AS Location");

			// LIMIT ... 
			// If no pagination set but limit set...
			//if (!empty($this->limit)) 
			//	$this->limit_sql = "LIMIT " . $this->limit;
			
			$this->filter->add($this->primary_column . " IN (" . implode(', ', $this->ids) . ")");
		}
		
		
		// ORDER ...
		
		if ($count || !$this->random) {
					
			if (empty($this->orderby)) 
				$this->orderby = $this->default_orderby;
			
			if (empty($this->order)) 
				$this->order = $this->default_order;
			
			// Add Order filters
			if (!$this->order_sql) {
				// ORDER ...
				if ($this->custom_order) 
					$this->order_sql = $this->custom_order . ", ";
		
				if (!empty($this->orderby)) $this->order_sql .= " " . $this->primary_prefix . $this->orderby . " ";
				if (!empty($this->order)) $this->order_sql .= " " . strtoupper($this->order);
			}
		}		
		

		/////////////////////////
		// SEARCH Query
		
		if ($count) // Counting 
			$this->query = 
			$this->query1 = 
			$this->sql->select($this->show_columns()) . " " . $this->sql_from . " " . $this->sql_join . " " . $this->filter->show() . " " . $this->show_order() . " " . $this->limit_sql . " ";
			
		else // Getting
			$this->query = 
			$this->query2 = 
			$this->sql->select($this->show_columns()) . " " . $this->sql_from . " " . $this->sql_join . " " . $this->filter->show() . " " . $this->show_order() . " " . $this->limit_sql . " ";
		
		
		//echo $this->query; die();
		
		// Debug Queries
		//if ($this->show_queries) 
		//	sayhello($this->query); // print_r($_REQUEST); 
		
		// Get Data
		if ($count == TRUE)
			$this->results = $this->wpdb->get_col($this->query);
		else
			$this->results = $this->wpdb->get_results($this->query, ARRAY_A);
		
		// Return results
		return $this->results;
	}


	
	// FILTERS //////.......................
	// Create filters for each search term
	
	function do_merchant_store_filter() {
		$args = FALSE;
		
		// Filter out Community Chest (4) and CRP (7) Merchants
		$this->filter->add('((SiteGroupID != 4 AND SiteGroupID != 7) OR SiteGroupID IS NULL)');
		
		
		if (isset($this->args))
			$args = $this->args;

		// Handle arguments
		if (is_array($args) && !empty($args)) {
			
			// Cycle through argument array
			foreach ($args as $arg => $value) {
				
				// If Card Numbers have been passed
				switch ($arg) {
					case "hide_res" :
						
						if ( !in_array($value, array('0', 0, FALSE)) )
							$this->filter->add("(Status != 'RES' OR Status = '')");
					break;
					
					case "id" : 
						if (empty($value))
							break;
						
						// Add this one Merchant to the query
						$this->filter->add("MerchantID = " . $value);
					break;
				}
			}
		}
	}






















	//////////////////////////////////////////////////////////////////
	// TRANSACTIONS
	
	
	
	// Get Transactions Table
	
	function get_transactions_table() {
		return ($this->archived === TRUE) ? $this->wpdb->transactions_archive : $this->wpdb->transactions;			
	}

	
	
	// Get Transactions Results
	
	function get_transactions_search_results($count = FALSE) {		
		$this->table = "transactions";
		$this->filter = new filter; 
		$this->filter->where = TRUE;
		$this->sql_join = FALSE;
		$this->default_orderby = $this->tables[$this->table]["orderby"];
		$this->default_order = $this->tables[$this->table]["order"];
		$this->orderby_options = $this->tables[$this->table]["orderby_options"];
		$this->primary_column_name = $this->tables[$this->table]["primary_column_name"];
		$this->primary_abbrev = $this->tables[$this->table]["abbrev"];
		$this->primary_prefix = $this->tables[$this->table]["prefix"];
		$this->primary_column = $this->tables[$this->table]["primary_column"];
		
		$this->sql_from = " FROM " . $this->get_transactions_table() . " " . $this->primary_abbrev;
		
		$this->orderby = (in_array($this->orderby, $this->orderby_options)) ? $this->orderby : FALSE;
		
		//$this->columns = FALSE;
		$this->players = TRUE;
		
		
		// Count total results
		
		if ($count) {
			
			$this->add_column($this->primary_column);			
			
			// Filter incoming data
			$this->do_transactions_filter();
			
			$count_query = "SELECT COUNT(*) " . $this->sql_from . " " . $this->filter->show();
			$this->result_count = $this->wpdb->get_var($count_query);
			
			if (!empty($this->count_limit) && empty($this->limit_sql)) 
				$this->limit_sql = "LIMIT " . $this->count_limit;
			elseif ($this->result_count > 0) {
				$this->data["pagination"] = $this->get_pagination();
				$this->batch = $this->paginate->get_batch();
				$this->start_index = $this->paginate->get_start_index();
				//$this->end_index = (isset($this->paginate->end_player_index)) ? $this->paginate->end_player_index : FALSE;
				$this->limit_sql = " LIMIT " . $this->start_index . ", " . $this->batch;
			}
			else {
				$this->limit_sql = "LIMIT " . $this->results_per_page;
			}
		} 
		
		
		// Get limited results from Search IDs
		
		if (!$count) { 
			$this->limit_sql = FALSE;
			
			$this->add_columns(array(
				//$this->primary_prefix . "SaleID", 
				$this->primary_prefix . "MerchantID", 
				"TIME_FORMAT(" . $this->primary_prefix . "DateOfSale, '" . $this->date_display_format . "') AS DateOfSale", 
				"TIME_FORMAT(CONCAT(" . $this->primary_prefix . "TimeOfSale, ':00:00'), '" . $this->time_display_format . "') AS TimeOfSale", 
				$this->primary_prefix . "SalePrice",
				$this->primary_prefix . "Location",
				$this->primary_prefix . "PointsAllocated", 
				$this->primary_prefix . "CardNo",
			));
			 
			// LIMIT ... 
			// If no pagination set but limit set...
			//if (!empty($this->limit)) 
			//	$this->limit_sql = "LIMIT " . $this->limit;

			// Transaction Data		
			//$this->add_columns(array("td.Notes AS Notes"));
			//$this->add_join("LEFT JOIN " . $this->wpdb->transactions_data . " td ON " . $this->primary_prefix . "ID = td.TransactionID");
			
						
			// Product Code			
			$this->add_column("p.Code AS ProductCode");
			$this->add_join("LEFT JOIN " . $this->wpdb->product_codes . " p ON " . $this->primary_prefix . "ProductCodeID = p.ID");
			
			// Players
			
			if ($this->players) {
				
				$this->add_column($this->tables["players"]["prefix"] . "Name");
				$this->add_column($this->tables["players"]["prefix"] . "FirstName");
				$this->add_column($this->tables["players"]["prefix"] . "Surname");
				
				// LEFT JOIN
				$this->add_join("LEFT JOIN " . $this->wpdb->players . " " . $this->tables["players"]["abbrev"] . " ON " . $this->primary_prefix . "CardNo = " . $this->tables["players"]["primary_column"]);
				
				// GROUP BY
				$this->group_sql = $this->primary_prefix . "CardNo";
			}
			
			$this->filter->add($this->primary_column . " IN (" . implode(', ', $this->ids) . ")");
			
		}
	
		
		// ORDER ...
		
		if ($count || !$this->random) {
					
			if (empty($this->orderby)) 
				$this->orderby = $this->default_orderby;
			
			if (empty($this->order)) 
				$this->order = $this->default_order;
			
			// Add Order filters
			if (!$this->order_sql) {
				// ORDER ...
				if ($this->custom_order) 
					$this->order_sql = $this->custom_order . ", ";
		
				if (!empty($this->orderby)) $this->order_sql .= " " . $this->primary_prefix . $this->orderby . " ";
				if (!empty($this->order)) $this->order_sql .= " " . strtoupper($this->order);
			}
		}		
		

		/////////////////////////
		// SEARCH Query
		
		if ($count) {
			// Counting 
			$this->query = 
			$this->query1 = 
			$this->sql->select($this->show_columns()) . " " . $this->sql_from . " FORCE INDEX(MerchantID_DateOfSale) " . $this->sql_join . " " . $this->filter->show() . " " . $this->show_order() . " " . $this->limit_sql . " ";
		}
		else {
			// Getting
			$this->query = 
			$this->query2 = 
			$this->sql->select($this->show_columns()) . " " . $this->sql_from . " " . $this->sql_join . " " . $this->filter->show() . " " . $this->show_order() . " " . $this->limit_sql . " ";
		}
		
		// Debug Queries
		//if ($this->show_queries) 
		//	sayhello($this->query); // print_r($_REQUEST); 
		
		
		//echo $this->query;
		
		// Get Data
		if ($count == TRUE) {
			
			$this->results = $this->wpdb->get_col($this->query);
		} 
		else {
			$this->results = $this->wpdb->get_results($this->query, ARRAY_A);
		}
		
		// Return results
		return $this->results;
	}


	
	// FILTERS //////.......................
	// Create filters for each search term
	
	function do_transactions_filter() {
		$args = FALSE;

		//$this->filter->add('((SiteGroup != "CHEST" AND SiteGroup != "CRP") OR SiteGroup = "" OR SiteGroup IS NULL)');
		
		//print_r($this->args);
		
		if (isset($this->args))
			$args = $this->args;
		
		// Handle arguments
		if (is_array($args) && !empty($args)) {
			
			// Cycle through argument array
			foreach ($args as $arg => $value) {
				
				// If Card Numbers have been passed
				switch ($arg) {
					case "hide_res" :
						if ( !in_array($value, array('0', 0, FALSE)) )
							$this->filter->add("(Status != 'RES' OR Status = '')");
					break;
					
					case "merchant_id" : 
						if (empty($value))
							break;
						
						// Add this one Merchant ID to the query
						$this->filter->add($this->primary_prefix . "MerchantID = " . $value);
					break;
					
					case "merchant_ids" : 
						if (empty($value) || !is_array($value) || sizeof($value) < 1)
							break;
						
						// Add this list of Merchant IDs to the query
						$this->filter->add($this->primary_prefix . "MerchantID IN (" . implode(",", $value) . ")");
					break;
					
					case "player_id" : 
						if (empty($value))
							break;
						
						$this->filter->add($this->primary_prefix . "CardNo = " . $value);
					break;
					
					case "id" : 
						if (empty($value))
							break;
						
						// Add this one Merchant to the query
						$this->filter->add($this->primary_prefix . "SaleID = " . $value);
					break;
					
					case "code" : 
						if (empty($value))
							break;
						
						$this->filter->add($this->primary_prefix . "ProductCodeID = '" . $value . "'");
					break;
					
					case "spend_greater_than" : 
						$this->filter->add($this->primary_prefix . "SalePrice >= " . $value);
					break;
					
					case "points_greater_than" : 
						$this->filter->add($this->primary_prefix . "PointsAllocated >= " . $value);
					break;
					
					case "period" :
						$quarter = $this->get_quarter($value);
						$this->filter->add($this->primary_prefix . "DateOfSale BETWEEN '" . $quarter["start"] . "' AND '" . $quarter["end"] . "'");
					break;
				}
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	///////////////////////////////////////////////////////
	// FUNCTIONS
	
	
	
	// Get Result Count
	
	function get_result_count() {
		if (isset($this->result_count) && !empty($this->result_count))
			return $this->result_count;
		
		if (isset($this->ids) && !empty($this->ids) && is_array($this->ids)) 
			return $this->result_count = sizeof($this->ids);
		
		return FALSE;
	}
	
	
	
	// Slice Results
	
	function slice_results() {
		if (!$this->paginate) $this->paginate = new pagination;
		if ($this->results && $this->results_per_page) return $this->page_results = array_slice($this->results, $this->paginate->get_start_index(), $this->results_per_page);
		return FALSE;
	}
	
	
	
	// Get Pagination
	
	function get_pagination() {
		if (!isset($this->pagination) && !empty($this->result_count)) {
			
			if (!isset($this->paginate)) {
				$this->paginate = new pagination;
				//$this->paginate->player_key = $this->player_key;
				$this->paginate->block = (isset($this->block)) ? $this->block : FALSE;
			}
			$this->pagination = $this->paginate->show($this->result_count, $this->results_per_page);
		}
		
		if (!isset($this->pagination))
			$this->pagination = FALSE;
		
		return $this->pagination;
	}
	
	
	
	// Set data style
	
	function set_array($style = "ARRAY_A") {
		$this->data_style = $style;	
	}

	
	
	// Add Column
	
	function add_column($column = FALSE) {
		return ($column == FALSE) ? FALSE : $this->columns[] = $column;
	}
	
	
	
	// Add Columns	
	
	function add_columns($arr = FALSE) {
		if (!empty($arr) && is_array($arr)) 
			foreach($arr as $column) 
				$this->add_column($column);
	}
	
	
	
	// Show Columns
	
	function show_columns() {
		if (empty($this->columns) || !is_array($this->columns))
			return FALSE;
				
		return implode(", ", $this->columns);
	}
	
	
	
	// Show Order SQL
	
	function show_order() {
		return (!$this->order_sql) ? FALSE : " ORDER BY " . $this->order_sql;
	}
	
	
	// Add Join Clause
	
	function add_join($join = FALSE) {
		if ($join) return $this->sql_join .= " " . $join . " ";	
	}



	// Interpret Search Params

	function interpret_search_params($search_params = FALSE) {
		global $ip;
		
		if (!$search_params) 
			$search_params = $this->terms;
		
		// If we have search params...
		if ($search_params) : 
			extract($search_params);
			$with_bed = FALSE;
			
			$sold = $this->sold;
			$keywords = $this->keywords;
			$offices = $this->offices;
			$featured = ($this->featured == "true");
			$categories = $this->categories;
			$category = ($this->category_row || $categories);
			$types = $this->player_types;
			$merchant_type = $this->merchant_type;
			$what = $this->what;
			$bedrooms = ($this->min_bedrooms || $this->max_bedrooms);
			$bathrooms = ($this->min_bathrooms || $this->max_bathrooms);
			$carparks = ($this->min_carparks || $this->max_carparks);
			$price = ($this->min_price || $this->max_price);
			$localities = $this->localities;
			$waterfront = $this->waterfront;
			$sortdropdowns = ($this->settings[$this->prefix . "_show_sort_dropdowns"] == "yes");
			$agent = ($this->agent_id) ? $this->get_employee($this->agent_id) : FALSE;

			if ($sold) $output = "You are browsing ";
			else $output = "You searched ";
			
			if ($keywords) $output .= '<strong>"' . $keywords . '"</strong> ';
			
			// Offices
			if ($offices && ($names = $this->get_office_locations($offices))) {
				$size = sizeof($names);
				//$plural = "'s";
				if ($size > 1) $plural = "s";
				$off = FALSE;
				for ($i = 0; $i < $size; $i++) :
					if (!empty($names[$i])) :
						if ($off && ($i < ($size - 1))) $off .= ", ";
						if ($off && ($i == ($size - 1))) $off .= " and ";
						$off .= "<strong>" . ucwords($names[$i]) . "</strong>";
					endif;
				endfor;
				$output .= " the " . $off . " office" . $plural . " ";	
			}
			
			$output .= " for ";
			
			// Featured
			if ($featured) $output .= " <strong>Featured</strong> ";

			// Sold
			if ($sold) :
				if (!$this->buyrent || ($this->buyrent == "buy")) $output .= " <strong>SOLD</strong> ";
				else $output .= " <strong>LEASED</strong> ";
			endif;
			
			// Categories
			if ($category) {
				//$output .= " in ";
				// Categories
				if ($categories) {
					//print_r($categories); die;
					$size = sizeof($categories);
					$cat = FALSE;
					for ($i = 0; $i < $size; $i++) :
						if (!empty($categories[$i]) && $categories[$i] != "all") :
							if ($cat && ($i < ($size - 1))) $cat .= ", ";
							if ($cat && ($i == ($size - 1))) $cat .= " and ";
							$cat .= "<strong>" . $this->db_categories[$categories[$i]] . "</strong>";
						endif;
					endfor;
					$output .= $cat . " ";
				}				
				// Category
				elseif ($this->category_row) $output .= "<strong>" . titleCase($this->category_row) . "</strong> ";
			}
			
			
			// Player Types
			if ($types && !in_array("all", $types)) {
				$playertypes = FALSE;
				$size = sizeof($types);
				for ($i = 0; $i < $size; $i++) {
					if (!empty($types[$i]) && $types[$i] != "all") {
						if ($playertypes && ($i < ($size - 1))) $playertypes .= ", ";
						if ($playertypes && ($i == ($size - 1))) $playertypes .= " and ";
						$playertypes .= "<strong>" . titleCase($types[$i]) . "</strong>";
					}
				}
				if ($playertypes) $output .= " " . $playertypes . " ";
			}
			
			if ($merchant_type) {
				$output .= "<strong>" . strtolower($ip->MERCHANT_TYPES[$merchant_type]) . "</strong> ";				
			}
			
			$output .= ($what == "merchants") ? " <strong>merchants</strong>" : " players";
			
			
			// FOR SALE / FOR RENT
			if (!$sold && ($for = $this->search_types_array[$this->buyrent]["for"]))
				$output .= " <strong>FOR " . strtoupper($for) . "</strong>";
			
				
			// Bedrooms
			if ($bedrooms) {
				$output .= ", ";
				if ($this->min_bedrooms) {
					$output2 .= "<strong>" . $this->min_bedrooms;
					$with_bed = TRUE;
				}
				if ($this->max_bedrooms) {
					if ($this->min_bedrooms) $output2 .= "-";
					else $output2 .= " up to ";
					$output2 .= $this->max_bedrooms . " bedrooms</strong>";
					$with_bed = TRUE;
				} elseif ($this->min_bedrooms) {
					$plural = ($this->min_bedrooms > 1) ? "s" : FALSE;
					$output2 .= "+ bedroom" . $plural . "</strong>";	
				}
			}
			
			// Bathrooms
			if ($bathrooms) {
				if ($this->min_bathrooms) {
					if ($with_bed) {
						if ($carparks) $output2 .= ", ";
						else $output2 .= " and ";
					}
					$output2 .= "<strong>" . $this->min_bathrooms;
				}
				if ($this->max_bathrooms) {
					if ($this->min_bathrooms) $output2 .= "-";
					else {
						if ($with_bed) $output2.= " and "; 
						$output2 .= " upto ";
					}
					$output2 .= $this->max_bathrooms . " bathrooms</strong>";
				} elseif ($this->min_bathrooms) {
					$plural = ($this->min_bathrooms > 1) ? "s" : FALSE;
					$output2 .= "+ bathroom" . $plural . "</strong>";
				}
			}
			
			// Car Spaces
			if ($carparks) {
				if ($this->min_carparks) {
					if ($with_bed) $output2 .= " and ";
					$output2 .= "<strong>" . $this->min_carparks;
				}
				if ($this->max_carparks) {
					if ($this->min_carparks) $output2 .= "-";
					else {
						if ($with_bed) $output2.= " and "; 
						$output2 .= " upto ";
					}
					$output2 .= $this->max_carparks . " car spaces</strong>";
				} elseif ($this->min_carparks) {
					$plural = ($this->min_carparks > 1) ? "s" : FALSE;
					$output2 .= "+ car space" . $plural . "</strong>";
				}
			}
			
			if (!empty($output2)) $output .= " with " . $output2 . ",";
			$output .= " ";
			
			// Price
			if ($price) {
				$output .= (($this->buyrent == "rent") || ($this->show_in_rental)) ? "renting for" : "priced";
				if ($this->min_price && $this->max_price) $output .= " between <strong>$" . formatMoney($this->min_price) . "</strong> and <strong>$" . formatMoney($this->max_price) . "</strong>";
				if ($this->min_price && !$this->max_price) {
					if ($buy) " at ";
					$output .= " <strong>$" . formatMoney($this->min_price) . "+</strong> ";
				}
				if (!$this->min_price && $this->max_price) $output .= " less than <strong>$" . formatMoney($this->max_price) . "</strong>";
				if (!$buy) $output .= " per week";
			}
			
			if ($waterfront == "yes") {
				$output .= "on the <strong>waterfront</strong> ";
			}
		
			// Localities
			if ($localities && !in_array("all", $localities)) {
				$size = sizeof($localities);
				$output .= " in ";
				$loc = FALSE;
				for ($i = 0; $i < $size; $i++) :
					if (!empty($localities[$i]) && ($localities[$i] != "all")) :
						if ($loc && ($i < ($size - 1))) $loc .= ", ";
						if ($loc && ($i == ($size - 1))) $loc .= " and ";
						$loc .= "<strong>" . titleCase($localities[$i]) . "</strong>";
					endif;
				endfor;
				
				$output .= $loc;
				
				// Surrounding Suburbs
				if ($this->include_surrounding && $this->radius && ($this->radius > 0)) 
					$output .= ", including <strong>surrounding suburbs within " . $this->radius . "km</strong>";
			}
			
			if ($agent) {
				$output .= ", listed by <strong>" . $agent['first_name'] . " " . $agent['last_name'] . "</strong> ";
			}
			
			// Order By
			if ($sortdropdowns && $this->orderby) 
				$output .= " [ordered by " . titleCase(str_replace("_", " ", $this->orderby)) . " " . (($this->order == "DESC") ? "descending" : "ascending") . "]";
			
		endif;
		return "<p class='interpreted_params'>" . $output . "</p>";
	}
}



///////////////////////
// HTTP FUNCTIONS
///////////////////////


// Check HTTP
function addHTTP($url) {
	return 'http://' . str_replace("http://", "", $url); 
}

// Adjust Parameters
function adjustURLParam($url, $s) {            
	if (preg_match('/(.*?)\?/', $url, $matches)) $urlWithoutParams = $matches[1];
	else $urlWithoutParams = $url;  
	parse_str(parse_url($url, PHP_URL_QUERY), $params);
	if (strpos($s, '=') !== false) {
			list($var, $value) = explode('=', $s);
			$params[$var] = urldecode($value);
			return $urlWithoutParams . '?' . http_build_query($params);      
	} else {
			unset($params[$s]);
			$newQueryString = http_build_query($params);
			if ($newQueryString) return $urlWithoutParams . '?' . $newQueryString;      
			else return $urlWithoutParams;
	}
}

// Add Parameter
function addURLParam($url = FALSE, $s = FALSE) {
	return adjustURLParam($url, $s);
}

// Delete Parameter
function deleteURLParam($url = FALSE, $s = FALSE) {
	return adjustURLParam($url, $s);           
}