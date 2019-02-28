<?php




// SQL Object

class sql {
	
	var $columns;
	
	function __construct() {
		
	}

	// Convert data into SQL-ready string
	function value($theValue, $theType = "text", $theDefinedValue = "", $theNotDefinedValue = "") {
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;
	
		switch ($theType) {
			case "text":
				$theValue = !empty($theValue) ? "'" . mysql_real_escape_string( $theValue ) . "'" : "NULL";
				break;    
			case "long":
			case "int":
				$theValue = !empty($theValue) ? intval($theValue) : "NULL";
				break;
			case "double":
				$theValue = !empty($theValue) ? "'" . doubleval($theValue) . "'" : "NULL";
				break;
			case "date":
				$theValue = !empty($theValue) ? "'" . $theValue . "'" : "NULL";
				break;
			case "defined":
				$theValue = !empty($theValue) ? $theDefinedValue : $theNotDefinedValue;
				break;
		}
		return $theValue;
	}
	
	// Convert data into SQL-ready search string
	function searchval($theValue, $theType = "text", $theDefinedValue = "", $theNotDefinedValue = "") {
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;
	
		switch ($theType) {
			case "text":
				$theValue = !empty($theValue) ? "'" . mysql_real_escape_string( $theValue ) . "'" : "%";
				break;    
			case "long":
			case "int":
				$theValue = !empty($theValue) ? intval($theValue) : "0";
				break;
			case "double":
				$theValue = !empty($theValue) ? "'" . doubleval($theValue) . "'" : "NULL";
				break;
			case "date":
				$theValue = !empty($theValue) ? "'" . $theValue . "'" : "";
				break;
			case "defined":
				$theValue = !empty($theValue) ? $theDefinedValue : $theNotDefinedValue;
				break;
		}
		return $theValue;
	}

	function doQuery($query = FALSE) {
		$result = mysql_query("SET NAMES utf8");
		if ($query) $result = mysql_query($query, $GLOBALS['db']) or die(mysql_error());
		return $result;
	}
	
	function query($query = FALSE) {
		if ($query) {
			if ($result = $this->doQuery($query)) {
				$i = 0;
				while ($row = mysql_fetch_assoc($result)) {
					foreach ($row as $key => $value) {
						$list[$i][$key] = $value;
					}
					$i++;
				}
			}
		}
		return $list;
	}
	
	function singleQuery($query = FALSE) {
		if ($query) 
			//echo "<p>" . $query . "</p>";
			if ($result = $this->doQuery($query)) $row = mysql_fetch_assoc($result);
		return $row;
	}
	
	function DeleteRow($table, $key, $id) {
		$string = "DELETE FROM $table WHERE $key = $id";
		return $this->doQuery($string);
	}
	
	function SelectQuery($select = "*", $table = FALSE, $rows = FALSE, $values = FALSE, $equals = FALSE, $orderby = FALSE, $other = FALSE, $limit = 1) {
		if ($limit) $limitprint = "LIMIT " . $limit;
		if (!$select) $select = "*";
		if ($table && $rows && $values) {
			$rows = (!is_array($rows)) ? array( $rows ) : $rows;
			$values = (!is_array($values)) ? array( $values ) : $values;
			$equals = (!is_array($equals)) ? array( $equals ) : $equals;
			
			$where = "WHERE ";
			for($i=0;$i<sizeof($rows);$i++) {
				$where .= $rows[$i] . " " . $equals[$i] . " " . $this->value($values[$i]);
				if ($i < (sizeof($rows) - 1)) $where .= " AND ";
			}
		}
		if ($orderby) 
			$orderby = "ORDER BY " . $orderby;
		
		$string = "SELECT $select FROM $table $where $other $orderby $limitprint";
		
		//echo $string;
		
		if ($limit == '1') 
			return $this->singleQuery($string);
		elseif ($limit > 1) 
			return $this->query($string);
	}
	
	
	
	// Find in Table
	
	function findInTable($table = FALSE, $rows = FALSE, $values = FALSE, $equals = "=", $other = FALSE) {
		if ($table && $rows && $values) {
			$rows = (!is_array($rows)) ? array( $rows ) : $rows;
			$values = (!is_array($values)) ? array( $values ) : $values;
			for($i=0;$i<sizeof($rows);$i++) {
				$where .= $rows[$i] . " " . $equals . " " . $this->value($values[$i]);
				if ($i < (sizeof($rows) - 1)) $where .= " AND ";
			}
			$string = "SELECT * FROM $table WHERE $where $other LIMIT 1";
			return $this->singleQuery($string);
		}
	}
	
	function EmptyTable($table = FALSE) {
		$string = "TRUNCATE TABLE " . $table;
		return $this->doQuery($string);
	}
	
	function findExact($table = FALSE, $row = FALSE, $value = FALSE, $other = FALSE) {
		if ($table && $row && $value) 
			return $this->findInTable($table, $row, $value, "=");
	}
	
	function findLike($table = FALSE, $rows = FALSE, $values = FALSE, $other = FALSE) {
		if ($table && $row && $values) 
			return $this->findInTable($table, $rows, $values, "LIKE");
	}
	
	function affected() {
		$string = $this->singleQuery("SELECT ROW_COUNT() AS Count");
		
		return $string["Count"];
	}
	
	function string_list($cols = FALSE) {
		$columns = ($cols) ? $cols : $this->columns;
		
		if (is_array($columns) && count($columns) > 0) 
			$columns = implode(", ", $columns);
		
		return $columns;
	}
	
	function select($columns = FALSE) {
		if ($columns) 
			$this->columns = $columns;
			
		$cols = ($this->columns) ? $this->string_list($this->columns) : "*";
		return "SELECT " . $cols . " ";
	}
	
	function where_range($column = FALSE, $min = FALSE, $max = FALSE) {
		if ($column != FALSE && ($min != FALSE || $max != FALSE)) { 
			if ($min != FALSE && $max != FALSE) 
				return $column . " BETWEEN " . $min . " AND " . $max;
			elseif ($min != FALSE) 
				return $column . " >= " . $min;
			elseif ($max != FALSE) 
				return $column . " <= " . $max;
		}
	}
	
	// Table exists
	function table_exists($tablename = FALSE, $dbname = FALSE) {
		global $wpdb;
		if ($tablename) {
			if (!$dbname) $dbname = DB_NAME;
			$query = $wpdb->prepare("SELECT * FROM information_schema.tables WHERE table_schema = %s AND table_name = %s LIMIT 1", $dbname, $tablename);
			$results = $wpdb->get_row($query, ARRAY_A);
			if ($results) return TRUE;			
		} 
		return FALSE;
	}

}




// Filter Object extends SQL Object

class filter extends sql {
	var $filter;
	
	function __construct($filter = FALSE, $where = TRUE) {
		parent::__construct();
		$this->where = $where;
		$this->filter = $filter;
	}
	
	function add($filter = FALSE) {
		if (!empty($filter)) {
			if (!empty($this->filter)) 
				$this->filter .= "AND";
			
			$this->filter .= " " . $filter . " ";
		}
	}
		
	function show() {
		$output = " ";
		
		if ($this->where === TRUE) 
			$output .= "WHERE ";
			
		$output .= (!empty($this->filter)) ? $this->filter : 1;
		 
		return $output;	
	}
}




// SHOWHTML Object
// For generating HTML markup

class showHTML {
	
	
	// Print out an array
	// mostly used for debugging
	
	function printArray($arr) {
		if (is_array($arr)) :
			foreach ($arr as $i => $value) $html .= "<p>" . $i . " = " . $value . "</p>\n";
			if (!$html) : $html = "Array empty"; endif;
		else : $html = "Is not array"; endif;
		return $html;
	}

	// Print Out CSS
	// Takes an array of css elements and values and echoes them in orderly fashion
	function printCSS($arr) {
		
		if (is_array($arr) && ($css_count = sizeof($arr))) : 
			foreach ($arr as $element => $style) :
				$n++;
if (isset($temp_element) && ($element != $temp_element)) : ?>
}

<?php endif;
if ($element != $temp_element) : $temp_element = $element; ?>
<?= $element; ?> {	
<?php endif;
foreach ($style as $property => $value) : ?>
	<?= $property . ": " . $value; ?>;
<?php endforeach;
if ($css_count == $n) : ?>
}	
<?php
endif;
			endforeach;
		endif;

		return $html;	
	}

	
	
	// Format error messages
	
	function unorderedList ($arr) {
		if (is_array($arr)) :
			$html = "<ul>\n";
			for ($i = 0; $i < sizeof($arr); $i++) 
				$html .= "<li>" . $arr[$i] . "</li>\n";
			$html .= "</ul>\n";
		endif;
		return $html;
	}
	
	
	// Make a list of links
	
	function makeLinks(&$arr, $var = "ID", $key = "ID", $selected = FALSE, $get = FALSE) {
		if (isset($var)) $selected = (isset($selected)) ? $selected : $_GET[$var];
		for ($i=0; $i < sizeof($arr); $i++) :
			$url = (!$get) ? $PHP_SELF : $get;
			$url .= (strpos($get, '?')) ? '&' : '?';
			$url .= $var . "=" . $arr[$i][$key];
			$linktext = (empty($arr[$i]['Image'])) ? $arr[$i]['Name'] : "<img src='images/" . $arr[$i]['Image'] . "' alt='" . $arr[$i]['Name'] . "' />";
        	$list[$i] = "<a href='" . $url . "' title='" . strip_tags($arr[$i]['Name']) . "' >" . $linktext . "</a>";
			if ($selected == $arr[$i][$key]) $list[$i] = sprintf("<strong>&raquo;</strong> %s", $list[$i]);
		endfor;
		return $list;
	}
	
	
	// Make Links - Lite
	
	function makeLinksLite($arr, $var = "ID", $key = "ID", $selected = FALSE) {
		global $_GET;
		if (isset($var)) $selected = (isset($selected)) ? $selected : $_GET[$var];
		for ($i=0; $i < sizeof($arr); $i++) :
			$linktext = (empty($arr[$i]['Image'])) ? $arr[$i]['Name'] : "<img src='images/" . $arr[$i]['Image'] . "' alt='" . $arr[$i]['Name'] . "' />";
        	$list[$i] = "<a href='" . $arr[$i]['Link'] . "' title='" . $arr[$i]['Name'] . "' >" . $linktext . "</a>";
			if ($selected == $arr[$i][$key]) $list[$i] = sprintf("<strong>&raquo;</strong> %s", $list[$i]);
		endfor;
		return $list;
	}
	
	
	
	// Breadcrumbs
	
	function breadcrumbs ($arr, $url = FALSE, $attrib = FALSE, $delim = "&raquo;") {
		if (($size = sizeof($arr)) > 1) :
			$crumb = array_reverse($arr);
			for ($i=0; $i < $size; $i++) :
				$end = ($i == $size - 1) ? TRUE : FALSE;
				$unique = $crumb[$i]['URL'];
				$link = (!$url) ? $unique : $url . $crumb[$i]['ID'];
				if ($link && !$end) $html .= '<a href="' . $link . '" ' . $attrib . '>' . $crumb[$i]['Name'] . '</a>';
				else $html .= $crumb[$i]['Name'];
				if (!$end) $html .= ' ' . $delim . ' ';
			endfor;
		endif;
		return $html;
	}
	
	
	// Element	

	static function pageElement($el = FALSE, $tag = "div", $id = "", $ref = "id", $attribs = FALSE) {
		if (empty($el))
			return FALSE;
		
		$html = "\n<" . $tag;
		
		if (!empty($id)) 
			$html .= " " . $ref . "='" . $id . "' ";
		
		$html .= $attribs . ">\n" . $el . "\n</" . $tag . ">\n";
		
		return $html;	
	}
	
	
	// Results Table
	
	function resultsTable(&$arr = FALSE, $tickvalue = FALSE, $showheader = TRUE) {
		if (!empty($arr) && is_array($arr)) {
			$table = FALSE;
			if ($showheader == TRUE) {
				$headercells = FALSE;
				foreach ($arr[0] as $key => $value) 
					$headercells .= self::tableHeader($key);
				$table .= "<thead>" . self::tableRow($headercells) . "</thead>";
			}
			$table .= "<tbody>";
			for ($i = 0; $i < sizeof($arr); $i++) {
				$tablecells = FALSE;
				foreach ($arr[$i] as $key => $value) 
					$tablecells .= self::tableCell($value);
				$table .= self::tableRow($tablecells);
			}
			$table .= "</tbody>";
			$html = self::table($table);
		} else {
			$html .= "No array found.";
		}
		return $html;
	}
	
	
	// Table
	
	static function table($content = FALSE, $name = FALSE, $width = "100%", $brdr = FALSE, $cp = 5, $cs = FALSE, $height = FALSE, $extra = FALSE) {
		//echo $content;
		$html = "<table";
		if ($name) $html .= " name=\"" . $name . "\"";
		if ($width) $html .= " width=\"" . $width . "\"";
		if ($height) $html .= " width=\"" . $height . "\"";
		if ($brdr !== FALSE) $html .= " border=\"" . $brdr . "\"";
		if ($cp) $html .= " cellpadding=\"" . $cp . "\"";
		if ($cs) $html .= " cellspacing=\"" . $cs . "\"";
		if ($extra) $html .= " " . $extra;
		$html .= " >\n";
		$end = "\n</table>\n";
		$output = $html . $content . $end;
		return $output;
	}
	
	
	
	// Table Cell
	
	static function tableCell ($content = "", $class = FALSE) {
		if ($class !== FALSE) 
			$class = "class='" . $class . "'";
		
		return $html = "<td " . $class . ">" . $content . "</td>\n";
	}
	
	
	
	// Table Header
	
	static function tableHeader ($content, $scope = "col") {
		return $html = "<th scope=\"" . $scope . "\">" . $content . "</th>\n";
	}
	
	
	
	// Table Row
	
	static function tableRow ($content) {
		return $html = "<tr>" . $content . "</tr>\n";
	}
	
	
	
	// Entry Gate ?
	
	static function entryGate($entry, $name, $value) {
		if ($entry == "checkbox") 
			return form::checkbox($name, $value, TRUE); // Work out a way to toggle the "checked" value
		elseif ($entry == "textarea") 
			return form::textarea($name, $value);
		elseif ($entry == "textfield")
			return form::textfield($name, $value);
	}
	
	
	
	// Table Form Row
	
	function tableFormRow($name, $value = FALSE, $label = FALSE, $entry = "textfield") {
		if (!$label) $label = $name;
		return $html = $this->tablerow( $this->tableHeader( form::label( $name, $label ), "row") . $this->tableCell( $this->entryGate( $entry, $name, $value) ) );
	}
	
	
	
	// Anchor tag
	
	static function ahref($content = FALSE, $url = FALSE, $title = FALSE, $id = FALSE, $class = FALSE, $attribs = '', $target = '_self') {
		$html = "<a ";
		if ($url) $html .= "href='" . $url . "' ";
		if ($id) $html .= "id='" . $id . "' ";
		if ($title) $html .= "title='" . $title . "' ";
		if ($class) $html .= "class='" . $class . "' ";
		if ($attribs) $html .= $attribs . " ";
		$html .= "target='" . $target . "'>" . $content . "</a>";
		return $html;
	}
	
	
	
	// Image
	
	function img($url = FALSE, $name = "Image", $alt = "Image", $width = FALSE, $id = FALSE, $class = FALSE, $height = FALSE, $border = 1, $align = FALSE) {
		$html = "<img ";
		if ($url) $html .= "src=\"" . $url . "\" ";
		if ($name) $html .= " name=\"" . $name . "\" ";
		if ($alternate = ($alt) ? $alt : $name) $html .= "alt=\"" . $alternate . "\" ";
		if ($width) $html .= "width=\"" . $width . "\" ";
		if ($height) $html .= "height=\"" . $height . "\" ";
		if ($identity = ($id) ? $id : "Image" . $name) $html .= "id=\"" . $identity . "\" ";
		if ($class) $html .= "class=\"" . $class . "\" ";
		if ($align) $html .= "align=\"" . $align . "\" ";
		$html .= "border=\"" . $border . "\" ";
		$html .= "/>";
		return $html;
	}
	
	
	
	// H3 Heading
	
	function h3($content = "Heading 3", $id = FALSE, $ref = FALSE, $attribs = FALSE) {
		$html = $this->pageElement($content, "h3", $id, $ref, $attribs);
		return $html;
	}
	
	
	
	// Format text

	function text_format($desc = "", $html = TRUE) {
		return ($html) ? nl2br($desc) : $desc;
	}
}









