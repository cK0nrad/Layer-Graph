<?php
require '../config/config.php';
header("Access-Control-Allow-Origin: *");
header("Content-type: text/json");
$x = time() * 1000;

function mean($first, $second, $time, $zone)
{
    $y = ($second - $first);
    if ($zone == 'US') {
        $y *= 8;
    }
    return json_encode(array($time, $y));
}

function dataFetch($type, $interface)
{
    $data1 = @file_get_contents("/sys/class/net/{$interface}/statistics/{$type}_bytes");
    sleep(1);
    $data2 = @file_get_contents("/sys/class/net/{$interface}/statistics/{$type}_bytes");
    return [$data1, $data2];
}

$rtx = dataFetch(($datatype == 'TX') ? "tx" : "rx", $interface);
echo mean($rtx[0], $rtx[1], $x, $zone);

