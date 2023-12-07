<?php
$fin = fopen("input2.txt", "r");

function processLine(string $line, string|null $prevLine, string|null $nextLine): array {
    $symbols = ['*','#','$','&','@','!','?','^','~','+','=','%','/','-'];
    $result = [];
    $currentNumber = null;
    $isincluded = false;
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
            }
        } else {
            // did we just finish a number?
            if ($currentNumber !== null) {
                if ($isincluded === true) {
                    $result[] = intval($currentNumber);
                }
                $isincluded = false;
                $currentNumber = null;
            } else {
                // no number yet, keep going
            }
        }
    }
    return $result;
}

$lines = [];
while (($line = fgets($fin)) !== false) {
    $lines[] = str_replace(PHP_EOL,'',$line);
}
fclose($fin);

$total = 0;
for ($i=0; $i<count($lines); $i++) {
    $line = $lines[$i];
    $numbers = processLine(
        $line.'.', 
        ($i>0 ? $lines[$i-1].'.' : null), 
        ($i<count($lines)-1 ? $lines[$i+1].'.' : null)
    );
    $total += array_sum($numbers);
    // echo str_replace(PHP_EOL,'',$line) . ' => ' . implode(', ', $numbers) . PHP_EOL;
}
echo "RESULT: " . $total . PHP_EOL;