// FORM Object

class form {
	
	var $ID, $Name, $Value, $FormName, $FormID;
	
	
	function __construct($vars = FALSE) {
		$this->terms = $vars;
		$this->id = (isset($this->terms['ID'])) ? $this->terms['ID'] : FALSE;
		$this->Name = (isset($this->terms['Name'])) ? $this->terms['Name'] : FALSE;
		$this->Value = (isset($this->terms['Value'])) ? $this->terms['Value'] : FALSE;
		$this->FormID = (isset($this->terms['FormID'])) ? $this->terms['FormID'] : FALSE;
		$this->FormName = (isset($this->terms['FormName'])) ? $this->terms['FormName'] : FALSE;
	}
	
	
	
	// Optional / Required

	function opt($value) {
		$optional = array("optional", "required");
		return $optional[$value];
	}
	
	
	
	
	// Compare A->B
	
	function compare($a, $b) {
		return ($a === $b);
	}
	
	
	
	// Print Form
	
	function printForm($content = FALSE, $action = FALSE, $method = 1, $name = FALSE, $attribs = FALSE, $encoding = 1, $lang = "en") {
		$html = "";
		
		if ($name == FALSE && !empty($this->FormName))
			$name = $this->FormName;

		if ($method == FALSE)
			$method = 1;
		
		$enctypes = array("", "multipart/form-data", "application/x-www-form-urlencoded");
		$methods = array("" , "post", "get");
		
		$html .= "<form";
		$html .= (!empty($name)) ? " name='" . $name . "'" : "";
		$html .= (!empty($name)) ? " id='" . $name . "'" : "";
		$html .= " action='" . ((!empty($action)) ? $action : "") . "'";
		$html .= (!empty($attribs)) ? $attribs : "";
		$html .= " method='" . $methods[$method] . "' enctype='" . $enctypes[$encoding] . "' lang='" . $lang . "' xml:lang='" . $lang . "' >\n";
		$html .= $content;
		$html .= "</form>\n";
		
		return $html;
	}
	
	
	
	// Label Tag

	static function label($for = FALSE, $text = FALSE) {
		if (empty($for) && empty($text))
			return FALSE;
		
		$content = (!empty($text)) ? $text : $for;
		
		$html = "<label ";
		
		if (!empty($for)) 
			$html .= " for='" . $for . "' ";
		
		$html .= " >" . $content . "</label>\n";
			 
		return $html;
	}
	
	
	
	// Input Field
	
	static function inputfield($type = "hidden", $name = FALSE, $value = FALSE, $attribs = FALSE, $label = FALSE) {
		$html = "";
		
		if (!empty($label) && $type != "radio") 
			$labelmarkup = (strlen($label) > 1) ? form::label($name, $label) : form::label($name, $name);
			
		$after = array("checkbox", "radio");
		
		if (!in_array($type, $after)) 
			$html .= $labelmarkup;
		
		$html .= "<input type='" . $type . "'";
		
		if ($name !== FALSE) 
			$html .= " name='" . $name . "'";
		
		if ($value !== FALSE) 
			$html .= " value=\"" . $value . "\"";
			
		$html .= " " . $attribs . " />\n";
		
		if (!empty($label) && in_array($type, $after)) {
			if ($type == "radio") 
				$html = (strlen($label) > 1) ? form::label(FALSE, $html . $label) : form::label(FALSE, $html);
			else 
				$html .= $labelmarkup;
		}
		
		return $html;
	}
	
	
	
	// File Field
	
	function fileField($name = FALSE, $label = FALSE, $size = 15, $extra = FALSE) {
		if (!empty($label)) 
			$l = (strlen($label) > 1) ? $label : $name;
		
		return form::inputfield("file", $name, FALSE, " size='" . $size . "' " . $extra, $label);
	}
	
	
	
	// Button

	function button($type = "submit", $text = "Submit", $name = FALSE, $attribs = FALSE) {
		return form::inputfield($type, $name, $text, " " . $attribs);
	}
	
	
	
	// Submit Button
	
	function submit($text = "Submit", $name = FALSE, $attribs = FALSE) {
		return form::inputfield("submit", $name, $text, "class='inputSubmit' " . $attribs);
	}
	
	
	
	// Image Field
	
	function image($src = FALSE, $name = FALSE, $alt = FALSE, $attribs = FALSE) {
		$extra .= "src='" . $src . "' ";
		$alt = (!empty($alt)) ? $alt : $name;
		$extra .= "alt='" . $alt . "' ";
		
		return form::inputfield("image", $name, "", $extra . $attribs);
	}
	
	
	
	// Hidden Field
	
	function hidden($name = FALSE, $value = FALSE, $attribs = FALSE) {
		return form::inputfield("hidden", $name, $value, $attribs);
	}
	
	
	
	// Checkbox Field
	
	function checkbox($name = FALSE, $value = TRUE, $checked = FALSE, $attribs = FALSE, $label = FALSE) {
		if ($checked == TRUE) {
			if (!empty($attribs)) 
				$attribs .= ", ";
			
			$attribs .= "checked='checked'";
		}
		
		return form::inputfield("checkbox", $name, $value, $attribs, $label);
	}
	
	
	
	// Radio Button Field
	
	function radiobutton($name = FALSE, $value = "", $checked = FALSE, $id = FALSE, $label = FALSE, $attribs = FALSE) {
		if ($checked == TRUE) {
			if (!empty($attribs)) 
				$attribs .= ", ";
			
			$attribs .= " checked='checked' ";
		}
		
		if (!empty($id))
			$attribs .= " id='" . $id . "' ";
		
		return form::inputfield("radio", $name, $value, $attribs, $label);
	}
	
	
	
	// Text Field
	
	function textfield($name = FALSE, $value = "", $attribs = FALSE, $label = FALSE) {
		return form::inputfield("text", $name, $value, $attribs, $label);
	}
	
	
	
	// Password Field
	
	function password($name, $value = "", $attribs = FALSE, $label = FALSE) {
		return form::inputfield("password", $name, $value, $attribs, $label);
	}
	
	
	
	// Textarea Field
	
	function textarea($name = FALSE, $value = "", $attribs = FALSE, $label = FALSE) {
		if (!empty($label)) 
			$html = form::label($name, $label);
		
		$html .= "<textarea name='" . $name . "' " . $attribs . ">\n" . $value . "</textarea>\n";
		
		return $html;
	}
	
	
	
	// Legend Tag
	
	function legend($name = FALSE) {
		if (empty($name))
			return FALSE;
		
		return "<legend>" . $name . "</legend>\n";
	}
	
	
	
	// Option
	
	function option($content = FALSE, $value = FALSE, $selected = FALSE) {
		//if (empty($value) && empty($content))
		//	return FALSE;
		
		//if (empty($value) && !empty($content)) 
		//	$value = $content;
		
		if (empty($content) && !empty($value)) 
			$content = $value;
		
		$out = "\t<option value='" . $value . "' ";
		
		if ($selected == TRUE)
			$out .= "selected='selected' ";
		
		$out .= ">" . $content . "</option>\n";
		
		return $out;
	}
	
	
	
	// Options - Simple
	
	function simpleOptions($arr = array(), $selected = "") {
		if (empty($arr) || !is_array($arr)) 
			return FALSE;
		
		$out = FALSE;
		
		foreach ($arr as $i => $value) 
			$out .= form::option( $value, $i, ($i == $selected) );
		
		return $out;
	}
	
	
	
	// Select Field
	
	function select($arr = array(), $name = "Select", $class = "", $id = "", $key = "ID", $value = "Name", $default = FALSE, $firstkey = 0, $firstname = FALSE, $extra = FALSE, $multiple = FALSE, $size = FALSE ) {
		if (empty($arr) || !is_array($arr)) 
			return FALSE;
		
		$duh = FALSE;
		$attribs = "";
		
		if ($size !== FALSE && is_numeric($size)) 
			$attribs .= " size='" . $size . "'";
		
		if ($multiple == TRUE) 
			$attribs .= " multiple='multiple'";
		
		if (!empty($attribs)) 
			$extra .= $attribs;
		
		$id = (!empty($id)) ? $id : $name;
		
		$out = "<select name='" . $name . "' class='" . $class . "' " . $extra . " id='" . $id . "'>\n";
		
		$sel = FALSE;
		
		foreach ($arr as $i => $data) {
			if (isset($firstkey) && ($firstname !== FALSE) && (!$duh)) {
				$out .= form::option($firstname, $firstkey);
				$duh = TRUE;
			}
			
			if (isset($default) && !empty($default)) {
				if (is_string($default) || is_int($default)) 
					$sel = ($data[$key] == $default);
				elseif (is_array($default)) 
					$sel = in_array($data[$key], $default);
			}
				
			$out .= form::option( $data[$value], $data[$key], $sel );
		}
		
		$out .= "</select>\n";
		
		return $out;
	}
	
	
	
	// Select Field - Multiple
	
