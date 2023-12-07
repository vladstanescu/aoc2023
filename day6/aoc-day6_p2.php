<?php
$fin = fopen("input2.txt", "r");
$_timesStr = fgets($fin);
$_distancesStr = fgets($fin);
fclose($fin);

function extractNumbers($string) {
    preg_match_all('/\d+/', $string, $matches);
    return $matches[0];
}

function extractAndConcatenateNumbers($string) {
    return preg_replace('/\D/', '', $string);
}

$times = extractAndConcatenateNumbers($_timesStr);
$distances = extractAndConcatenateNumbers($_distancesStr);

$races = [
    [
    'time' => intval($times),
    'distance' => intval($distances),
    ]
];

$total = 1;
foreach ($races as $race) {
    $_run = 0;
    for ($i = 1; $i < $race['time']-1; $i++) {
        if ($i * ($race['time']-$i) > $race['distance']) {
            $_run++;
        }
    }
    $total *= $_run;
}

echo 'RESULT: ' . $total . PHP_EOL;
