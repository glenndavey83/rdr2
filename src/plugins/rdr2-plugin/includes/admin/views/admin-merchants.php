<?php

// CollectablesTracker
// Merchants Admin
// Include
// View

$search_id = (isset($_REQUEST["search_id"])) ? $_REQUEST["search_id"] : FALSE;

?>
<form id="merchants-filter" method="get" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
	<fieldset>
		<legend class="screen-reader-text"><h3>Filters</h3></legend>
		<p><label>ID <input type="text" name="search_id" value="<?php echo $search_id; ?>" id="search_id" size='5'></label>
		<?php if (isset($merchant_filters) && !empty($merchant_filters)) echo $merchant_filters; ?>
		<input type="submit" class="button button-primary" value="Update Filter"> <a href="#" class="button hide"><span class="dashicons dashicons-media-spreadsheet"></span> Export Merchants</a></p>
	</fieldset>
	
	<?php /*
	<label><input type="checkbox" name="clean" value="1" <?php if ($clean == 1) : ?>checked="checked"<?php endif; ?> id="LBCleanData"> Clean up data (filter out empty account numbers, card numbers, created dates, etc.)</label>
	 */ ?>
	<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
	<input type="hidden" name="ajaxaction" id="ajaxaction" value="_ajax_fetch_merchants" />
	
	
	<!-- Now we can render the completed list table -->
	<?php $merchant_list_table->display(); ?>
</form>

<script>

(function($) {

$(document).ready(function() {
	
	/*
	$(".lb-toggle-active").on("change", function() {
		$("#merchants-filter").submit();
	});
	*/
	
	$("#merchants-filter").listy();
});
 
})(jQuery);

</script>