	function selectMultiple( &$arr = array(), $name = "SelectMultiple", $class = "", $id = "", $key = "ID", $value = "Name", $default = FALSE, $size = 10, $attribs = FALSE ) {
		if (empty($arr) || !is_array($arr))
			return FALSE;
		
		return $this->select( $arr, $name, $class, $id, $key, $value, $default, FALSE, FALSE, $attribs, TRUE, $size );
	}
	
	
	
	// Select Field - Simple
	
	function selectSimple($arr = array(), $name = "Select", $class = "", $id = "", $default = FALSE, $extra = FALSE, $multiple = FALSE, $size = FALSE) {
		if (empty($arr) || !is_array($arr))
			return FALSE;
		
		if (is_numeric($size)) 
			$attribs .= " size='" . $size . "'";
		
		if ($multiple == TRUE) 
			$attribs .= " multiple='multiple'";
		
		$id = ($id) ? $id : $name;
		$out = "<select name='" . $name . "' class='" . $class . "' " . $extra . " id='" . $id . "'>\n";
		$out .= form::simpleOptions($arr, $default);
		$out .= "</select>\n";
		return $out;
	}
	
	
	
	// Select Field - Number
	
	function numberSelect($name = "Number", $selected = '0', $num = '10', $start = '0', $sort = "asc", $class = FALSE, $id = FALSE) {
		for ($i = 0; $i < $num; $i++) {
			$number = $start + $i;
			$list[$i]["ID"] = $number;
			$list[$i]["Name"] = $number;
		}
		
		$id = ($id) ? $id : $name;
		$output = form::select( $list, $name, $class, $id, "ID", "Name", $selected  );
		return $output;
	}
	
	
	
	// Select Field - Minute
	
	function minuteSelect($name = "Minute", $selected = FALSE, $class = FALSE, $id = FALSE) {
		if (!$selected) 
			$selected = date("i");
		
		return $this->numberSelect($name, $selected, 59, 00, FALSE, $class, $id);
	}
	
	
	
	// Select Field - Minute Interval
	
	function minuteIntervalSelect($name = "Minute") {
		$list[] = array("ID" => "00", "Name" => "00");
		$list[] = array("ID" => "15", "Name" => "15");
		$list[] = array("ID" => "30", "Name" => "30");
		$list[] = array("ID" => "45", "Name" => "45");
		return $this->select( $list, $name );
	}
	
	
	
	// Select Field - Limit
	
	function limitSelect($selected = FALSE, $name = "Limit", $attribs = FALSE ) {
		$selected = (!$selected) ? DEFAULT_LIMIT : $selected;
		$list =
			array(
				array("ID" => "10", "Name" => "10"),
				array("ID" => "25", "Name" => "25"),
				array("ID" => "50", "Name" => "50"),
				array("ID" => "100", "Name" => "100"),
				array("ID" => "99999999", "Name" => "All")
		);
		return $this->select( $list, $name, "ID", "Name", $selected, FALSE, FALSE, $attribs );
	}
	
	
	
	// Select Field - Hour
	
	function hourSelect($name = "Hour", $selected = FALSE, $class = FALSE, $id = FALSE) {
		if (!$selected) $selected = date("g");
		return $this->numberSelect($name, $selected, 12, 0, FALSE, $class, $id);
	}
	
	
	
	// Select Field - Meridien
	
	function meridienSelect($name = "Meridien", $selected = FALSE) {
		if (!$selected) $selected = date("a");
		$list[] = array("ID" => "am", "Name" => "AM");
		$list[] = array("ID" => "pm", "Name" => "PM");
		
		return $this->select( $list, $name, "ID", "Name", FALSE, FALSE, $selected );
	}
	
	
	
	// Fieldset Tag
	
	function fieldset($content, $name = FALSE) {
		$html = "<fieldset>\n";
		
		if (!empty($name)) 
			$html .= form::legend($name);
		
		$html .= $content;
		$html .= "</fieldset>\n";
		
		return $html;
	}
	
	
	
	// Check
	
	function check($var, $value) {
		return ($this->checkval($var, $value)) ? "checked='checked'" : "";
	}
	
	
	
	// Check Value Equivalence
	
	function checkval($var, $value) {
		return ($var === $value);
	}
	
	
	
	// Check Email Address Validity
	
	function check_email_address($email) {
		// First, we check that there's one @ symbol, and that the lengths are right
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) return FALSE; // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
	
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++)
			if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) 
				return FALSE;

		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) : // Check if domain is IP. If not, it should be valid domain name
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) return FALSE; // Not enough parts to domain
			for ($i = 0; $i < sizeof($domain_array); $i++) 
				if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) return FALSE;
		endif;
		return TRUE;
	}

}















// DATA Object

class data {


	// Get Postcodes
	function Postcodes() {
		$string = "SELECT Postcodes_ID AS ID, Postcodes_Postcode AS Postcode, Postcodes_Suburb AS Suburb FROM Postcodes ORDER BY Postcodes_Postcode ASC";
		return sql::query($string);
	}
	
	function GetSuburbs($state = "%") {
		$string = sprintf("
			SELECT 
				Postcodes_ID AS ID, 
				Postcodes_Suburb AS Name, 
				Postcodes_Postcode AS Postcode 
			FROM Postcodes 
			WHERE States_ID LIKE %s 
			ORDER BY Postcodes_Suburb ASC",
			sql::value($state)
		);
		return sql::query($string);
	}

	function GetSuburb($pid = FALSE) {
		if ($pid) {
			$string = "
			SELECT Postcodes_ID AS ID, Postcodes_Suburb AS Suburb, Postcodes_Postcode AS Postcode	
			FROM Postcodes
			WHERE Postcodes_ID = $pid
			LIMIT 1";
			return sql::singleQuery($string);
		}
	}
	
	function SuburbExists($suburb) {
		$query = sprintf("
			SELECT Postcodes_ID AS ID, Postcodes_Suburb AS Suburb, Postcodes_Postcode AS Postcode
				FROM Postcodes
			WHERE Postcodes_Suburb LIKE %s
			ORDER BY Postcodes_Suburb ASC
			LIMIT 1",
				sql::value($suburb, "text")
		);
		return sql::singleQuery($query);
	}
	
	function GetShortestSuburb() {
		$string = "SELECT Postcodes_ID AS ID, Poscodes_Suburb AS Name, LENGTH(Postcodes_Suburb) AS Length FROM Postcodes ORDER BY LENGTH(Postcodes_Suburb) ASC LIMIT 1";
		return sql::singleQuery($string);
	}
	
	function GetLongestSuburb() {
		return sql::SelectQuery(
			"Postcodes_ID AS ID, Postcodes_Suburb AS Name, LENGTH(Postcodes_Suburb) AS Length",
			"Postcodes",
			FALSE,
			FALSE,
			FALSE,
			"LENGTH(Postcodes_Suburb) DESC",
			FALSE,
			1
		);
	}
	
	function SuburbPCList($state = "%") {
		$query = sprintf("
			SELECT 
				Postcodes_ID AS ID, 
				CONCAT(LEFT(Postcodes_Suburb, " . DEFAULT_CHARS . "), ' - ', Postcodes_Postcode) AS Name,
				States_ID AS State 
				FROM Postcodes 
				WHERE States_ID LIKE %s
					GROUP BY Postcodes_Suburb",
					sql::value($state, "text")
			);
		return sql::query($query);
	}
	
	function PrintSuburbList($default = FALSE, $state = FALSE, $firstkey = FALSE, $firstvalue = FALSE, $attribs = FALSE) {
		$form = new form;
		return $form->select($this->GetSuburbs($state), "Suburb", "ID", "Name", $default, $firstkey, $firstvalue, $attribs); 
	}
	
	function PrintSuburbPCSelect($attribs = FALSE) {
		$form = new form;
		$suburbs = $this->SuburbPCList();
		//print_r($suburbs);
		return $form->select($suburbs, "Suburb", "ID", "Suburb", FALSE, FALSE, FALSE, $attribs);
	}
	
	function PrintSuburbPCSelectMultiple($state = '%', $name = "Suburbs[]", $id = "ID", $value = "Name", $size = 10, $default = FALSE, $attribs = FALSE) {
		$form = new form;
		$list = $this->SuburbPCList($state);
		return $form->selectMultiple($list, $name, $id, $value, $default, $size, $attribs);
	}
	
	function SuburbPCMatches($postcode = "%", $suburb = "%") {
		$query = sprintf("SELECT 
						Postcodes_ID AS ID, 
						CONCAT(Postcodes_Suburb, ' - ', Postcodes_Postcode) AS SubPost,
						Postcodes_Suburb AS Suburb
						FROM Postcodes 
						WHERE (Postcodes_Suburb LIKE %s
							OR Postcodes_Postcode LIKE %s)				
						GROUP BY Postcodes_Suburb",
							sql::value($suburb, "text"),
							sql::value(ereg_replace( '[^0-9]+', '', $postcode ), "text")
		);
		return sql::query($query);
	}
	
	function PostcodeSearch() {
	
	
	
	}


	// Get states
	function GetStates($country = '%') {
		$query = sprintf("SELECT States_ID as ID, States_Name as Name, States_Abbrev as Abbrev, Countries_ID as Country FROM States WHERE Countries_ID LIKE %s", sql::value($country, "text"));
		return sql::query($query);
	}
	
	function GetDefaultState() {
		$state = (DEFAULT_STATE) ? DEFAULT_STATE : "%";
		return sql::SelectQuery( "States_ID AS ID, States_Name AS Name" , "States", 
			array( "States_Name" ), 
			array( $state ),
			array( "LIKE" ),
			"States_Name ASC"
		);
	}
	
	function GetStateName($state = DEFAULT_STATE) {
		$string = sprintf("SELECT States_Name AS Name FROM States WHERE States_ID = %d LIMIT 1", 
			sql::value($state, "int")
		);
		$result = sql::singleQuery($string);
		
		return $result["Name"];
	}
	
	function GetStateID($state = "VIC") {
		/*
		$query = sprintf("SELECT States_ID AS ID FROM States WHERE States_Abbrev LIKE %s", sql::value($state) );
		$results = sql::singleQuery($query);
		//echo $output = $results["ID"];
		*/
		$states = $this->States;
		$output = $states[$state];
		return $output;
	}
	
	function StateSelector($default = FALSE, $attribs = FALSE) {
		$form = new form;
		$states = $this->GetStates();
		if (!$default):
			$ds = $this->GetDefaultState();
			$default = $ds["ID"];
		endif;
		return $form->select( $states, "State", "ID", "Name", $default, 0, "Select state...", $attribs );
	}


	// Get countries
	function Countries() {
		$string = "SELECT Countries_ID AS ID, Countries_Name AS Name FROM Countries ORDER BY Countries_Name ASC";
		return sql::query($string);
	}
	
	function CountrySelector($default = FALSE, $disabled = FALSE) {
		$form = new form;
		$countries = $this->Countries();
		return $form->select($countries, "Country", "ID", "Name", $default );
	}
	
	// Get days of the month
	function Days ($month = FALSE) {
		$num = 31; 
		$list[] = array( "ID" => "", "Name" => "Day" );
		for ($i=1; $i <= $num; $i++) {
			$list[$i] = array( "ID" => $i, "Name" => $i );
		}
		return $list;
	}
	
	// Get months of the year
	function Months () {
		$num = 12;
		$list[] = array( "ID" => "", "Name" => "Month" );
		for ($i=1; $i <= $num; $i++) {
			$month = ($i<10) ? "0" . $i : $i;
			$list[$i] = array( "ID" => $month, "Name" => date('F', strtotime('2006-' . $month . '-01')) );
		}
		return $list;
	}
	
	// Get months of the year
	function Years ($start = 1900, $end = 2100) {
		$list[] = array( "ID" => "", "Name" => "Year" );
		for ($i=$start; $i <= $end; $i++) {
			$list[] = array( "ID" => $i, "Name" => $i );
		}
		return $list;
	}
	
	function TimezoneSelector($name = "timezone", $selected = FALSE, $id = FALSE, $class = FALSE) {
		if (!$id) $id = $name;
		if (!($tz = $this->timezoneArray)) $tz = $this->timezoneArray = $this->GetTimezones(); ?>
		<select name="<?= $name; ?>" id="<?= $id; ?>" <?php if ($class) : ?>class="<?= $class; ?>"<?php endif; // Class ?> >
		<option disabled selected style='display: none;' value="">Time Zone...</option>	
		<option value=""> </option>
		<?php foreach ($tz as $i => $value) : ?>
			<optgroup label="<?= $value["group"]; ?>">	
				<?php foreach($value["zones"] as $n => $zone) : ?>
					<option value="<?= $zone["value"]; ?>" <?= selectedOpt($zone["value"], $selected); ?>><?= $zone["name"]; ?></option>
				<?php endforeach; ?>
			</optgroup>
		<?php endforeach; ?>
		</select>
		<?php
	}

