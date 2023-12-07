<?php
$fin = fopen("input4.txt", "r");

function processLine(string $line) {
    $first = null;
    $last = null;
    for ($i=0;$i<strlen($line);$i++) {
        if (is_numeric($line[$i])) {
            if ($first === null) {
                $first = $line[$i];
            }
            $last = $line[$i];
        } else {
            $found = null;
            if (substr($line, $i, 3) === "one") $found = 1;
            if (substr($line, $i, 3) === "two") $found = 2;
            if (substr($line, $i, 5) === "three") $found = 3;
            if (substr($line, $i, 4) === "four") $found = 4;
            if (substr($line, $i, 4) === "five") $found = 5;
            if (substr($line, $i, 3) === "six") $found = 6;
            if (substr($line, $i, 5) === "seven") $found = 7;
            if (substr($line, $i, 5) === "eight") $found = 8;
            if (substr($line, $i, 4) === "nine") $found = 9;
            if ($found !== null) {
                if ($first === null) {
                    $first = $found;
                }
                $last = $found;
            }
        }
    }
    return intval($first . $last);
}

$total = 0;
while (($line = fgets($fin)) !== false) {
    $num = processLine($line);
    $total += $num;
}
echo "RESULT: " . $total . PHP_EOL;

fclose($fin);
