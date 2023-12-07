<?php
$data = [
    'seedsets' => [],
    'seed2soil' => [],
    'soil2fertilizer' => [],
    'fertilizer2water' => [],
    'water2light' => [],
    'light2temperature' => [],
    'temperature2humidity' => [],
    'humidity2location' => [],
];
$currentSegment = null;

$fin = fopen("prod.txt", "r");
while (($line = fgets($fin)) !== false) {
    $line = str_replace(PHP_EOL, '', $line);
    if (strstr($line, 'seeds:')) {
        $tmp = explode('seeds: ', $line);
        $data['seedsets'] = explode(' ', $tmp[1]);
    } else {
        switch (trim($line)) {
            case 'seed-to-soil map:': $currentSegment = 'seed2soil'; break;
            case 'soil-to-fertilizer map:': $currentSegment = 'soil2fertilizer'; break;
            case 'fertilizer-to-water map:': $currentSegment = 'fertilizer2water'; break;
            case 'water-to-light map:': $currentSegment = 'water2light'; break;
            case 'light-to-temperature map:': $currentSegment = 'light2temperature'; break;
            case 'temperature-to-humidity map:': $currentSegment = 'temperature2humidity'; break;
            case 'humidity-to-location map:': $currentSegment = 'humidity2location'; break;
        }
        if (trim($line) !== '' && strstr($line, 'map:') === false) {
            $data[$currentSegment][] = explode(' ', trim($line));
        }
    }
}
fclose($fin);

function existsInArray($item, $array): bool {
    foreach ($array as $_item) {
        if ($_item[0] === $item[0] && $_item[1] === $item[1]) {
            return true;
        }
    }
    return false;
}

// sort mappings so they're consecutive and fill in the gaps so except for 0 -> min and max -> infinity everything is continuous
foreach (['seed2soil', 'soil2fertilizer', 'fertilizer2water', 'water2light', 'light2temperature', 'temperature2humidity', 'humidity2location'] as $_map) {
    usort($data[$_map], function($a, $b) {
        return $a[1] <=> $b[1];
    });
    // fill in gaps
    $gaps = [];
    for ($i=0;$i<count($data[$_map])-1;$i++) {
        $_from = $data[$_map][$i][1];
        $_to = $data[$_map][$i][1]+$data[$_map][$i][2];
        $_nextFrom = $data[$_map][$i+1][1];
        $_nextTo = $data[$_map][$i+1][1]+$data[$_map][$i+1][2];
        if ($_nextFrom > $_to+1) {
            $gaps[] = [
                $_to+1,
                $_to+1,
                $_nextFrom-$_to-1,
            ];
        }
    }
    foreach ($gaps as $_gap) {
        $data[$_map][] = $_gap;
    }
    usort($data[$_map], function($a, $b) {
        return $a[1] <=> $b[1];
    });
}

$min = null;

$currentSegments = [];
for ($i=0; $i<count($data['seedsets'])-1; $i+=2) {
    $currentSegments[] = [
        $data['seedsets'][$i],
        $data['seedsets'][$i]+$data['seedsets'][$i+1]-1,
    ];
}

foreach (['seed2soil', 'soil2fertilizer', 'fertilizer2water', 'water2light', 'light2temperature', 'temperature2humidity', 'humidity2location'] as $_map) {
    $newSegments = [];
    for ($i=0;$i<count($data[$_map]);$i++) {
        $_item = $data[$_map][$i];
        $_diff = $_item[0]-$_item[1];
        $_from = $_item[1];
        $_to = $_item[1]+$_item[2];
        foreach ($currentSegments as $_key => $_segment) {
            // before segment (0 -> minimum segment)
            if ($i === 0 && $_segment[0] < $_from) { // only if current segment is first
                $newSegments[] = [
                    $_segment[0],
                    min($_from-1, $_segment[1]),
                ];
            }
            // joint segment ... check whether $_from - $_to and $_segment[0] - $_segment[1] overlap
            if (
                ($_from >= $_segment[0] && $_from <= $_segment[1]) || 
                ($_to >= $_segment[0] && $_to <= $_segment[1]) || 
                ($_segment[0] >= $_from && $_segment[0] <= $_to) || 
                ($_segment[1] >= $_from && $_segment[1] <= $_to)
            ) {
                $newSegments[] = [
                    max($_segment[0],$_from)+$_diff,
                    min($_segment[1],$_to)+$_diff,
                ];
            }
            // after segment (maximum segment -> infinity)
            if ($i === count($data[$_map])-1 && $_segment[1] > $_to) { // only if current segment is last
                $newSegments[] = [
                    max($_segment[0], $_to+1),
                    $_segment[1],
                ];
            }
        }
    }
    $currentSegments = $newSegments;
}

$min = null;
foreach ($currentSegments as $_segment) {
    if ($min === null) {
        $min = $_segment[0];
    } elseif ($_segment[0] < $min) {
        $min = $_segment[0];
    }
}

echo 'RESULT: ' . $min . PHP_EOL;
