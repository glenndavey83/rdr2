<?php

// RDR2
// ADMIN
// PLAYERS
// VIEW

global $order, $orderby;

$export_link = $_SERVER["PHP_SELF"] . "?page=" . $_REQUEST["page"] . "&action=export-spreadsheet&orderby=" . $orderby . "&order=" . $order;
$first_name = FALSE;
$last_name = FALSE;

?>
<form id="players-filter" method="get">
	
	<?php if (isset($player_filters) && !empty($player_filters)) : ?>
	<fieldset>
		<legend class="screen-reader-text"><h3>Filters</h3></legend>
		<p><label>First Name <input type="text" name="first_name" value="<?php echo $first_name; ?>" id="first_name" size='12'></label>
			<label>Last Name <input type="text" name="last_name" value="<?php echo $last_name; ?>" id="last_name" size='12'></label>
			<?php echo $player_filters; ?><input type="submit" class="button button-primary" value="Update Filter">&nbsp;&nbsp;<a href="<?php if (isset($export_link) && !empty($export_link)) echo $export_link; ?>" class="button hide"><span class="dashicons dashicons-media-spreadsheet"></span> Export Players</a></p>
	</fieldset>
	<?php endif; ?>
	
	<input type="hidden" name="action" id="ajaxaction" value="_ajax_fetch_players" />
	<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
	<input type="hidden" name="order" value="<?php echo $order; ?>" />
	<input type="hidden" name="orderby" value="<?php echo $orderby; ?>" />

	
	<?php if (isset($player_list_table) && !empty($player_list_table)) $player_list_table->display(); ?>
</form>

<script>

(function($) {

	$(document).ready(function() {
		
		$("#LBCleanData").on("change", function() {
			$("#players-filter").submit();
		});
		
		$("#players-filter").listy();
		
	});
	 
})(jQuery);

</script>