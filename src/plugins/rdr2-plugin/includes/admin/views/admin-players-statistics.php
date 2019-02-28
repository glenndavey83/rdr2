<?php 

// RDR2
// PLAYER STATISTICS
// ADMIN
// VIEW

if (!empty($players_per_year)) : //print_r($players_per_year); ?>

<div class="admin-graph"><canvas id="PlayersChartContainer"></canvas></div>

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
	        labels: [ <?php foreach ($players_per_year as $key => $data) echo $data["Year"] . ", "; ?> ],
	        datasets: [{
	            label: 'New Players',
	            backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
	            borderColor: window.chartColors.blue,
	            fill: false,
	            data: [<?php foreach ($players_per_year as $key => $data) echo $data["Players"] . ", "; ?>],
	        }]
	    },
	    options: {
	    	title: {
				text: 'Players Per Year'
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
						labelString: 'Players'
					}
				}]
			},
	    }
	};
	
	$(document).ready(function() {
		var ctx = $("#PlayersChartContainer");
		var PlayerChart = new Chart(ctx, config);
	});

	
})(jQuery);


</script>

<?php //echo showHTML::resultsTable($players_per_year, TRUE, 2); ?>
<?php endif; 

$table_rows = "";
foreach ($stats_array as $key => $value) : 
		$row = showHTML::tableHeader($key) . showHTML::tableCell(number_format($value));
		$table_rows .= showHTML::tableRow($row);
endforeach; 
echo showHTML::table($table_rows, FALSE, FALSE, 2); ?>

