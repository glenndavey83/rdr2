<?php

// RDR2
// MERCHANT REPORT - NEW PLAYER ANALYSIS
// ADMIN
// VIEW

global $rdr2;

if (isset($merchant_report_data) && !empty($merchant_report_data) && is_array($merchant_report_data)) : 
	
	//print_r($merchant_report_data);
	
	//echo "LABELS";
	$labels = array_column($merchant_report_data, "Points");
	//print_r($labels);
	
	$keys = array_keys($merchant_report_data[1]);
	
	if (($del_key = array_search("Points", $keys)) !== false) {
	    unset($keys[$del_key]);
	}
	
	array_pop($merchant_report_data);

	$reports_export_link = $_SERVER["PHP_SELF"] . "?page=rdr2-merchants&action=export-spreadsheet&data=merchant-reports&report=new_player_analysis&id=" . $id;
?>



<?php if (isset($merchant_report_table) && !empty($merchant_report_table)) : ?>

<form name="merchant-report" id="merchant-report-new-player-analysis" method="get">
	<input type="hidden" name="page" value="<?php echo $rdr2->page; ?>" />
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<input type="hidden" name="ajaxaction" id="ajaxaction" value="_ajax_fetch_merchant_report_new_player_analysis" />
	<p class="alignright"><a href="<?php echo $reports_export_link; ?>" class="button"><span class="dashicons dashicons-media-spreadsheet"></span> Export Report</a></p>
	<?php $merchant_report_table->display(); ?>
</form>

<?php endif; ?>

<?php /*
<div class="admin-graph"><canvas id="Top40PlayersChartContainer"></canvas></div>
<script>

(function($) {

	
	window.chartColors = {
		red: 'rgb(255, 99, 132)',
		orange: 'rgb(255, 159, 64)',
		yellow: 'rgb(255, 205, 86)',
		green: 'rgb(75, 192, 192)',
		blue: 'rgb(54, 162, 235)',
		purple: 'rgb(153, 102, 255)',
		grey: 'rgb(201, 203, 207)',

		
		key: function(n) {
	        return this[Object.keys(this)[n]];
	    }
	};
	
	window.colorKeys = Object.keys(window.chartColors);
	
	var timeFormat = 'YYYY';
	var color = Chart.helpers.color;
	

	// Product Spend Pie Chart
	
	
	var config2 = {
		type: 'pie',
	    data: {
	        labels: [ <?php foreach ($merchant_report_data as $data) echo "'" . $data["Points"] . "', "; ?> ],
	        datasets: [{
	            data: [ <?php foreach ($merchant_report_data as $i => $data) : ?>'<?php echo $data["TotalPlayers"]; ?>', <?php endforeach?> ],
	            backgroundColor: [
					window.chartColors.red,
					window.chartColors.orange,
					window.chartColors.yellow,
					window.chartColors.green,
					window.chartColors.blue,
					window.chartColors.purple,
					window.chartColors.grey,
				],
				
	        }]
	    },
		
	    options: {
	    	title: {
				text: 'Top 40 Players'
			},
	    	responsive: true,
	    	responsiveAnimationDuration: 300,
	    	maintainAspectRatio: false,
	    }
	};
	
	$(document).ready(function() {		
		var ProductChart = new Chart($("#Top40PlayersChartContainer"), config2);
	});

	
})(jQuery);


</script>

<?php 

//echo showHTML::resultsTable($players_per_year, TRUE, 2); 

*/

?>

<script>

(function($) {
	
	$(document).ready(function() {
		$("#merchant-report-new-player-analysis").listy();
	});

} )( jQuery );

</script>

<?php

endif;