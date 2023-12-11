<?php
$matrix = [];
$fin = fopen("prod.txt", "r");
while (($line = fgets($fin)) !== false) {
    $matrix[] = str_split(trim($line));
}
fclose($fin);

function findStartingPosition($matrix) {
    $x = 0;
    $y = 0;
    foreach ($matrix as $row) {
        foreach ($row as $val) {
            if ($val == 'S') {
                return [$y, $x];
            }
            $x++;
        }
        $x = 0;
        $y++;
    }
}

function walk(&$matrix, $currentPosition, $lastHistory, $counter,  &$globalCount) {
    list($x, $y) = $currentPosition;
    // echo ($x+1) . " x " . ($y+1) . '=' . $matrix[$x][$y] . PHP_EOL;
    if ($matrix[$x][$y] === 'S' && $lastHistory === null) { // start
        // determine valid directions
        if ($x > 0 
            && $matrix[$x-1][$y] !== '.'
            && in_array($matrix[$x+1][$y], ['|','L','J','S'])
            ) walk($matrix, [$x-1, $y], $currentPosition, $counter+1, $globalCount); // up
        if ($x < count($matrix)-1 
            && $matrix[$x+1][$y] !== '.'
            && in_array($matrix[$x][$y-1], ['-','L','F','S'])
            ) walk($matrix, [$x+1, $y], $currentPosition, $counter+1, $globalCount); // down
        if ($y > 0 
            && in_array($matrix[$x][$y-1], ['-','L','F','S'])
            && $matrix[$x][$y-1] !== '.'
            ) walk($matrix, [$x, $y-1], $currentPosition, $counter+1, $globalCount); // left
        if ($y < count($matrix[$x])-1 
            && $matrix[$x][$y+1] !== '.'
            && in_array($matrix[$x][$y+1], ['-','J','7','S'])
            ) walk($matrix, [$x, $y+1], $currentPosition, $counter+1, $globalCount); // right
    } elseif ($matrix[$x][$y] === 'S') { // loop completed
        $globalCount[] = $counter;
        return true;
    } else {
        // get valid next directions (not back)
        $noValidDirections = true;
        if ($x < count($matrix)-1 
            && in_array($matrix[$x][$y], ['|','7','F']) && in_array($matrix[$x+1][$y], ['|','L','J','S'])
            && ($lastHistory === null || $lastHistory[0] !== $x+1 || $lastHistory[1] !== $y)
        ) { // down
            $noValidDirections = false;
            $res = walk($matrix, [$x+1, $y], $currentPosition, $counter+1, $globalCount);
            if ($res === false) {
                return false;
            }
        }
        if ($x > 0 
            && in_array($matrix[$x][$y], ['|','L','J']) && in_array($matrix[$x-1][$y], ['|','7','F','S'])
            && ($lastHistory === null || $lastHistory[0] !== $x-1 || $lastHistory[1] !== $y)
        ) { // up
            $noValidDirections = false;
            $res = walk($matrix, [$x-1, $y], $currentPosition, $counter+1, $globalCount);
            if ($res === false) {
                return false;
            }
        }
        if ($y > 0 
            && in_array($matrix[$x][$y], ['-','7','J']) && in_array($matrix[$x][$y-1], ['-','L','F','S'])
            && ($lastHistory === null || $lastHistory[0] !== $x || $lastHistory[1] !== $y-1)
        ) { // left
            $noValidDirections = false;
            $res = walk($matrix, [$x, $y-1], $currentPosition, $counter+1, $globalCount);
            if ($res === false) {
                return false;
            }
        }
        if ($y < count($matrix[$x])-1 
            && in_array($matrix[$x][$y], ['-','L','F']) && in_array($matrix[$x][$y+1], ['-','J','7','S'])
            && ($lastHistory === null || $lastHistory[0] !== $x || $lastHistory[1] !== $y+1)
        ) { // right
            $noValidDirections = false;
            $res = walk($matrix, [$x, $y+1], $currentPosition, $counter+1, $globalCount);
            if ($res === false) {
                return false;
            }
        }
        if ($noValidDirections) {
            return false;
        }
    }
}

// foreach ($matrix as $row) {
//     foreach ($row as $val) {
//         echo $val;
//     }
//     echo PHP_EOL;
// }

$startingPosition = findStartingPosition($matrix);
echo "Starting position: " . ($startingPosition[0]+1) . " x " . ($startingPosition[1]+1) . PHP_EOL;

$walks = [];
list($x, $y) = $startingPosition;
walk($matrix, [$x, $y], null, 0, $walks);

if (empty($walks)) {
    echo "No valid walks found" . PHP_EOL;
    $result = 0;
} else {
    print_r($walks);
    $result = max($walks)/2;
}

echo 'RESULT: ' . floor($result) . PHP_EOL;
