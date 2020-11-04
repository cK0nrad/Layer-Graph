<?php
require 'config/config.php';
$dataName = ($zone == 'EU') ? (($lang == 'FR') ? "Octets" : "Bytes") : 'Bits';
$requestLang = ($lang == 'FR') ? 'Requetes' : 'Requests';
$perSecondLang = ($lang == 'FR') ? 'par seconde' : 'per second';
?>
<title><?php echo $sitename; ?></title>

<html>
<head>
    <?php error_log(" \r\n", 3, 'data/layer7-logs'); ?>
</head>
<body>
<div id="layer7"></div>
<br/>
<div id="layer4"></div>
<br/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/8.2.2/highcharts.js"
        integrity="sha512-PpL09bLaSaj5IzGNx6hsnjiIeLm9bL7Q9BB4pkhEvQSbmI0og5Sr/s7Ns/Ax4/jDrggGLdHfa9IbsvpnmoZYFA=="
        crossorigin="anonymous"></script>
<script
        src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/8.2.2/modules/exporting.min.js"
        integrity="sha512-DuFO4JhOrZK4Zz+4K0nXseP0K/daLNCrbGjSkRzK+Zibkblwqc0BYBQ1sTN7mC4Kg6vNqr8eMZwLgTcnKXF8mg=="
        crossorigin="anonymous"
></script>

<script id="source" language="javascript" type="text/javascript">
    $(document).ready(function () {
        Highcharts.createElement(
            "link",
            {
                href: "https://fonts.googleapis.com/css?family=Unica+One",
                rel: "stylesheet",
                type: "text/css",
            },
            null,
            document.getElementsByTagName("head")[0]
        );

        let layer7 = new Highcharts.Chart({
            chart: {
                renderTo: "layer7",
                defaultSeriesType: "spline",
                events: {
                    load: requestData(0),
                },
            },
            title: {
                text: "<?php echo $Layer7Title;?>",
            },
            xAxis: {
                type: "datetime",
                tickPixelInterval: 150,
                maxZoom: 20 * 1000,
            },
            yAxis: {
                minPadding: 0.2,
                maxPadding: 0.2,
                title: {
                    text: "<?php echo $requestLang;?> <?php echo $perSecondLang;?>",
                    margin: 80,
                },
            },
            series: [
                {
                    name: "<?php echo $requestLang;?>/s",
                    data: [],
                },
            ],
        });

        let layer4 = new Highcharts.Chart({
            chart: {
                renderTo: "layer4",
                defaultSeriesType: "spline",
                events: {
                    load: requestData(1),
                },
            },
            title: {
                text: "<?php echo $Layer4Title;?>",
            },
            xAxis: {
                type: "datetime",
                tickPixelInterval: 150,
                maxZoom: 20 * 1000,
            },
            yAxis: {
                minPadding: 0.2,
                maxPadding: 0.2,
                title: {
                    text: "<?php echo $dataName;?> <?php echo $perSecondLang;?>",
                    margin: 80,
                },
            },
            series: [
                {
                    name: "<?php echo $dataName;?>/s",
                    data: [],
                },
            ],
        });

        function requestData(type) {
            $.ajax({
                url: "data/" + (!type ? "layer7" : "layer4") + ".php",
                success: function (point) {
                    var series = (!type ? layer7 : layer4).series[0],
                        shift = series.data.length > 20;
                    series.addPoint(point, true, shift);
                    setTimeout(() => requestData(type), 1000);
                },
                cache: false,
            });
        }
    });
</script>
</body>
</html>

