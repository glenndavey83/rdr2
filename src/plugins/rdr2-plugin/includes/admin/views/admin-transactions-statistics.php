<?php 

// RDR2
// TRANSACTIONS STATISTICS
// ADMIN
// VIEW

if (isset($transactions_per_year) && !empty($transactions_per_year)) : //print_r($transactions_per_year); ?>

<div class="admin-graph"><canvas id="TransactionsChartContainer"></canvas></div>

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

	var timeFormat = 'YYYY';
	var color = Chart.helpers.color;
	
	var config = {
		type: 'line',
	    data: {
	        labels: [ <?php foreach ($transactions_per_year as $key => $data) echo $data["Year"] . ", "; ?> ],
	        datasets: [{
	            label: 'Transactions',
	            backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(),
	            borderColor: window.chartColors.green,
	            fill: false,
	            data: [<?php foreach ($transactions_per_year as $key => $data) echo $data["Transactions"] . ", "; ?>],
	        }]
	    },
	    options: {
	    	title: {
				text: 'Transactions Per Year'
			},
	    	responsive: true,
	    	responsiveAnimationDuration: 300,
	    	maintainAspectRatio: false,
	    	
			scales: {
				xAxes: [{
					scaleLabel: {
						display: true,
						labelString: 'Year'
					}
				}],
				yAxes: [{
					scaleLabel: {
						display: true,
						labelString: 'Transactions'
					}
				}]
			},
	    }
	};
	
	$(document).ready(function() {
		var ctx = $("#TransactionsChartContainer");
		var TransactionsChart = new Chart(ctx, config);
	});

	
})(jQuery);


</script>

<?php 

//echo showHTML::resultsTable($players_per_year, TRUE, 2); 

endif; 


if (isset($stats_array) && !empty($stats_array) && is_array($stats_array)) {
	$table_rows = "";
	foreach ($stats_array as $key => $value) { 
		$row = showHTML::tableHeader($key) . showHTML::tableCell(number_format($value));
		$table_rows .= showHTML::tableRow($row);
	} 
	echo showHTML::table($table_rows, FALSE, FALSE, 2); 
}
