<?php
$data = [
    'seeds' => [],
    'seed2soil' => [],
    'soil2fertilizer' => [],
    'fertilizer2water' => [],
    'water2light' => [],
    'light2temperature' => [],
    'temperature2humidity' => [],
    'humidity2location' => [],
];
$currentSegment = null;

$fin = fopen("prod.txt", "r");
while (($line = fgets($fin)) !== false) {
    $line = str_replace(PHP_EOL, '', $line);
    if (strstr($line, 'seeds:')) {
        $tmp = explode('seeds: ', $line);
        $data['seeds'] = explode(' ', $tmp[1]);
    } else {
        switch (trim($line)) {
            case 'seed-to-soil map:': $currentSegment = 'seed2soil'; break;
            case 'soil-to-fertilizer map:': $currentSegment = 'soil2fertilizer'; break;
            case 'fertilizer-to-water map:': $currentSegment = 'fertilizer2water'; break;
            case 'water-to-light map:': $currentSegment = 'water2light'; break;
            case 'light-to-temperature map:': $currentSegment = 'light2temperature'; break;
            case 'temperature-to-humidity map:': $currentSegment = 'temperature2humidity'; break;
            case 'humidity-to-location map:': $currentSegment = 'humidity2location'; break;
        }
        if (trim($line) !== '' && strstr($line, 'map:') === false) {
            $data[$currentSegment][] = explode(' ', trim($line));
        }
    }
}
fclose($fin);

$results = [];
foreach ($data['seeds'] as $_seed) {
    $_result = $_seed;
    foreach (['seed2soil', 'soil2fertilizer', 'fertilizer2water', 'water2light', 'light2temperature', 'temperature2humidity', 'humidity2location'] as $_segment) {
        foreach ($data[$_segment] as $_item) {
            if ($_result >= $_item[1] && $_result <= $_item[1]+$_item[2]) {
                $_result = $_result + ($_item[0]-$_item[1]);
                break;
            }
        }
    }
    $results[] = $_result;
}

echo 'RESULT: ' . min($results) . PHP_EOL;
