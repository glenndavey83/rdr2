<?php

// CollectablesTracker
// Merchants Stores Admin
// Include
// View

$orderby = (isset($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : "SiteNo";
$order = (isset($_REQUEST['order'])) ? $_REQUEST['order'] : "DESC";
$hide_res = (isset($_REQUEST["hide_res"])) ? $_REQUEST["hide_res"] : 1;

$active_options = array(
	1 => "Show only Active Stores",
	0 => "Show all Stores",
);
$form_name = 'merchant-stores-filter';
$form = new form(array("FormName" => $form_name, "FormID" => $form_name));
$active_switcher = $form->selectSimple($active_options, "hide_res", 'lb-toggle-active', "hide_res", $hide_res);

?>
<form id="merchant-stores-filter" method="get" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
	<fieldset>
		<legend class="screen-reader-text"><h3>Filters</h3></legend>
		<?php if (!empty($active_switcher)) : ?><p><label><?php echo $active_switcher; ?></label><input type="submit" class="button button-primary" value="Update Filter"></p><?php endif; ?>		
	</fieldset>
	
	<input type="hidden" name="ajaxaction" id="ajaxaction" value="_ajax_fetch_merchant_stores" />
	<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
	
	<?php if (isset($merchant_stores_table)) $merchant_stores_table->display(); ?>
</form>
<script>

(function($) {
	
	$(document).ready(function() {
		$("#merchant-stores-filter").listy();
	});
	
} )( jQuery );

</script>