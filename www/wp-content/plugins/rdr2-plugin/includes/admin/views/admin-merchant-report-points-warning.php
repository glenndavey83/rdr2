<?php

// RDR2
// MERCHANT REPORT - POINTS WARNING
// ADMIN
// VIEW

global $rdr2;

if (isset($merchant_report_data) && !empty($merchant_report_data) && is_array($merchant_report_data)) : 

	//print_r($merchant_report_data);
	//echo "LABELS";
	$labels = array_column($merchant_report_data, "Label");
	//print_r($labels);
	
	$keys = array_keys($merchant_report_data[0]);
	
	if (($del_key = array_search("Label", $keys)) !== false) {
	    unset($keys[$del_key]);
	}
	
	$reports_export_link = $_SERVER["PHP_SELF"] . "?page=rdr2-merchants&action=export-spreadsheet&data=merchant-reports&report=points_warning&id=" . $id;
	?>
	
<?php if (isset($merchant_report_table) && !empty($merchant_report_table)) : ?>

<form name="merchant-report" id="merchant-report" method="get">
	<input type="hidden" name="page" value="<?php echo $rdr2->page; ?>" />
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<input type="hidden" name="ajaxaction" id="ajaxaction" value="_ajax_fetch_merchant_report_points_warning" />
	<p class="alignright"><a href="<?php echo $reports_export_link; ?>" class="button"><span class="dashicons dashicons-media-spreadsheet"></span> Export Report</a></p>
	<?php $merchant_report_table->display(); ?>
</form>

<?php endif; ?>

<script>

(function($) {

	$(document).ready(function() {		
		$("#merchant-report").listy();
	});

} )( jQuery );

</script>

<?php

endif; 