	function GetTimezones() {
		if (!$this->timezoneArray) {
			$timezoneJSON = '[
				{"group":"Australia",
				"zones":[
				{"value":"Australia/ACT","name":"ACT"},
				{"value":"Australia/Adelaide","name":"Adelaide"},
				{"value":"Australia/Brisbane","name":"Brisbane"},
				{"value":"Australia/Broken_Hill","name":"Broken Hill"},
				{"value":"Australia/Canberra","name":"Canberra"},
				{"value":"Australia/Currie","name":"Currie"},
				{"value":"Australia/Darwin","name":"Darwin"},
				{"value":"Australia/Eucla","name":"Eucla"},
				{"value":"Australia/Hobart","name":"Hobart"},
				{"value":"Australia/LHI","name":"LHI"},
				{"value":"Australia/Lindeman","name":"Lindeman"},
				{"value":"Australia/Lord_Howe","name":"Lord Howe"},
				{"value":"Australia/Melbourne","name":"Melbourne"},
				{"value":"Australia/North","name":"North"},
				{"value":"Australia/NSW","name":"NSW"},
				{"value":"Australia/Perth","name":"Perth"},
				{"value":"Australia/Queensland","name":"Queensland"},
				{"value":"Australia/South","name":"South"},
				{"value":"Australia/Sydney","name":"Sydney"},
				{"value":"Australia/Tasmania","name":"Tasmania"},
				{"value":"Australia/Victoria","name":"Victoria"},
				{"value":"Australia/West","name":"West"},
				{"value":"Australia/Yancowinna","name":"Yancowinna"}
				]
				},
				{"group": "US (Common)",
				"zones":[
				{"value":"America/Puerto_Rico","name":"Puerto Rico (Atlantic)"},
				{"value":"America/New_York","name":"New York (Eastern)"},
				{"value":"America/Chicago","name":"Chicago (Central)"},
				{"value":"America/Denver","name":"Denver (Mountain)"},
				{"value":"America/Phoenix","name":"Phoenix (MST)"},
				{"value":"America/Los_Angeles","name":"Los Angeles (Pacific)"},
				{"value":"America/Anchorage","name":"Anchorage (Alaska)"},
				{"value":"Pacific/Honolulu","name":"Honolulu (Hawaii)"}
				]
				},
				{"group":"America",
				"zones":[
				{"value":"America/Adak","name":"Adak"},
				{"value":"America/Anchorage","name":"Anchorage"},
				{"value":"America/Anguilla","name":"Anguilla"},
				{"value":"America/Antigua","name":"Antigua"},
				{"value":"America/Araguaina","name":"Araguaina"},
				{"value":"America/Argentina/Buenos_Aires","name":"Argentina - Buenos Aires"},
				{"value":"America/Argentina/Catamarca","name":"Argentina - Catamarca"},
				{"value":"America/Argentina/ComodRivadavia","name":"Argentina - ComodRivadavia"},
				{"value":"America/Argentina/Cordoba","name":"Argentina - Cordoba"},
				{"value":"America/Argentina/Jujuy","name":"Argentina - Jujuy"},
				{"value":"America/Argentina/La_Rioja","name":"Argentina - La Rioja"},
				{"value":"America/Argentina/Mendoza","name":"Argentina - Mendoza"},
				{"value":"America/Argentina/Rio_Gallegos","name":"Argentina - Rio Gallegos"},
				{"value":"America/Argentina/Salta","name":"Argentina - Salta"},
				{"value":"America/Argentina/San_Juan","name":"Argentina - San Juan"},
				{"value":"America/Argentina/San_Luis","name":"Argentina - San Luis"},
				{"value":"America/Argentina/Tucuman","name":"Argentina - Tucuman"},
				{"value":"America/Argentina/Ushuaia","name":"Argentina - Ushuaia"},
				{"value":"America/Aruba","name":"Aruba"},
				{"value":"America/Asuncion","name":"Asuncion"},
				{"value":"America/Atikokan","name":"Atikokan"},
				{"value":"America/Atka","name":"Atka"},
				{"value":"America/Bahia","name":"Bahia"},
				{"value":"America/Barbados","name":"Barbados"},
				{"value":"America/Belem","name":"Belem"},
				{"value":"America/Belize","name":"Belize"},
				{"value":"America/Blanc-Sablon","name":"Blanc-Sablon"},
				{"value":"America/Boa_Vista","name":"Boa Vista"},
				{"value":"America/Bogota","name":"Bogota"},
				{"value":"America/Boise","name":"Boise"},
				{"value":"America/Buenos_Aires","name":"Buenos Aires"},
				{"value":"America/Cambridge_Bay","name":"Cambridge Bay"},
				{"value":"America/Campo_Grande","name":"Campo Grande"},
				{"value":"America/Cancun","name":"Cancun"},
				{"value":"America/Caracas","name":"Caracas"},
				{"value":"America/Catamarca","name":"Catamarca"},
				{"value":"America/Cayenne","name":"Cayenne"},
				{"value":"America/Cayman","name":"Cayman"},
				{"value":"America/Chicago","name":"Chicago"},
				{"value":"America/Chihuahua","name":"Chihuahua"},
				{"value":"America/Coral_Harbour","name":"Coral Harbour"},
				{"value":"America/Cordoba","name":"Cordoba"},
				{"value":"America/Costa_Rica","name":"Costa Rica"},
				{"value":"America/Cuiaba","name":"Cuiaba"},
				{"value":"America/Curacao","name":"Curacao"},
				{"value":"America/Danmarkshavn","name":"Danmarkshavn"},
				{"value":"America/Dawson","name":"Dawson"},
				{"value":"America/Dawson_Creek","name":"Dawson Creek"},
				{"value":"America/Denver","name":"Denver"},
				{"value":"America/Detroit","name":"Detroit"},
				{"value":"America/Dominica","name":"Dominica"},
				{"value":"America/Edmonton","name":"Edmonton"},
				{"value":"America/Eirunepe","name":"Eirunepe"},
				{"value":"America/El_Salvador","name":"El Salvador"},
				{"value":"America/Ensenada","name":"Ensenada"},
				{"value":"America/Fortaleza","name":"Fortaleza"},
				{"value":"America/Fort_Wayne","name":"Fort Wayne"},
				{"value":"America/Glace_Bay","name":"Glace Bay"},
				{"value":"America/Godthab","name":"Godthab"},
				{"value":"America/Goose_Bay","name":"Goose Bay"},
				{"value":"America/Grand_Turk","name":"Grand Turk"},
				{"value":"America/Grenada","name":"Grenada"},
				{"value":"America/Guadeloupe","name":"Guadeloupe"},
				{"value":"America/Guatemala","name":"Guatemala"},
				{"value":"America/Guayaquil","name":"Guayaquil"},
				{"value":"America/Guyana","name":"Guyana"},
				{"value":"America/Halifax","name":"Halifax"},
				{"value":"America/Havana","name":"Havana"},
				{"value":"America/Hermosillo","name":"Hermosillo"},
				{"value":"America/Indiana/Indianapolis","name":"Indiana - Indianapolis"},
				{"value":"America/Indiana/Knox","name":"Indiana - Knox"},
				{"value":"America/Indiana/Marengo","name":"Indiana - Marengo"},
				{"value":"America/Indiana/Petersburg","name":"Indiana - Petersburg"},
				{"value":"America/Indiana/Tell_City","name":"Indiana - Tell City"},
				{"value":"America/Indiana/Vevay","name":"Indiana - Vevay"},
				{"value":"America/Indiana/Vincennes","name":"Indiana - Vincennes"},
				{"value":"America/Indiana/Winamac","name":"Indiana - Winamac"},
				{"value":"America/Indianapolis","name":"Indianapolis"},
				{"value":"America/Inuvik","name":"Inuvik"},
				{"value":"America/Iqaluit","name":"Iqaluit"},
				{"value":"America/Jamaica","name":"Jamaica"},
				{"value":"America/Jujuy","name":"Jujuy"},
				{"value":"America/Juneau","name":"Juneau"},
				{"value":"America/Kentucky/Louisville","name":"Kentucky - Louisville"},
				{"value":"America/Kentucky/Monticello","name":"Kentucky - Monticello"},
				{"value":"America/Knox_IN","name":"Knox IN"},
				{"value":"America/La_Paz","name":"La Paz"},
				{"value":"America/Lima","name":"Lima"},
				{"value":"America/Los_Angeles","name":"Los Angeles"},
				{"value":"America/Louisville","name":"Louisville"},
				{"value":"America/Maceio","name":"Maceio"},
				{"value":"America/Managua","name":"Managua"},
				{"value":"America/Manaus","name":"Manaus"},
				{"value":"America/Marigot","name":"Marigot"},
				{"value":"America/Martinique","name":"Martinique"},
				{"value":"America/Matamoros","name":"Matamoros"},
				{"value":"America/Mazatlan","name":"Mazatlan"},
				{"value":"America/Mendoza","name":"Mendoza"},
				{"value":"America/Menominee","name":"Menominee"},
				{"value":"America/Merida","name":"Merida"},
				{"value":"America/Mexico_City","name":"Mexico City"},
				{"value":"America/Miquelon","name":"Miquelon"},
				{"value":"America/Moncton","name":"Moncton"},
				{"value":"America/Monterrey","name":"Monterrey"},
				{"value":"America/Montevideo","name":"Montevideo"},
				{"value":"America/Montreal","name":"Montreal"},
				{"value":"America/Montserrat","name":"Montserrat"},
				{"value":"America/Nassau","name":"Nassau"},
				{"value":"America/New_York","name":"New York"},
				{"value":"America/Nipigon","name":"Nipigon"},
				{"value":"America/Nome","name":"Nome"},
				{"value":"America/Noronha","name":"Noronha"},
				{"value":"America/North_Dakota/Center","name":"North Dakota - Center"},
				{"value":"America/North_Dakota/New_Salem","name":"North Dakota - New Salem"},
				{"value":"America/Ojinaga","name":"Ojinaga"},
				{"value":"America/Panama","name":"Panama"},
				{"value":"America/Pangnirtung","name":"Pangnirtung"},
				{"value":"America/Paramaribo","name":"Paramaribo"},
				{"value":"America/Phoenix","name":"Phoenix"},
				{"value":"America/Port-au-Prince","name":"Port-au-Prince"},
				{"value":"America/Porto_Acre","name":"Porto Acre"},
				{"value":"America/Port_of_Spain","name":"Port of Spain"},
				{"value":"America/Porto_Velho","name":"Porto Velho"},
				{"value":"America/Puerto_Rico","name":"Puerto Rico"},
				{"value":"America/Rainy_River","name":"Rainy River"},
				{"value":"America/Rankin_Inlet","name":"Rankin Inlet"},
				{"value":"America/Recife","name":"Recife"},
				{"value":"America/Regina","name":"Regina"},
				{"value":"America/Resolute","name":"Resolute"},
				{"value":"America/Rio_Branco","name":"Rio Branco"},
				{"value":"America/Rosario","name":"Rosario"},
				{"value":"America/Santa_Isabel","name":"Santa Isabel"},
				{"value":"America/Santarem","name":"Santarem"},
				{"value":"America/Santiago","name":"Santiago"},
				{"value":"America/Santo_Domingo","name":"Santo Domingo"},
				{"value":"America/Sao_Paulo","name":"Sao Paulo"},
				{"value":"America/Scoresbysund","name":"Scoresbysund"},
				{"value":"America/Shiprock","name":"Shiprock"},
				{"value":"America/St_Barthelemy","name":"St Barthelemy"},
				{"value":"America/St_Johns","name":"St Johns"},
				{"value":"America/St_Kitts","name":"St Kitts"},
				{"value":"America/St_Lucia","name":"St Lucia"},
				{"value":"America/St_Thomas","name":"St Thomas"},
				{"value":"America/St_Vincent","name":"St Vincent"},
				{"value":"America/Swift_Current","name":"Swift Current"},
				{"value":"America/Tegucigalpa","name":"Tegucigalpa"},
				{"value":"America/Thule","name":"Thule"},
				{"value":"America/Thunder_Bay","name":"Thunder Bay"},
				{"value":"America/Tijuana","name":"Tijuana"},
				{"value":"America/Toronto","name":"Toronto"},
				{"value":"America/Tortola","name":"Tortola"},
				{"value":"America/Vancouver","name":"Vancouver"},
				{"value":"America/Virgin","name":"Virgin"},
				{"value":"America/Whitehorse","name":"Whitehorse"},
				{"value":"America/Winnipeg","name":"Winnipeg"},
				{"value":"America/Yakutat","name":"Yakutat"},
				{"value":"America/Yellowknife","name":"Yellowknife"}
				]
				},
				{"group":"Europe",
				"zones":[
				{"value":"Europe/Amsterdam","name":"Amsterdam"},
				{"value":"Europe/Andorra","name":"Andorra"},
				{"value":"Europe/Athens","name":"Athens"},
				{"value":"Europe/Belfast","name":"Belfast"},
				{"value":"Europe/Belgrade","name":"Belgrade"},
				{"value":"Europe/Berlin","name":"Berlin"},
				{"value":"Europe/Bratislava","name":"Bratislava"},
				{"value":"Europe/Brussels","name":"Brussels"},
				{"value":"Europe/Bucharest","name":"Bucharest"},
				{"value":"Europe/Budapest","name":"Budapest"},
				{"value":"Europe/Chisinau","name":"Chisinau"},
				{"value":"Europe/Copenhagen","name":"Copenhagen"},
				{"value":"Europe/Dublin","name":"Dublin"},
				{"value":"Europe/Gibraltar","name":"Gibraltar"},
				{"value":"Europe/Guernsey","name":"Guernsey"},
				{"value":"Europe/Helsinki","name":"Helsinki"},
				{"value":"Europe/Isle_of_Man","name":"Isle of Man"},
				{"value":"Europe/Istanbul","name":"Istanbul"},
				{"value":"Europe/Jersey","name":"Jersey"},
				{"value":"Europe/Kaliningrad","name":"Kaliningrad"},
				{"value":"Europe/Kiev","name":"Kiev"},
				{"value":"Europe/Lisbon","name":"Lisbon"},
				{"value":"Europe/Ljubljana","name":"Ljubljana"},
				{"value":"Europe/London","name":"London"},
				{"value":"Europe/Luxembourg","name":"Luxembourg"},
				{"value":"Europe/Madrid","name":"Madrid"},
				{"value":"Europe/Malta","name":"Malta"},
				{"value":"Europe/Mariehamn","name":"Mariehamn"},
				{"value":"Europe/Minsk","name":"Minsk"},
				{"value":"Europe/Monaco","name":"Monaco"},
				{"value":"Europe/Moscow","name":"Moscow"},
				{"value":"Europe/Nicosia","name":"Nicosia"},
				{"value":"Europe/Oslo","name":"Oslo"},
				{"value":"Europe/Paris","name":"Paris"},
				{"value":"Europe/Podgorica","name":"Podgorica"},
				{"value":"Europe/Prague","name":"Prague"},
				{"value":"Europe/Riga","name":"Riga"},
				{"value":"Europe/Rome","name":"Rome"},
				{"value":"Europe/Samara","name":"Samara"},
				{"value":"Europe/San_Marino","name":"San Marino"},
				{"value":"Europe/Sarajevo","name":"Sarajevo"},
				{"value":"Europe/Simferopol","name":"Simferopol"},
				{"value":"Europe/Skopje","name":"Skopje"},
				{"value":"Europe/Sofia","name":"Sofia"},
				{"value":"Europe/Stockholm","name":"Stockholm"},
				{"value":"Europe/Tallinn","name":"Tallinn"},
				{"value":"Europe/Tirane","name":"Tirane"},
				{"value":"Europe/Tiraspol","name":"Tiraspol"},
				{"value":"Europe/Uzhgorod","name":"Uzhgorod"},
				{"value":"Europe/Vaduz","name":"Vaduz"},
				{"value":"Europe/Vatican","name":"Vatican"},
				{"value":"Europe/Vienna","name":"Vienna"},
				{"value":"Europe/Vilnius","name":"Vilnius"},
				{"value":"Europe/Volgograd","name":"Volgograd"},
				{"value":"Europe/Warsaw","name":"Warsaw"},
				{"value":"Europe/Zagreb","name":"Zagreb"},
				{"value":"Europe/Zaporozhye","name":"Zaporozhye"},
				{"value":"Europe/Zurich","name":"Zurich"}
				]
				},
				{"group":"Asia",
				"zones":[
				{"value":"Asia/Aden","name":"Aden"},
				{"value":"Asia/Almaty","name":"Almaty"},
				{"value":"Asia/Amman","name":"Amman"},
				{"value":"Asia/Anadyr","name":"Anadyr"},
				{"value":"Asia/Aqtau","name":"Aqtau"},
				{"value":"Asia/Aqtobe","name":"Aqtobe"},
				{"value":"Asia/Ashgabat","name":"Ashgabat"},
				{"value":"Asia/Ashkhabad","name":"Ashkhabad"},
				{"value":"Asia/Baghdad","name":"Baghdad"},
				{"value":"Asia/Bahrain","name":"Bahrain"},
				{"value":"Asia/Baku","name":"Baku"},
				{"value":"Asia/Bangkok","name":"Bangkok"},
				{"value":"Asia/Beirut","name":"Beirut"},
				{"value":"Asia/Bishkek","name":"Bishkek"},
				{"value":"Asia/Brunei","name":"Brunei"},
				{"value":"Asia/Calcutta","name":"Calcutta"},
				{"value":"Asia/Choibalsan","name":"Choibalsan"},
				{"value":"Asia/Chongqing","name":"Chongqing"},
				{"value":"Asia/Chungking","name":"Chungking"},
				{"value":"Asia/Colombo","name":"Colombo"},
				{"value":"Asia/Dacca","name":"Dacca"},
				{"value":"Asia/Damascus","name":"Damascus"},
				{"value":"Asia/Dhaka","name":"Dhaka"},
				{"value":"Asia/Dili","name":"Dili"},
				{"value":"Asia/Dubai","name":"Dubai"},
				{"value":"Asia/Dushanbe","name":"Dushanbe"},
				{"value":"Asia/Gaza","name":"Gaza"},
				{"value":"Asia/Harbin","name":"Harbin"},
				{"value":"Asia/Ho_Chi_Minh","name":"Ho Chi Minh"},
				{"value":"Asia/Hong_Kong","name":"Hong Kong"},
				{"value":"Asia/Hovd","name":"Hovd"},
				{"value":"Asia/Irkutsk","name":"Irkutsk"},
				{"value":"Asia/Istanbul","name":"Istanbul"},
				{"value":"Asia/Jakarta","name":"Jakarta"},
				{"value":"Asia/Jayapura","name":"Jayapura"},
				{"value":"Asia/Jerusalem","name":"Jerusalem"},
				{"value":"Asia/Kabul","name":"Kabul"},
				{"value":"Asia/Kamchatka","name":"Kamchatka"},
				{"value":"Asia/Karachi","name":"Karachi"},
				{"value":"Asia/Kashgar","name":"Kashgar"},
				{"value":"Asia/Kathmandu","name":"Kathmandu"},
				{"value":"Asia/Katmandu","name":"Katmandu"},
				{"value":"Asia/Kolkata","name":"Kolkata"},
				{"value":"Asia/Krasnoyarsk","name":"Krasnoyarsk"},
				{"value":"Asia/Kuala_Lumpur","name":"Kuala Lumpur"},
				{"value":"Asia/Kuching","name":"Kuching"},
				{"value":"Asia/Kuwait","name":"Kuwait"},
				{"value":"Asia/Macao","name":"Macao"},
				{"value":"Asia/Macau","name":"Macau"},
				{"value":"Asia/Magadan","name":"Magadan"},
				{"value":"Asia/Makassar","name":"Makassar"},
				{"value":"Asia/Manila","name":"Manila"},
				{"value":"Asia/Muscat","name":"Muscat"},
				{"value":"Asia/Nicosia","name":"Nicosia"},
				{"value":"Asia/Novokuznetsk","name":"Novokuznetsk"},
				{"value":"Asia/Novosibirsk","name":"Novosibirsk"},
				{"value":"Asia/Omsk","name":"Omsk"},
				{"value":"Asia/Oral","name":"Oral"},
				{"value":"Asia/Phnom_Penh","name":"Phnom Penh"},
				{"value":"Asia/Pontianak","name":"Pontianak"},
				{"value":"Asia/Pyongyang","name":"Pyongyang"},
				{"value":"Asia/Qatar","name":"Qatar"},
				{"value":"Asia/Qyzylorda","name":"Qyzylorda"},
				{"value":"Asia/Rangoon","name":"Rangoon"},
				{"value":"Asia/Riyadh","name":"Riyadh"},
				{"value":"Asia/Saigon","name":"Saigon"},
				{"value":"Asia/Sakhalin","name":"Sakhalin"},
				{"value":"Asia/Samarkand","name":"Samarkand"},
				{"value":"Asia/Seoul","name":"Seoul"},
				{"value":"Asia/Shanghai","name":"Shanghai"},
				{"value":"Asia/Singapore","name":"Singapore"},
				{"value":"Asia/Taipei","name":"Taipei"},
				{"value":"Asia/Tashkent","name":"Tashkent"},
				{"value":"Asia/Tbilisi","name":"Tbilisi"},
				{"value":"Asia/Tehran","name":"Tehran"},
				{"value":"Asia/Tel_Aviv","name":"Tel Aviv"},
				{"value":"Asia/Thimbu","name":"Thimbu"},
				{"value":"Asia/Thimphu","name":"Thimphu"},
				{"value":"Asia/Tokyo","name":"Tokyo"},
				{"value":"Asia/Ujung_Pandang","name":"Ujung Pandang"},
				{"value":"Asia/Ulaanbaatar","name":"Ulaanbaatar"},
				{"value":"Asia/Ulan_Bator","name":"Ulan Bator"},
				{"value":"Asia/Urumqi","name":"Urumqi"},
				{"value":"Asia/Vientiane","name":"Vientiane"},
				{"value":"Asia/Vladivostok","name":"Vladivostok"},
				{"value":"Asia/Yakutsk","name":"Yakutsk"},
				{"value":"Asia/Yekaterinburg","name":"Yekaterinburg"},
				{"value":"Asia/Yerevan","name":"Yerevan"}
				]
				},
				{"group":"Africa",
				"zones":[
				{"value":"Africa/Abidjan","name":"Abidjan"},
				{"value":"Africa/Accra","name":"Accra"},
				{"value":"Africa/Addis_Ababa","name":"Addis Ababa"},
				{"value":"Africa/Algiers","name":"Algiers"},
				{"value":"Africa/Asmara","name":"Asmara"},
				{"value":"Africa/Asmera","name":"Asmera"},
				{"value":"Africa/Bamako","name":"Bamako"},
				{"value":"Africa/Bangui","name":"Bangui"},
				{"value":"Africa/Banjul","name":"Banjul"},
				{"value":"Africa/Bissau","name":"Bissau"},
				{"value":"Africa/Blantyre","name":"Blantyre"},
				{"value":"Africa/Brazzaville","name":"Brazzaville"},
				{"value":"Africa/Bujumbura","name":"Bujumbura"},
				{"value":"Africa/Cairo","name":"Cairo"},
				{"value":"Africa/Casablanca","name":"Casablanca"},
				{"value":"Africa/Ceuta","name":"Ceuta"},
				{"value":"Africa/Conakry","name":"Conakry"},
				{"value":"Africa/Dakar","name":"Dakar"},
				{"value":"Africa/Dar_es_Salaam","name":"Dar es Salaam"},
				{"value":"Africa/Djibouti","name":"Djibouti"},
				{"value":"Africa/Douala","name":"Douala"},
				{"value":"Africa/El_Aaiun","name":"El Aaiun"},
				{"value":"Africa/Freetown","name":"Freetown"},
				{"value":"Africa/Gaborone","name":"Gaborone"},
				{"value":"Africa/Harare","name":"Harare"},
				{"value":"Africa/Johannesburg","name":"Johannesburg"},
				{"value":"Africa/Kampala","name":"Kampala"},
				{"value":"Africa/Khartoum","name":"Khartoum"},
				{"value":"Africa/Kigali","name":"Kigali"},
				{"value":"Africa/Kinshasa","name":"Kinshasa"},
				{"value":"Africa/Lagos","name":"Lagos"},
				{"value":"Africa/Libreville","name":"Libreville"},
				{"value":"Africa/Lome","name":"Lome"},
				{"value":"Africa/Luanda","name":"Luanda"},
				{"value":"Africa/Lubumbashi","name":"Lubumbashi"},
				{"value":"Africa/Lusaka","name":"Lusaka"},
				{"value":"Africa/Malabo","name":"Malabo"},
				{"value":"Africa/Maputo","name":"Maputo"},
				{"value":"Africa/Maseru","name":"Maseru"},
				{"value":"Africa/Mbabane","name":"Mbabane"},
				{"value":"Africa/Mogadishu","name":"Mogadishu"},
				{"value":"Africa/Monrovia","name":"Monrovia"},
				{"value":"Africa/Nairobi","name":"Nairobi"},
				{"value":"Africa/Ndjamena","name":"Ndjamena"},
				{"value":"Africa/Niamey","name":"Niamey"},
				{"value":"Africa/Nouakchott","name":"Nouakchott"},
				{"value":"Africa/Ouagadougou","name":"Ouagadougou"},
				{"value":"Africa/Porto-Novo","name":"Porto-Novo"},
				{"value":"Africa/Sao_Tome","name":"Sao Tome"},
				{"value":"Africa/Timbuktu","name":"Timbuktu"},
				{"value":"Africa/Tripoli","name":"Tripoli"},
				{"value":"Africa/Tunis","name":"Tunis"},
				{"value":"Africa/Windhoek","name":"Windhoek"}
				]
				},

				{"group":"Indian",
				"zones":[
				{"value":"Indian/Antananarivo","name":"Antananarivo"},
				{"value":"Indian/Chagos","name":"Chagos"},
				{"value":"Indian/Christmas","name":"Christmas"},
				{"value":"Indian/Cocos","name":"Cocos"},
				{"value":"Indian/Comoro","name":"Comoro"},
				{"value":"Indian/Kerguelen","name":"Kerguelen"},
				{"value":"Indian/Mahe","name":"Mahe"},
				{"value":"Indian/Maldives","name":"Maldives"},
				{"value":"Indian/Mauritius","name":"Mauritius"},
				{"value":"Indian/Mayotte","name":"Mayotte"},
				{"value":"Indian/Reunion","name":"Reunion"}
				]
				},
				{"group":"Atlantic",
				"zones":[
				{"value":"Atlantic/Azores","name":"Azores"},
				{"value":"Atlantic/Bermuda","name":"Bermuda"},
				{"value":"Atlantic/Canary","name":"Canary"},
				{"value":"Atlantic/Cape_Verde","name":"Cape Verde"},
				{"value":"Atlantic/Faeroe","name":"Faeroe"},
				{"value":"Atlantic/Faroe","name":"Faroe"},
				{"value":"Atlantic/Jan_Mayen","name":"Jan Mayen"},
				{"value":"Atlantic/Madeira","name":"Madeira"},
				{"value":"Atlantic/Reykjavik","name":"Reykjavik"},
				{"value":"Atlantic/South_Georgia","name":"South Georgia"},
				{"value":"Atlantic/Stanley","name":"Stanley"},
				{"value":"Atlantic/St_Helena","name":"St Helena"}
				]
				},
				{"group":"Pacific",
				"zones":[
				{"value":"Pacific/Apia","name":"Apia"},
				{"value":"Pacific/Auckland","name":"Auckland"},
				{"value":"Pacific/Chatham","name":"Chatham"},
				{"value":"Pacific/Easter","name":"Easter"},
				{"value":"Pacific/Efate","name":"Efate"},
				{"value":"Pacific/Enderbury","name":"Enderbury"},
				{"value":"Pacific/Fakaofo","name":"Fakaofo"},
				{"value":"Pacific/Fiji","name":"Fiji"},
				{"value":"Pacific/Funafuti","name":"Funafuti"},
				{"value":"Pacific/Galapagos","name":"Galapagos"},
				{"value":"Pacific/Gambier","name":"Gambier"},
				{"value":"Pacific/Guadalcanal","name":"Guadalcanal"},
				{"value":"Pacific/Guam","name":"Guam"},
				{"value":"Pacific/Honolulu","name":"Honolulu"},
				{"value":"Pacific/Johnston","name":"Johnston"},
				{"value":"Pacific/Kiritimati","name":"Kiritimati"},
				{"value":"Pacific/Kosrae","name":"Kosrae"},
				{"value":"Pacific/Kwajalein","name":"Kwajalein"},
				{"value":"Pacific/Majuro","name":"Majuro"},
				{"value":"Pacific/Marquesas","name":"Marquesas"},
				{"value":"Pacific/Midway","name":"Midway"},
				{"value":"Pacific/Nauru","name":"Nauru"},
				{"value":"Pacific/Niue","name":"Niue"},
				{"value":"Pacific/Norfolk","name":"Norfolk"},
				{"value":"Pacific/Noumea","name":"Noumea"},
				{"value":"Pacific/Pago_Pago","name":"Pago Pago"},
				{"value":"Pacific/Palau","name":"Palau"},
				{"value":"Pacific/Pitcairn","name":"Pitcairn"},
				{"value":"Pacific/Ponape","name":"Ponape"},
				{"value":"Pacific/Port_Moresby","name":"Port Moresby"},
				{"value":"Pacific/Rarotonga","name":"Rarotonga"},
				{"value":"Pacific/Saipan","name":"Saipan"},
				{"value":"Pacific/Samoa","name":"Samoa"},
				{"value":"Pacific/Tahiti","name":"Tahiti"},
				{"value":"Pacific/Tarawa","name":"Tarawa"},
				{"value":"Pacific/Tongatapu","name":"Tongatapu"},
				{"value":"Pacific/Truk","name":"Truk"},
				{"value":"Pacific/Wake","name":"Wake"},
				{"value":"Pacific/Wallis","name":"Wallis"},
				{"value":"Pacific/Yap","name":"Yap"}
				]
				},
				{"group":"Antarctica",
				"zones":[
				{"value":"Antarctica/Casey","name":"Casey"},
				{"value":"Antarctica/Davis","name":"Davis"},
				{"value":"Antarctica/DumontDUrville","name":"DumontDUrville"},
				{"value":"Antarctica/Macquarie","name":"Macquarie"},
				{"value":"Antarctica/Mawson","name":"Mawson"},
				{"value":"Antarctica/McMurdo","name":"McMurdo"},
				{"value":"Antarctica/Palmer","name":"Palmer"},
				{"value":"Antarctica/Rothera","name":"Rothera"},
				{"value":"Antarctica/South_Pole","name":"South Pole"},
				{"value":"Antarctica/Syowa","name":"Syowa"},
				{"value":"Antarctica/Vostok","name":"Vostok"}
				]
				},
				{"group":"Arctic",
				"zones":[
				{"value":"Arctic/Longyearbyen","name":"Longyearbyen"}
				]
				},
				{"group":"UTC",
				"zones":[
				{"value":"UTC","name":"UTC"}
				]
				},
				{"group":"Manual Offsets",
				"zones":[
				{"value":"UTC-12","name":"UTC-12"},
				{"value":"UTC-11","name":"UTC-11"},
				{"value":"UTC-10","name":"UTC-10"},
				{"value":"UTC-9","name":"UTC-9"},
				{"value":"UTC-8","name":"UTC-8"},
				{"value":"UTC-7","name":"UTC-7"},
				{"value":"UTC-6","name":"UTC-6"},
				{"value":"UTC-5","name":"UTC-5"},
				{"value":"UTC-4","name":"UTC-4"},
				{"value":"UTC-3","name":"UTC-3"},
				{"value":"UTC-2","name":"UTC-2"},
				{"value":"UTC-1","name":"UTC-1"},
				{"value":"UTC+0","name":"UTC+0"},
				{"value":"UTC+1","name":"UTC+1"},
				{"value":"UTC+2","name":"UTC+2"},
				{"value":"UTC+3","name":"UTC+3"},
				{"value":"UTC+4","name":"UTC+4"},
				{"value":"UTC+5","name":"UTC+5"},
				{"value":"UTC+6","name":"UTC+6"},
				{"value":"UTC+7","name":"UTC+7"},
				{"value":"UTC+8","name":"UTC+8"},
				{"value":"UTC+9","name":"UTC+9"},
				{"value":"UTC+10","name":"UTC+10"},
				{"value":"UTC+11","name":"UTC+11"},
				{"value":"UTC+12","name":"UTC+12"},
				{"value":"UTC+13","name":"UTC+13"},
				{"value":"UTC+14","name":"UTC+14"}
				]
				}
				]';
			$this->timezoneArray = json_decode($timezoneJSON, TRUE);
		}	
		return $this->timezoneArray;
	}	
}






