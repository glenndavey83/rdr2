<?php

// RDR2
// MERCHANT REPORT - ANALYSIS BY AGE
// ADMIN
// VIEW

if (isset($merchant_report_data) && !empty($merchant_report_data)) : 

	//print_r($merchant_report_data);
	//echo "LABELS";
	$labels = array_column($merchant_report_data, "Label");
	//print_r($labels);
	
	$keys = array_keys($merchant_report_data[0]);
	
	if (($del_key = array_search("Label", $keys)) !== false) {
	    unset($keys[$del_key]);
	}
	
	$reports_export_link = $_SERVER["PHP_SELF"] . "?page=rdr2-merchants&action=export-spreadsheet&data=merchant-reports&report=analysis_by_age&id=" . $id;

?>
<div class="admin-graph"><canvas id="AgeAnalysisChartContainer"></canvas></div>

<?php if (isset($merchant_report_table) && !empty($merchant_report_table)) $merchant_report_table->display(); ?>

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
	        labels: [ <?php foreach ($merchant_report_data as $data) echo "'" . $data["AgeGroup"] . "', "; ?> ],
	        datasets: [{
	            data: [ <?php foreach ($merchant_report_data as $i => $data) : ?>'<?php echo $data["DollarsSpent"]; ?>', <?php endforeach?> ],
	            backgroundColor: [
					window.chartColors.red,
					window.chartColors.orange,
					window.chartColors.yellow,
					window.chartColors.green,
					window.chartColors.blue,
				],
				
	        }]
	    },
		
	    options: {
	    	title: {
				text: 'Analysis by Age',
				display: true,
				fontSize: 16,
				fontFamily: '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif',
				
			},
	    	responsive: true,
	    	responsiveAnimationDuration: 300,
	    	maintainAspectRatio: false,
	    }
	};
	
	$(document).ready(function() {		
		var AgeChart = new Chart($("#AgeAnalysisChartContainer"), config2);
	});

	
})(jQuery);


</script><?php endif; ?>