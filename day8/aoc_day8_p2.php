<?php
$fin = fopen("prod.txt", "r");
$instructions = fgets($fin);
fgets($fin);
$map = [];
while ($line = fgets($fin)) {
    list($from, $coordStr) = explode(" = ", str_replace(PHP_EOL, '', $line));
    $coords = explode(", ", substr($coordStr,1,-1));
    $map[$from] = $coords;
}
fclose($fin);

/**
 * @param string $start
 * @param array $instuctions
 * @param array $map
 * @param array $destinations
 */
function findPathsToZ($start, $instructions, $map, $destinations) {
    $i = 0;
    $iterator = 0;
    $result = [];
    $currentIndex = $start;
    while (count($destinations) > 0 && $iterator++ < 100000) {
        if ($instructions[$i] === 'L') {
            $currentIndex = $map[$currentIndex][0];
        } else {
            $currentIndex = $map[$currentIndex][1];
        }
        if ($currentIndex[2] === 'Z') {
            if (in_array($currentIndex, $destinations)) {
                $result[] = $iterator;
                $destinations = array_diff($destinations, [$currentIndex]);
            }
        }
        if (++$i === strlen($instructions)-1) {
            $i = 0;
        }
    }
    return $result;

}

// destination point sending with Z
$destinationPoints = [];
foreach ($map as $key => $value) {
    if ($key[2] === 'Z') {
        $destinationPoints[] = $key;
    }
}

// starting points ending with A
$startingPoints = [];
$paths = [];
foreach ($map as $key => $value) {
    if ($key[2] === 'A') {
        $paths[$key] = findPathsToZ($key, $instructions, $map, $destinationPoints);
        $startingPoints[] = $key;
    }
}

// find smallest common multiple for all numbers in $paths
function gcd($a, $b) {
    return $b === 0 ? $a : gcd($b, $a % $b);
}
function lcm($a, $b) {
    return ($a == 0 || $b == 0) ? 0 : abs($a * $b) / gcd($a, $b);
}
$result = null;
foreach ($paths as $key => $value) {
    if ($result === null) {
        $result = $value[0];
    } else {
        $result = lcm($result, $value[0]); // noticed that there are never 2 paths to a Z item in the results; if there were, we would need to find all combinations
    }
}

echo 'RESULT: ' . $result . PHP_EOL;