/**
 * Array2XML: A class to convert array in PHP to XML
 * It also takes into account attributes names unlike SimpleXML in PHP
 * It returns the XML in form of DOMDocument class for further manipulation.
 * It throws exception if the tag name or attribute name has illegal chars.
 *
 * Author : Lalit Patel
 * Website: http://www.lalit.org/lab/convert-php-array-to-xml-with-attributes
 * License: Apache License 2.0
 *          http://www.apache.org/licenses/LICENSE-2.0
 * Version: 0.1 (10 July 2011)
 * Version: 0.2 (16 August 2011)
 *          - replaced htmlentities() with htmlspecialchars() (Thanks to Liel Dulev)
 *          - fixed a edge case where root node has a false/null/0 value. (Thanks to Liel Dulev)
 * Version: 0.3 (22 August 2011)
 *          - fixed tag sanitize regex which didn't allow tagnames with single character.
 * Version: 0.4 (18 September 2011)
 *          - Added support for CDATA section using @cdata instead of @value.
 * Version: 0.5 (07 December 2011)
 *          - Changed logic to check numeric array indices not starting from 0.
 * Version: 0.6 (04 March 2012)
 *          - Code now doesn't @cdata to be placed in an empty array
 * Version: 0.7 (24 March 2012)
 *          - Reverted to version 0.5
 * Version: 0.8 (02 May 2012)
 *          - Removed htmlspecialchars() before adding to text node or attributes.
 *
 * Usage:
 *       $xml = Array2XML::createXML('root_node_name', $php_array);
 *       echo $xml->saveXML();
 */

