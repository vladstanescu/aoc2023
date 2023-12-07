<?php

/**
 * @param $hand
 * @return int
 *  - 1 highcard
 *  - 2 one pair
 *  - 3 two pairs
 *  - 4 three of a kind
 *  - 5 fullhouse
 *  - 6 four of a kind
 *  - 7 five of a kind
 */
function handStrength($hand) {
    $counts = array_count_values($hand);
    $jCount = array_key_exists('J', $counts) ? $counts['J'] : 0;
    // 5 of a kind
    if (count(array_unique($hand)) === 1) {
        return 7;
    }
    // 4 of a kind (or 5 of a kind with a joker)
    if (in_array(4, $counts)) {
        return $jCount > 0 ? 7 : 6;
    }
    // full house (or 5 of a kind with 2 jokers or 4 of a kind with a joker)
    if (in_array(3, $counts) && in_array(2, $counts)) {
        switch ($jCount) {
            case 3:
            case 2:
            case 1:
                return 7; // 5 of a kind
            default:
                return 5; // full house
        }
    }
    // 3 of a kind
    if (in_array(3, $counts)) {
        switch ($jCount) {
            case 3:
                return 6; // 4 of a kind (as the 3x is J + any card)
            case 2:
                return 7; // 5 of a kind
            case 1:
                return 6; // 4 of a kind
            default:
                return 4; // 3 of a kind
        }
    }
    // 2 pairs
    rsort($counts);
    if ($counts[0] === 2 && $counts[1] === 2) {
        switch ($jCount) {
            case 2: // 2x J
                return 6; // 4 of a kind
            case 1:
                return 5; // full house
            default:
                return 3; // 2 pairs
        }
    }
    // 1 pair
    if ($counts[0] === 2 && $counts[1] === 1) {
        switch ($jCount) {
            case 2: // 2x J
            case 1:
                return 4; // 3 of a kind
                return 4; // 3 of a kind
            default:
                return 2; // 1 pair
        }
    }
    return $jCount === 1 ? 2 : 1;
}

function compareLetterStrength($hand1, $hand2) {
    $strength = [
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        'T' => 10,
        'J' => 1,
        'Q' => 12,
        'K' => 13,
        'A' => 14,
    ];
    $hand1 = str_split($hand1);
    $hand2 = str_split($hand2);
    $hand1 = array_map(function($letter) use ($strength) {
        return $strength[$letter];
    }, $hand1);
    $hand2 = array_map(function($letter) use ($strength) {
        return $strength[$letter];
    }, $hand2);
    for ($i = 0; $i < count($hand1); $i++) {
        if ($hand1[$i] === $hand2[$i]) {
            continue;
        }
        return $hand1[$i] < $hand2[$i] ? 1 : -1;
    }
    return 0;
}


$fin = fopen("prod.txt", "r");
$hands = [];
while ($line = fgets($fin)) {
    $line = str_replace(PHP_EOL, '', $line);
    list($hand, $bid) = explode(' ', $line);
    $hands[] = [$hand, $bid, handStrength(str_split($hand))];
}
fclose($fin);

usort($hands, function($a, $b) {
    if ($a[2] === $b[2]) {
        // compare letters in order
        return compareLetterStrength($a[0], $b[0]);
    }
    return $a[2] < $b[2] ? 1 : -1;
});

$hands = array_reverse($hands);

$score = 0;
for ($i=0;$i<count($hands);$i++) {
    $mult = $i+1;
    $score += $hands[$i][1] * $mult;
}

echo "SCORE: " . $score . PHP_EOL;