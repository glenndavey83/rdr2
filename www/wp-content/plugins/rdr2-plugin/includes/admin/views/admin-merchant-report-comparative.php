<?php

// RDR2
// MERCHANT REPORT - COMPARATIVE MOVEMENT
// ADMIN
// VIEW

if (isset($merchant_report_data) && !empty($merchant_report_data)) : 

	$graph_data = array_reverse($merchant_report_data);
	
	//echo "LABELS";
	$labels = array_column($graph_data, "Label");
	//print_r($labels);
	
	$keys = array_keys($graph_data[0]);
	
	if (($del_key = array_search("Label", $keys)) !== false) {
	    unset($keys[$del_key]);
	}
	
	$reports_export_link = $_SERVER["PHP_SELF"] . "?page=rdr2-merchants&action=export-spreadsheet&data=merchant-reports&report=comparative_movement&id=" . $id;

?>

<div class="admin-graph"><canvas id="ReportChartContainer"></canvas></div>

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
		grey: 'rgb(201, 203, 207)'
	};
	
	window.colorKeys = Object.keys(window.chartColors);
	
	var timeFormat = 'YYYY';
	var color = Chart.helpers.color;
	
	var config = {
		type: 'bar',
	    data: {
	        labels: [ <?php foreach ($labels as $label) echo "'" . $label . "', "; ?> ],
	        datasets: [
	        <?php foreach ($keys as $k => $key) : ?>
	        {
	            label: '<?php echo $key; ?>',
	            backgroundColor: color(window.chartColors[window.colorKeys[<?php echo $k; ?>]]).alpha(0.5).rgbString(),
	            borderColor: window.chartColors[window.colorKeys[<?php echo $k; ?>]],
	            fill: false,
	            data: [ <?php foreach ($graph_data as $i => $data) : ?>'<?php echo $data[$key]; ?>', <?php endforeach?> ],
	            <?php if ($key == "DollarsSpent") : ?>
	            type: 'line',
	            yAxisID: 'right-y-axis',
	            <?php else : ?>
	            yAxisID: 'left-y-axis',
	            <?php endif; ?>
	        }, 
	        <?php endforeach; ?>
	        ]
	    },
	    options: {
	    	title: {
				text: 'Comparative Movement - Last 4 Quarters',
				display: true,
				fontSize: 16,
				fontFamily: '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif',
				
			},
	    	responsive: true,
	    	responsiveAnimationDuration: 300,
	    	maintainAspectRatio: false,
	    	
			scales: {
				xAxes: [{
					scaleLabel: {
						display: true,
						labelString: 'Quarter'
					}
				}],
				yAxes: [
				{
					id: 'left-y-axis',
					position: 'left',
					scaleLabel: {
						display: true,
						labelString: '',
						stacked: true,
					},
				},
				{
					id: 'right-y-axis',
					position: 'right',
					scaleLabel: {
						display: true,
						labelString: '',
						stacked: true,
					},
				},
				],
			},
	    }
	};
	
	$(document).ready(function() {
		var ctx = $("#ReportChartContainer");
		var SalesChart = new Chart(ctx, config);
	});

	
})(jQuery);


</script>

<?php 

//echo showHTML::resultsTable($players_per_year, TRUE, 2); 

endif; 