class Array2XML {

    private static $xml = null;
	private static $encoding = 'UTF-8';

    /**
     * Initialize the root XML node [optional]
     * @param $version
     * @param $encoding
     * @param $format_output
     */
    public static function init($version = '1.0', $encoding = 'UTF-8', $format_output = TRUE) {
        self::$xml = new DomDocument($version, $encoding);
        self::$xml->formatOutput = $format_output;
		self::$encoding = $encoding;
    }

    /**
     * Convert an Array to XML
     * @param string $node_name - name of the root node to be converted
     * @param array $arr - aray to be converterd
     * @return DomDocument
     */
    public static function &createXML($node_name, $arr=array()) {
        $xml = self::getXMLRoot();
        $xml->appendChild(self::convert($node_name, $arr));

        self::$xml = null;    // clear the xml node in the class for 2nd time use.
        return $xml;
    }

    /**
     * Convert an Array to XML
     * @param string $node_name - name of the root node to be converted
     * @param array $arr - aray to be converterd
     * @return DOMNode
     */
    private static function &convert($node_name, $arr=array()) {

        //print_arr($node_name);
        $xml = self::getXMLRoot();
        $node = $xml->createElement($node_name);

        if(is_array($arr)){
            // get the attributes first.;
            if (isset($arr['@attributes'])) {
                foreach($arr['@attributes'] as $key => $value) {
                    if(!self::isValidTagName($key)) {
                        throw new Exception('[Array2XML] Illegal character in attribute name. attribute: '.$key.' in node: '.$node_name);
                    }
                    $node->setAttribute($key, self::bool2str($value));
                }
                unset($arr['@attributes']); //remove the key from the array once done.
            }

            // check if it has a value stored in @value, if yes store the value and return
            // else check if its directly stored as string
            if (isset($arr['@value'])) {
                $node->appendChild($xml->createTextNode(self::bool2str($arr['@value'])));
                unset($arr['@value']);    //remove the key from the array once done.
                //return from recursion, as a note with value cannot have child nodes.
                return $node;
            } elseif (isset($arr['@cdata'])) {
                $node->appendChild($xml->createCDATASection(self::bool2str($arr['@cdata'])));
                unset($arr['@cdata']);    //remove the key from the array once done.
                //return from recursion, as a note with cdata cannot have child nodes.
                return $node;
            }
        }

        //create subnodes using recursion
        if(is_array($arr)){
            // recurse to get the node for that key
            foreach($arr as $key=>$value){
                if(!self::isValidTagName($key)) {
                    throw new Exception('[Array2XML] Illegal character in tag name. tag: '.$key.' in node: '.$node_name);
                }
                if(is_array($value) && is_numeric(key($value))) {
                    // MORE THAN ONE NODE OF ITS KIND;
                    // if the new array is numeric index, means it is array of nodes of the same kind
                    // it should follow the parent key name
                    foreach($value as $k=>$v){
                        $node->appendChild(self::convert($key, $v));
                    }
                } else {
                    // ONLY ONE NODE OF ITS KIND
                    $node->appendChild(self::convert($key, $value));
                }
                unset($arr[$key]); //remove the key from the array once done.
            }
        }

        // after we are done with all the keys in the array (if it is one)
        // we check if it has any text value, if yes, append it.
        if(!is_array($arr)) {
            $node->appendChild($xml->createTextNode(self::bool2str($arr)));
        }

        return $node;
    }

