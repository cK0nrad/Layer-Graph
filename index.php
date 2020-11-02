<?php
session_start();
require 'config/config.php';
if ($num == 'EU'){
	if($lang == 'FR'){
			$datareturn = 'Octets';
	}else{
			$datareturn = 'Bytes';
	}
}else{
	$datareturn = 'Bit';
	}if($lang == 'FR'){
		$requestreturn = 'Requetes';
		$phrase = 'par seconde';
	}else{
		$requestreturn = 'Requests';
		$phrase = 'per second';
}
?>
<title><?php echo $sitename;?></title>
<center>
<html>
    <head>
        <?php error_log(" \r\n",3,'data/layer7-logs'); ?>
	 </head>
<body>
	<div id="container"></div><br>
	<div id="container2"></div><br>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/8.2.2/highcharts.js" integrity="sha512-PpL09bLaSaj5IzGNx6hsnjiIeLm9bL7Q9BB4pkhEvQSbmI0og5Sr/s7Ns/Ax4/jDrggGLdHfa9IbsvpnmoZYFA==" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/8.2.2/modules/exporting.min.js" integrity="sha512-DuFO4JhOrZK4Zz+4K0nXseP0K/daLNCrbGjSkRzK+Zibkblwqc0BYBQ1sTN7mC4Kg6vNqr8eMZwLgTcnKXF8mg==" crossorigin="anonymous"></script>

	<script id="source" language="javascript" type="text/javascript">
	$(document).ready(function() {
		Highcharts.createElement('link', {
			href: 'https://fonts.googleapis.com/css?family=Unica+One',
			rel: 'stylesheet',
			type: 'text/css'
		}, null, document.getElementsByTagName('head')[0]);

		chart = new Highcharts.Chart({
			chart: {
			renderTo: 'container',
			defaultSeriesType: 'spline',
			events: {
					load: requestData
			}
		},
		title: {
			text: 'Layer 7  ==><?php echo $ip;?> PORT 80<=='
		},
		xAxis: {
			type: 'datetime',
			tickPixelInterval: 150,
			maxZoom: 20 * 1000
		},
		yAxis: {
			minPadding: 0.2,
			maxPadding: 0.2,
			title: {
					text: '<?php echo $requestreturn;?> <?php echo $phrase;?>',
					margin: 80
			}
		},
		series: [{
			name: '<?php echo $requestreturn;?>/s',
			data: []
		}]
	});
	chart2 = new Highcharts.Chart({
		chart: {
			renderTo: 'container2',
			defaultSeriesType: 'spline',
			events: {
					load: requestData2
			}},
		title: {
				text: 'Layer 4 ==><?php echo $ip;?> PORT 80 <=='
		},
		xAxis: {
				type: 'datetime',
				tickPixelInterval: 150,
				maxZoom: 20 * 1000
		},
		yAxis: {
				minPadding: 0.2,
				maxPadding: 0.2,
				title: {
						text: '<?php echo $datareturn;?> <?php echo $phrase;?>',
						margin: 80
			}
		},
		series: [{
				name: '<?php echo $datareturn;?>/s',
				data: []
			}]
		});
	});

	function requestData() {
	$.ajax({
	url: 'data/layer7.php',
	success: function(point) {
			var series = chart.series[0],
					shift = series.data.length > 20;
			chart.series[0].addPoint(point, true, shift);
			setTimeout(requestData, 1000);
	},
	cache: false
	});
	}
	function requestData2() {
	$.ajax({
	url: 'data/layer4.php',
	success: function(point) {
			var series = chart2.series[0],
					shift = series.data.length > 20;
			chart2.series[0].addPoint(point, true, shift);
			setTimeout(requestData2, 1000);
	},
	cache: false
	});
	}
	</script>
</body>
</html>
