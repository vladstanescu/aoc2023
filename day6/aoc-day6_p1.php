<?php
$fin = fopen("input2.txt", "r");
$_timesStr = fgets($fin);
$_distancesStr = fgets($fin);
fclose($fin);

function extractNumbers($string) {
    preg_match_all('/\d+/', $string, $matches);
    return $matches[0];
}

$times = extractNumbers($_timesStr);
$distances = extractNumbers($_distancesStr);

$races = [];
for ($i = 0; $i < count($times); $i++) {
    $races[] = ['time' => (int)$times[$i], 'distance' => (int)$distances[$i]];
}

$runs = [];
$total = 1;
foreach ($races as $race) {
    $_run = 0;
    for ($i = 1; $i < $race['time']-1; $i++) {
        if ($i * ($race['time']-$i) > $race['distance']) {
            $_run++;
        }
    }
    $runs[] = $_run;
    $total *= $_run;
}

echo 'RESULT: ' . $total . PHP_EOL;
