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
            $expandedRowVal = array_fill(0, count($rowVal), ':');
            $newMatrix[] = $expandedRowVal; // we use : for milions instead of duplicating row
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
            $expandedRowVal = array_fill(0, count($rowVal), ':');
            $newMatrix2[] = $expandedRowVal; // we use : for milions instead of duplicating row
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

$matrix = expenseUniverse($matrix);
// printMatrix($matrix);
$galaxies = findGalaxies($matrix);

$sum = 0;
for ($i=0;$i<count($galaxies);$i++) {
    for ($j=$i+1;$j<count($galaxies);$j++) {
        $_distance = 0;
        for ($k=min($galaxies[$i][0], $galaxies[$j][0]);$k<max($galaxies[$i][0], $galaxies[$j][0]);$k++) {
            $_distance += ($matrix[$k][$galaxies[$i][1]] === ':') ? 1000000 : 1;
        }
        for ($l=min($galaxies[$i][1], $galaxies[$j][1]);$l<max($galaxies[$i][1], $galaxies[$j][1]);$l++) {
            $_distance += ($matrix[$galaxies[$i][0]][$l] === ':') ? 1000000 : 1;
        }
        $sum += $_distance;
    }
}

echo "RESULT: " . $sum . PHP_EOL;