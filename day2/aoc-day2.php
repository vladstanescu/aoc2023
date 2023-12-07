<?php
$fin = fopen("input2.txt", "r");

$target = [
    'red' => 12,
    'green' => 13,
    'blue' => 14,
];

function extractRound(string $round)
{
    $cubes = explode(',', $round);
    $result = [
        'red' => 0,
        'green' => 0,
        'blue' => 0,
    ];
    foreach ($cubes as $cube) {
        list($num, $color) = explode(' ', trim($cube));
        $result[trim($color)] += $num;
    }
    return $result;
}

function processLine(string $line, array $target) {
    $segments = explode(': ', $line, 2);
    list(, $game) = explode(' ', $segments[0], 2);
    $rounds = explode(';', $segments[1]);
    $possible = true;
    foreach ($rounds as $_round) {
        $_roundResult = extractRound(trim($_round));
        $possible = $possible && (
            $_roundResult['red'] <= $target['red'] &&
            $_roundResult['green'] <= $target['green'] &&
            $_roundResult['blue'] <= $target['blue']
        );
    }
    return [
        'game' => $game,
        'result' => $possible,
    ];
}

function processLine2(string $line) {
    $segments = explode(': ', $line, 2);
    list(, $game) = explode(' ', $segments[0], 2);
    $rounds = explode(';', $segments[1]);
    $minimum = [
        'red' => 0,
        'green' => 0,
        'blue' => 0,
    ];
    foreach ($rounds as $_round) {
        $_roundResult = extractRound(trim($_round));
        if ($_roundResult['red'] > $minimum['red']) {
            $minimum['red'] = $_roundResult['red'];
        }
        if ($_roundResult['green'] > $minimum['green']) {
            $minimum['green'] = $_roundResult['green'];
        }
        if ($_roundResult['blue'] > $minimum['blue']) {
            $minimum['blue'] = $_roundResult['blue'];
        }
    }
    return [
        'game' => $game,
        'result' => $minimum,
    ];
}

$total = 0;
while (($line = fgets($fin)) !== false) {
    $_lineResult = processLine2($line);
    // echo str_replace(PHP_EOL,'',$line) . ' (' 
    //     . ($_lineResult['result']['red'] . ' red , ' . $_lineResult['result']['green'] . ' green , ' . $_lineResult['result']['blue'] . ' blue)') . PHP_EOL;
    $total += $_lineResult['result']['red'] * $_lineResult['result']['green'] * $_lineResult['result']['blue'];
}
echo "RESULT: " . $total . PHP_EOL;

fclose($fin);