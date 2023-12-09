<?php

function processSequence(array $sequence) {
    $countValues = array_count_values($sequence);
    if (count($countValues) === 1 && $sequence[0] === 0) {
        return 0;
    } else {
        $diffs = [];
        for ($i = 1; $i < count($sequence); $i++) {
            $diffs[] = $sequence[$i] - $sequence[$i-1];
        }
        return $sequence[count($sequence) - 1] + processSequence($diffs);
    }
}

$fin = fopen("prod.txt", "r");
$total = 0;
while (($line = fgets($fin)) !== false) {
    $line = trim(str_replace(PHP_EOL, '', $line));
    $num = processSequence(explode(' ', $line));
    // echo $line . ' - ' . $num . PHP_EOL;
    $total += $num;
}

echo 'RESULT: ' . $total . PHP_EOL;