    /*
     * Get the root XML node, if there isn't one, create it.
     */
    private static function getXMLRoot(){
        if(empty(self::$xml)) {
            self::init();
        }
        return self::$xml;
    }

    /*
     * Get string representation of boolean value
     */
    private static function bool2str($v){
        //convert boolean to text value.
        $v = $v === TRUE ? 'true' : $v;
        $v = $v === FALSE ? 'false' : $v;
        return $v;
    }

    /*
     * Check if the tag name or attribute name contains illegal characters
     * Ref: http://www.w3.org/TR/xml/#sec-common-syn
     */
    private static function isValidTagName($tag){
        $pattern = '/^[a-z_]+[a-z0-9\:\-\.\_]*[^:]*$/i';
        return preg_match($pattern, $tag, $matches) && $matches[0] == $tag;
    }
}


/**
 * XML2Array: A class to convert XML to array in PHP
 * It returns the array which can be converted back to XML using the Array2XML script
 * It takes an XML string or a DOMDocument object as an input.
 *
 * See Array2XML: http://www.lalit.org/lab/convert-php-array-to-xml-with-attributes
 *
 * Author : Lalit Patel
 * Website: http://www.lalit.org/lab/convert-xml-to-array-in-php-xml2array
 * License: Apache License 2.0
 *          http://www.apache.org/licenses/LICENSE-2.0
 * Version: 0.1 (07 Dec 2011)
 * Version: 0.2 (04 Mar 2012)
 * 			Fixed typo 'DomDocument' to 'DOMDocument'
 *
 * Usage:
 *       $array = XML2Array::createArray($xml);
 */

class XML2Array {

    private static $xml = null;
	private static $encoding = 'UTF-8';

    /**
     * Initialize the root XML node [optional]
     * @param $version
     * @param $encoding
     * @param $format_output
     */
    public static function init($version = '1.0', $encoding = 'UTF-8', $format_output = TRUE) {
        self::$xml = new DOMDocument($version, $encoding);
        self::$xml->formatOutput = $format_output;
		self::$encoding = $encoding;
    }

    /**
     * Convert an XML to Array
     * @param string $node_name - name of the root node to be converted
     * @param array $arr - aray to be converterd
     * @return DOMDocument
     */
    public static function &createArray($input_xml) {
        $xml = self::getXMLRoot();
		if(is_string($input_xml)) {
			$parsed = $xml->loadXML($input_xml);
			if(!$parsed) {
				throw new Exception('[XML2Array] Error parsing the XML string.');
			}
		} else {
			if(get_class($input_xml) != 'DOMDocument') {
				throw new Exception('[XML2Array] The input XML object should be of type: DOMDocument.');
			}
			$xml = self::$xml = $input_xml;
		}
		$array[$xml->documentElement->tagName] = self::convert($xml->documentElement);
        self::$xml = null;    // clear the xml node in the class for 2nd time use.
        return $array;
    }

    /**
     * Convert an Array to XML
     * @param mixed $node - XML as a string or as an object of DOMDocument
     * @return mixed
     */
    private static function &convert($node) {
		$output = array();

		switch ($node->nodeType) {
			case XML_CDATA_SECTION_NODE:
				$output['@cdata'] = trim($node->textContent);
				break;

			case XML_TEXT_NODE:
				$output = trim($node->textContent);
				break;

			case XML_ELEMENT_NODE:

				// for each child node, call the covert function recursively
				for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
					$child = $node->childNodes->item($i);
					$v = self::convert($child);
					if(isset($child->tagName)) {
						$t = $child->tagName;

						// assume more nodes of same kind are coming
						if(!isset($output[$t])) {
							$output[$t] = array();
						}
						$output[$t][] = $v;
					} else {
						//check if it is not an empty text node
						if($v !== '') {
							$output = $v;
						}
					}
				}

				if(is_array($output)) {
					// if only one node of its kind, assign it directly instead if array($value);
					foreach ($output as $t => $v) {
						if(is_array($v) && count($v)==1) {
							$output[$t] = $v[0];
						}
					}
					if(empty($output)) {
						//for empty nodes
						$output = '';
					}
				}

				// loop through the attributes and collect them
				if($node->attributes->length) {
					$a = array();
					foreach($node->attributes as $attrName => $attrNode) {
						$a[$attrName] = (string) $attrNode->value;
					}
					// if its an leaf node, store the value in @value instead of directly storing it.
					if(!is_array($output)) {
						$output = array('@value' => $output);
					}
					$output['@attributes'] = $a;
				}
				break;
		}
		return $output;
    }

    /*
     * Get the root XML node, if there isn't one, create it.
     */
    private static function getXMLRoot(){
        if(empty(self::$xml)) {
            self::init();
        }
        return self::$xml;
    }
}





// QUICK CSV IMPORT Object

class Quick_CSV_import {
	
	var $table_name; //where to import to
	var $file_name;  //where to import from
	var $use_csv_header; //use first line of file OR generated columns names
	var $field_separate_char; //character to separate fields
	var $field_enclose_char; //character to enclose fields, which contain separator char into content
	var $field_escape_char;  //char to escape special symbols
	var $error; //error message
	var $arr_csv_columns; //array of columns
	var $table_exists; //flag: does table for import exist
	var $encoding; //encoding table, used to parse the incoming file. Added in 1.5 version
	
	function __construct($file_name = "") {
		$this->file_name = $file_name;
		$this->arr_csv_columns = array();
		$this->use_csv_header = TRUE;
		$this->field_separate_char = ",";
		$this->field_enclose_char  = "\"";
		$this->field_escape_char   = "\\";
		$this->table_exists = FALSE;
	}
	
	
	function import() {
		// if ($this->table_name=="")
		//	$this->table_name = "temp_".date("d_m_Y_H_i_s");
		
		// $this->table_exists = false;
		// $this->create_import_table();
		
		if ( empty($this->arr_csv_columns) )
			$this->get_csv_header_fields();
		
		/* change start. Added in 1.5 version */

		if ("" != $this->encoding && "default" != $this->encoding)
			$this->set_encoding();
			
		/* change end */
		
		/*
		if ($this->table_exists) {
			$string = "LOAD DATA INFILE '" . @mysql_escape_string($this->file_name) .
				"' INTO TABLE `" . $this->table_name .
				"` FIELDS TERMINATED BY '" . @mysql_escape_string($this->field_separate_char) .
				"' OPTIONALLY ENCLOSED BY '" . @mysql_escape_string($this->field_enclose_char) .
				"' ESCAPED BY '" . @mysql_escape_string($this->field_escape_char) .
				"' " . ($this->use_csv_header ? " IGNORE 1 LINES " : "") . 
				"(`" . implode("`,`", $this->arr_csv_columns) . "`)";
				// echo $string;
			$res = @mysql_query($string);
			$this->error = "table_exists: " . mysql_error();
			if ($res) $this->response = "Records have been added to database.";
		}
		*/
		
		/*
		
		$fp = fopen('test.csv', 'r');

		// get the first (header) line
		$header = fgetcsv($fp);
		
		// get the rest of the rows
		$data = array();
		while ($row = fgetcsv($fp)) {
		  $arr = array();
		  foreach ($header as $i => $col)
			$arr[$col] = $row[$i];
		  $data[] = $arr;
		}
		
		print_r($data);
		
		*/
		
		$row = 0;
		if (($handle = fopen($this->file_name, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				for ($c=0; $c < $num; $c++) {
					$results[$row][] = $data[$c];
				}
				$row++;
			}
			fclose($handle);
		}
/*
		$results = array();

		for ($i=0; $i < sizeof($row); $i++) {
			$results[] = $row[$i];
		}
		*/
		
		return $results;
		
		// return $results = array( "error" => $this->error, "response" => $this->response );
	}
	
	
	//returns array of CSV file columns
	function get_csv_header_fields() {
		$this->arr_csv_columns = array();
		$fpointer = fopen($this->file_name, "r");
		
		if ($fpointer) {
		  $arr = fgetcsv($fpointer, 10 * 1024, $this->field_separate_char);
		  
		  if (is_array($arr) && !empty($arr)) {
			if ($this->use_csv_header) {
			  foreach($arr as $val)
				if (trim($val)!="")
				  $this->arr_csv_columns[] = $val;
			} else {
				$i = 1;
				foreach($arr as $val)
					if (trim($val)!="")
						$this->arr_csv_columns[] = "column".$i++;
				}
			}
			unset($arr);
			fclose($fpointer);
		} else
			$this->error = "file cannot be opened: " . ("" == $this->file_name ? "[empty]" : 
		
		@mysql_escape_string($this->file_name));
		
		return $this->arr_csv_columns;
	}
		
		
	function create_import_table() {
		$sql = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (";
		
		if (empty($this->arr_csv_columns))
			$this->get_csv_header_fields();
		
		if (!empty($this->arr_csv_columns)) {
			$arr = array();
			for($i = 0; $i < sizeof($this->arr_csv_columns); $i++)
				$arr[] = "`" . $this->arr_csv_columns[$i] . "` TEXT";
				$sql .= implode(",", $arr);
				$sql .= ")";
				$res = @mysql_query($sql);
				$this->error = "import table error: " . mysql_error();
				$this->table_exists = "" == mysql_error();
			}
		}
		
		
		/* change start. Added in 1.5 version */
		// returns recordset with all encoding tables names, supported by your database
		
		function get_encodings() {
			$rez = array();
			$sql = "SHOW CHARACTER SET";
			$res = @mysql_query($sql);
			if (mysql_num_rows($res) > 0) {
				while ($row = mysql_fetch_assoc ($res)) {
					//some MySQL databases return empty Description field
					$rez[$row["Charset"]] = ("" != $row["Description"] ? $row["Description"] : $row["Charset"]); 
				}
			}
			return $rez;
		}
		
		//defines the encoding of the server to parse to file
		
		function set_encoding($encoding = "") {
			if ("" == $encoding)
				$encoding = $this->encoding;

			//'character_set_database' MySQL server variable is [also] to parse file with rigth encoding
			$sql = "SET SESSION character_set_database = " . $encoding; 
			$res = @mysql_query($sql);
			return mysql_error();
		}
	
	/* change end */
}

/**
  * Formats a line (passed as a fields  array) as CSV and returns the CSV as a string.
  * Adapted from http://us3.php.net/manual/en/function.fputcsv.php#87120
  */
function arrayToCsv( array &$fields, $delimiter = ';', $enclosure = '"', $encloseAll = FALSE, $nullToMysqlNull = FALSE ) {
    $delimiter_esc = preg_quote($delimiter, '/');
    $enclosure_esc = preg_quote($enclosure, '/');

    $output = array();
    foreach ( $fields as $field ) {
        if ($field === null && $nullToMysqlNull) {
            $output[] = 'NULL';
            continue;
        }

        // Enclose fields containing $delimiter, $enclosure or whitespace
        if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
            $output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
        }
        else {
            $output[] = $field;
        }
    }

    return implode( $delimiter, $output );
}

// RANDOM STRING GENERATOR Object

class RandomString {
	
	function __construct($amount = 1, $length = 10, $numbers = TRUE, $letters = TRUE, $symbols = TRUE, $case = 3) {
		$this->MaxLength = 100;
		$this->DefaultLength = 10;
		$this->Length = ($length > $this->MaxLength) ? $this->MaxLength : $length;
		if ($this->Length < 1) $this->Length = $this->DefaultLength;
		$this->Amount = $amount;
		$this->Numbers = $numbers;
		$this->Letters = $letters;
		$this->Symbols = $symbols;
		$this->Case = $case; // Case -- 1 = lower, 2 = upper, 3 = mixed
		
		$this->Num = "0123456789";
		$this->Alpha = "abcdefghijklmnopqrstuvwxyz";
		$this->Symb = "?#@*-_!+%&$";
		$this->Pool = array();
	}

	function getRandomString($length = FALSE) {
		if ($length) $this->Length = $length;
		if ($this->Numbers) $this->Pool[] = $this->Num;
		if ($this->Symbols) $this->Pool[] = $this->Symb;
		if ($this->Letters) {
			if ($this->Case == 1) $this->Pool[] = $this->Alpha;
			if ($this->Case == 2) $this->Pool[] = strtoupper($this->Alpha);
			if ($this->Case == 3) { 
				$this->Pool[] = $this->Alpha;
				$this->Pool[] = strtoupper($this->Alpha);
			}
		}
		
		$this->TypesNum = count($this->Pool);
		
		// Generate array of strings or just one
		
		if ($this->Amount > 1) {
			$this->StringArr = array();
			for($i=0; $i < $this->Amount; $i++) {
				$this->StringArr[] = $this->GenString();
			}
			return $this->StringArr;
		} else {
			return $this->GenString();
		}
	}
	
