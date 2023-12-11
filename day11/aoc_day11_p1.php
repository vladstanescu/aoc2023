<?php
$matrix = [];
$fin = fopen("prod.txt", "r");
while (($line = fgets($fin)) !== false) {
    $matrix[] = str_split(trim($line));
}
fclose($fin);

function printMatrix($matrix) {
    foreach ($matrix as $row) {
        foreach ($row as $val) {
            echo $val;
        }
        echo PHP_EOL;
    }
    echo PHP_EOL;
}

function expenseUniverse($matrix) {
    $newMatrix = [];
    // duplicate non # rows
    foreach ($matrix as $row => $rowVal) {
        $countval = array_count_values($rowVal);
        if (!array_key_exists('#', $countval)) {
            $newMatrix[] = $rowVal;
            $newMatrix[] = $rowVal;
        } else {
            $newMatrix[] = $rowVal;
        }
    }
    // invert matrix
    $newMatrixInverted = array_map(null, ...$newMatrix);
    // duplicate non # cols
    $newMatrix2 = [];
    foreach ($newMatrixInverted as $row => $rowVal) {
        $countval = array_count_values($rowVal);
        if (!array_key_exists('#', $countval)) {
            $newMatrix2[] = $rowVal;
            $newMatrix2[] = $rowVal;
        } else {
            $newMatrix2[] = $rowVal;
        }
    }
    return array_map(null, ...$newMatrix2);
}

function findGalaxies($matrix) {
    $result = [];
    foreach ($matrix as $row => $rowVal) {
        foreach ($rowVal as $col => $colVal) {
            if ($colVal == '#') {
                $result[] = [$row, $col];
            }
        }
    }
    return $result;
}

// printMatrix($matrix);
$matrix = expenseUniverse($matrix);
printMatrix($matrix);
$galaxies = findGalaxies($matrix);
// print_r($galaxies);

// $distances = [];
$sum = 0;
for ($i=0;$i<count($galaxies);$i++) {
    for ($j=$i+1;$j<count($galaxies);$j++) {
        $_distance = abs($galaxies[$i][0] - $galaxies[$j][0]) + abs($galaxies[$i][1] - $galaxies[$j][1]);
        // $distances[] = $_distance;
        $sum += $_distance;
    }
}

// print_r($distances);

echo "RESULT: " . $sum . PHP_EOL;