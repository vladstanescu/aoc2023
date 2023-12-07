<?php
function processLine(string $line, string|null $prevLine, string|null $nextLine, int $j): array {
    $symbols = ['*'];
    $result = [];
    $currentNumber = null;
    $isincluded = false;
    $stars = [];
    for ($i=0; $i<strlen($line); $i++) {
        if (is_numeric($line[$i])) {
            if ($currentNumber === null) {
                $currentNumber = $line[$i];
            } else {
                $currentNumber .= $line[$i];
            }
            if (
                ($prevLine !== null && $i>0 && in_array($prevLine[$i-1], $symbols) // top left
                || ($prevLine !== null && in_array($prevLine[$i], $symbols)) // top
                || ($prevLine !== null && $i<strlen($prevLine)-1 && in_array($prevLine[$i+1], $symbols)) // top right
                || ($i>0 && in_array($line[$i-1], $symbols)) // left
                || ($i<strlen($line)-1 && in_array($line[$i+1], $symbols)) // right
                || ($nextLine !== null && $i>0 && in_array($nextLine[$i-1], $symbols)) // bottom left
                || ($nextLine !== null && in_array($nextLine[$i], $symbols)) // bottom
                || ($nextLine !== null && $i<strlen($nextLine)-1 && in_array($nextLine[$i+1], $symbols))) // bottom right
            ) {
                $isincluded = true;
                if ($prevLine !== null && $i>0 && $prevLine[$i-1] === '*') $stars[] = ($j-1).'x'.($i-1);
                if ($prevLine !== null && $prevLine[$i] === '*') $stars[] = ($j-1).'x'.($i);
                if ($prevLine !== null && $i<strlen($prevLine)-1 && $prevLine[$i+1] === '*') $stars[] = ($j-1).'x'.($i+1);
                if ($i>0 && $line[$i-1] === '*') $stars[] = ($j).'x'.($i-1);
                if ($i<strlen($line)-1 && $line[$i+1] === '*') $stars[] = ($j).'x'.($i+1);
                if ($nextLine !== null && $i>0 && $nextLine[$i-1] === '*') $stars[] = ($j+1).'x'.($i-1);
                if ($nextLine !== null && $nextLine[$i] === '*') $stars[] = ($j+1).'x'.($i);
                if ($nextLine !== null && $i<strlen($nextLine)-1 && $nextLine[$i+1] === '*') $stars[] = ($j+1).'x'.($i+1);
            }
        } else {
            // did we just finish a number?
            if ($currentNumber !== null) {
                if ($isincluded === true) {
                    // eliminate duplicate stars
                    $result[] = [
                        'stars' => array_unique($stars, SORT_REGULAR),
                        'num' => intval($currentNumber),
                    ];
                }
                $isincluded = false;
                $currentNumber = null;
                $stars = [];
            } else {
                // no number yet, keep going
            }
        }
    }
    return $result;
}

$lines = [];
$fin = fopen("input2.txt", "r");
while (($line = fgets($fin)) !== false) {
    $lines[] = str_replace(PHP_EOL,'',$line);
}
fclose($fin);

$total = 0;
$numbersByStars = [];
for ($i=0; $i<count($lines); $i++) {
    $line = $lines[$i];
    $numbers = processLine(
        $line.'.', 
        ($i>0 ? $lines[$i-1].'.' : null), 
        ($i<count($lines)-1 ? $lines[$i+1].'.' : null),
        $i
    );
    $total += array_sum($numbers);
    foreach ($numbers as $number) {
        foreach ($number['stars'] as $star) {
            $numbersByStars[$star][] = $number['num'];
        }
    }
}

// process the numbers with 2 stars
$total = 0;
foreach ($numbersByStars as $_numbers) {
    if (count($_numbers) === 2) {
        $total += array_product($_numbers);
    }
}
echo "RESULT: " . $total . PHP_EOL;