	function GenString() {
		// Create random characters to set string length
		$this->String = "";
	
		for ($i = 0; $i < $this->Length; $i++) {
			$this->SelectPool = $this->Pool[$this->GetPoolNum()]; // Fairly chooses from each character pool
			$this->String .= $this->GenChar();
		}
		
		// String Check
		if ($this->Length >= $this->TypesNum) // If the length of string generated is greater than or equal to the amount of types of allowable chars
			$this->CheckString();

		return $this->String;
	}
	
	function GetPoolNum() {
		if ($this->TypesNum > 1) {
			$p = mt_rand(0, $this->TypesNum-1);
			if ($p == $this->PoolNum) return $this->GetPoolNum();
			else return $this->PoolNum = $p;
		} else {
			return $this->PoolNum = 0;
		}
	}
	
	function GenChar($pool = FALSE) {
		if ($pool = ($c = $this->SelectPool) ? $c : $pool)
			return $pool[mt_rand(0, strlen($pool)-1)];
	}
	
	function CheckString() {
		$redo = FALSE;
		foreach ($this->Pool as $value) {
			$this->SelectPool = $value;
			// Make sure at least one character of each selected type is in the password
			if (similar_text( $this->SelectPool, $this->String ) == 0) { // No chars of this type found in string
				// Manually change a random value in the string to a random value in the pool string
				$NewChar = $this->GenChar();
				$randompos = mt_rand(0, $this->Length-1);
				$this->String = substr_replace( $this->String, $NewChar, $randompos, 1 );

				$redo = TRUE;
				break;
			}
		}
		if ($redo) $this->CheckString();
	}

}






////////////////////////////////////
// Pagination

class pagination {
	
	var $pagination;
	
	function __construct($vars = FALSE) {
		$this->terms = $vars;
	}

	// Extract all terms to Object Variables
	function extract_terms($terms = FALSE) {
		$this->terms_extracted = FALSE;
		if ($terms) $this->terms = $terms;
		if (is_object($this->terms)) $this->terms = objectToArray($this->terms);
		if (is_array($this->terms))
			foreach ($this->terms as $_key => $_value) 
				if ($this->$_key = $_value) 
					$this->terms_extracted = TRUE;
		return $this->terms_extracted;
	}



	// Do Pagination (return page start row number)

	function do_pagination($result_count = FALSE, $results_per_page = FALSE) {
		if (!isset($this->paginated)) {
			$this->paginated = TRUE;
			$this->result_count = ($result_count) ? $result_count : $this->result_count;
			$this->results_per_page = ($results_per_page) ? $results_per_page : $this->results_per_page;
			
			// Count results
			// If Pagination is worth bothering with...
			if ($this->results_per_page && ($this->result_count > 0)) {
	
				// Calculate total pages
				$this->total_pages = $this->get_total_pages();
				
				// Set current page
				if (isset($this->block) && is_numeric($this->block) && ($this->block > 0)) {
					if ($this->block > $this->total_pages) $this->block = $this->total_pages;
					elseif ($this->block < 1) $this->block = 1;
				} 
				elseif (isset($this->property_key) && !empty($this->property_key)) $this->block = $this->get_page_from_key();
				else $this->block = 1;
				
				if ($indexes = $this->get_indexes_from_page()) 
					$this->extract_terms($indexes);
				
				// Get number of items in this batch
				$this->batch = $this->end_property_index - $this->start_property_index;
			}
		}
		return $this->batch;
	}
	
	// Get Total Pages
	function get_total_pages($result_count = FALSE, $results_per_page = FALSE) {
		if (!isset($this->total_pages)) {
			$this->result_count = ($result_count) ? $result_count : $this->result_count;
			$this->results_per_page = ($results_per_page) ? $results_per_page : $this->results_per_page;
			$this->total_pages = ceil($this->result_count / $this->results_per_page);
		}
		return $this->total_pages;
	}
	
	// Get Property Indexes from Page
	function get_indexes_from_page($block = FALSE) {
		if (!$block) $block = $this->block;
		
		// Default Start and End Property Indexes
		$array['start_property_index'] = 0;
		$array['end_property_index'] = ($this->result_count > $this->results_per_page) ? $this->results_per_page : $this->result_count;
		
		// Calculate Start and End Property Indexes
		if ($block > 1) :
			$array['start_property_index'] = ($block - 1) * $this->results_per_page;
			if ($array['start_property_index'] > $this->result_count) $array['start_property_index'] = $this->results_per_page;
			$array['end_property_index'] = $array['start_property_index'] + $this->results_per_page;
			if ($array['end_property_index'] >= $this->result_count) $array['end_property_index'] = $this->result_count;
		endif;
		
		return $array;
	}
	
	// Get Page from Property Result Key
	function get_page_from_key($key = FALSE) {
		if (!$key) $key = $this->property_key;
		if ($key && $this->results_per_page)
			$this->block = floor($key / $this->results_per_page) + 1;
		return $this->block;
	}
	
	// Get Current Page Batch
	function get_batch() {
		if (!$this->batch) $this->batch = $this->do_pagination();
		return $this->batch;
	}
	
	// Get Start Key of Current Batch
	function get_start_index($result_count = FALSE, $results_per_page = FALSE) {
		if (!$this->start_property_index) {
			$this->result_count = ($result_count) ? $result_count : $this->result_count;
			$this->results_per_page = ($results_per_page) ? $results_per_page : $this->results_per_page;
			$this->do_pagination();
		}
		return $this->start_property_index;
	}
	
	
	// CREATE PAGINATION LINKS
	
	function show($result_count = FALSE, $results_per_page = FALSE) {
		
		if (!isset($this->display)) {
			$this->result_count = ($result_count) ? $result_count : $this->result_count;
			$this->results_per_page = ($results_per_page) ? $results_per_page : $this->results_per_page;
			$this->total_pages = $this->get_total_pages();
			
			$this->do_pagination();
			
			if ($this->result_count) {
						
				// Pagination Links
				if ($this->total_pages > 1) {
					// Get Full Request URI
					$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
				
					// PREVIOUS "<" Link
					if ($this->block > 1) $renderPrev = '<div class="page_links left"><a class="page_link page_left on" href="' . addURLParam(addURLParam($url, "block=" . ($this->block - 1)), "us=true") . '"><span>&lt;</span></a></div>';
					else $renderPrev = '<div class="page_links left"><a class="page_left off"><span>&lt;</span></a></div>';
				
					// NEXT ">" Link
					if ($this->block < $this->total_pages) $renderNext = '<div class="page_links right"><a class="page_link page_right on" href="' . addURLParam(addURLParam($url, "block=" . ($this->block + 1)), "us=true") . '"><span>&gt;</span></a></div>';
					else $renderNext = '<div class="page_links right"><a class="page_right off"><span>&gt;</span></a></div>';
				
					// PAGINATION "1 | 2 | 3 " Links
					
					$total = $this->total_pages;
					$block = ($this->block) ? $this->block : 1;
					$link_count = 0;
					$link_limit = 5;
					$renderNav = false;
					$showing = false;
					
					for ($i = 1; $i <= $total; $i++) :
						// Show if total links is 5 or less -- OR -- this link is first or last or current page or either side of the current page 
						if (
							!empty($i) && 
							(
							($total <= $link_limit) || 
							(($i == 1) || ($i == $total) || ($i == $block) || ($i == ($block - 1)) || ($i == ($block + 1))) ||
							((($total - $block) <= 2) && ($i > $block) && ($i < $total)) ||
							(($i > 1) && ($block <= 3) && ($i < $block)) ||
							(($block == $total) && (($total - $i) <= 2)) ||
							(($block == 1) && ($i <= 3))
							)
						) :

							if (($i > 1) && ($i != ($last_link + 1))) $renderNav .= " <div class='ellipsis'>&#8230;</div> ";
							$link_count++;
							$last_link = $i;
							$indexes = $this->get_indexes_from_page($i);
							$link_href = addURLParam(addURLParam($url, "block=" . $i), "us=true");
							$href = ($i != $block) ? ' href="' . $link_href . '" ' : FALSE;
							$data_href = ($i == $block) ? ' data-href="' . $link_href . '" ' : FALSE;
							$class = ($href) ? "on" : "off";
							$renderNav .= '<a class="page_link ' . $class . '" ' . $href . $data_href . ' data-showing="' . $showing . '" >' . $i . '</a> ';
						endif;
					endfor;
					
					// Pagination Links Output
					//$renderNav = "<div class='nav_links'>" . $renderNav . "</div>";
					$nav_links = $renderPrev . $renderNav . $renderNext;
					$this->display["top"] = "<div class='pagination'>" . $nav_links . "</div>";
					$this->display["bottom"] = "<div class='pagination pagination-bottom'>" . $nav_links . "</div>";
				}
			}
		}
		return (isset($this->display)) ? $this->display : FALSE;
	}
}


// Currency Converter
// Convert currencies using Yahoo's currency conversion service

class CurrencyConverter {
	private $fxRate;
	
	public function __construct($currencyBase, $currencyForeign) {
		$url = 'http://download.finance.yahoo.com/d/quotes.csv?s=' . $currencyBase . $currencyForeign . '=X&f=l1';
		$c = curl_init($url);
		curl_setopt($c, CURLOPT_HEADER, 0);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		$this->fxRate = doubleval(curl_exec($c));
		curl_close($c);
	}

	public function toBase($amount) {
		if ($this->fxRate == 0) return 0;
		return $amount / $this->fxRate;
	}
	
	public function toForeign($amount) {
		if ($this->fxRate == 0) return 0;
		return $amount * $this->fxRate;
	}
}




/*Main ICS class for creating ics file for given time*/

class ICS {
    var $data;
    var $name;
	
    function __construct($start, $end, $name, $description, $location) {
        $this->name = $name;
        $this->data = "BEGIN:VCALENDAR\nVERSION:2.0\nMETHOD:PUBLISH\nBEGIN:VEVENT\nDTSTART:" . date("Ymd\THis", strtotime($start)) . "\nDTEND:" .date("Ymd\THis", strtotime($end)) . "\nLOCATION:" . $location . "\nTRANSP: OPAQUE\nSEQUENCE:0\nUID:\nDTSTAMP:" . date("Ymd\THis") . "\nSUMMARY:" . $name . "\nDESCRIPTION;ENCODING=QUOTED-PRINTABLE:" . $description . "\nPRIORITY:1\nCLASS:PUBLIC\nBEGIN:VALARM\nTRIGGER:-PT10080M\nACTION:DISPLAY\nDESCRIPTION:Reminder\nEND:VALARM\nEND:VEVENT\nEND:VCALENDAR\n";
    }
	
    function save() {
        file_put_contents($this->name . ".ics", $this->data);
    }
	
    function show() {
        header("Content-type:text/calendar");
        header('Content-Disposition: attachment; filename="' . $this->name . '.ics"');
        Header('Content-Length: ' . strlen($this->data));
        Header('Connection: close');
        echo $this->data;
    }
}

/*Function to convert UNIX timestamp to ical format */

function unixToiCal($uStamp = 0, $tzone = 0.0) {
    $uStampUTC = $uStamp + ($tzone * 3600);
    $stamp  = date("Ymd\THis\Z", $uStampUTC);
    return $stamp;
}


/* Main vCard class for creating vCard of employee details*/

class VCF {
    var $data;
    var $name;
	
    function __construct($data) {
        $this->name = $data['first_name'] . " " . $data['last_name'];
        $this->data = "BEGIN:VCARD\nVERSION:3\nREV:" . date("Ymd\THis\Z") . "\nFN:" . $data['first_name'] . " " . $data['last_name'] . "\nN:" . $data['last_name'] . ";" . $data['first_name'] . "\nORG:" . $data['company_name'] . "\nTITLE:" . $data['role'] . "\nADR;WORK;ENCODING=QUOTED-PRINTABLE:;;" . $data['unit_number'] . ";" . $data['street_number'] . "=0A" . $data['street'] . ";" . $data['company_locality'] . ";" . $data['region'] . ";" . $data['postal_code'] . "\nTEL;WORK;VOICE:" . $data['phone'] . "\nTEL;CELL;VOICE:" . $data['mobile'] . "\nEMAIL;WORK;INTERNET:" . $data['email'] . "\nURL;WORK:" . $data['url'] . "\nEND:VCARD";
    }
	
    function save() {
        file_put_contents($this->name.'.vcf', $this->data);
    }
	
    function show() {
        header('Content-type:text/x-vcard');
        header('Content-Disposition: attachment; filename="' . $this->name . '.vcf"');
        Header('Content-Length: ' . strlen($this->data));
        Header('Connection: close');
        echo $this->data;
    }
}


