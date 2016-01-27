<?php

include __DIR__ . '/../../vendor/autoload.php';

$bufferResult = false;
$source       = \Rx\React\Http::get('http://download.xs4all.nl/test/100MiB.bin', [], null, $bufferResult);
$start        = time();
$size         = 0;

$source->subscribeCallback(
    function ($data) use (&$size) {
        $size += strlen($data);
        echo "\033[1A", 'Downloaded size: ', number_format($size / 1024 / 1024, 2, '.', ''), 'MB', PHP_EOL;
    },
    function (\Exception $e) {
        echo $e->getMessage();
    },
    function () use (&$size, $start) {
        $end      = time();
        $duration = $end - $start;

        echo round($size / 1024 / 1024, 2), 'MB downloaded in ', $duration, ' seconds at ', round(($size / $duration) / 1024 / 1024, 2), 'MB/s', PHP_EOL;
    }
);
