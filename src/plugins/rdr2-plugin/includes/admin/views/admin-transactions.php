<?php

// CollectablesTracker
// Merchants Admin
// Include
// View


?>
<form id="transactions-filter" method="get" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
	<fieldset>
		<legend class="screen-reader-text"><h3>Filters</h3></legend>
		<?php if (isset($transactions_filters) && !empty($transactions_filters)) echo $transactions_filters; ?>		
		<input type="submit" class="button button-primary" value="Update Filter"></p>
	</fieldset>

	<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
	<input type="hidden" name="ajaxaction" id="ajaxaction" value="_ajax_fetch_transactions" />
	<p class="alignright"><a href="#" class="button"><span class="dashicons dashicons-media-spreadsheet"></span> Export Sales</a></p>
	
	<!-- Now we can render the completed list table -->
	<?php $transactions_list_table->display(); ?>
</form>

<script>

(function($) {

$(document).ready(function() {
	
	/*
	$(".lb-toggle-active").on("change", function() {
		$("#merchants-filter").submit();
	});
	*/
	
	$("#transactions-filter").listy();
});
 
})(jQuery);

</script>

