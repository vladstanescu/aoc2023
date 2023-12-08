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

$found = false;
$i = 0;
$result = 0;
$currentIndex = 'AAA';
while (!$found) {
    if ($instructions[$i] === 'L') {
        $currentIndex = $map[$currentIndex][0];
    } else {
        $currentIndex = $map[$currentIndex][1];
    }
    $result++;
    if ($currentIndex === 'ZZZ') {
        $found = true;
    } else {
        if (++$i === strlen($instructions)-1) {
            $i = 0;
        }
    }
}

echo 'RESULT: ' . $result . PHP_EOL;