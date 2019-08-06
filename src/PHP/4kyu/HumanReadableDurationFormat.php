<?php

function format_duration($seconds)
{
    if ($seconds < 0) {
        return null;
    }
    if ($seconds === 0) {
        return 'now';
    }

    $periods = [
        'year'   => 60 * 60 * 24 * 365,
        'day'    => 60 * 60 * 24,
        'hour'   => 60 * 60,
        'minute' => 60,
        'second' => 1,
    ];
    $result = [];
    $count = 0;
    foreach ($periods as $key => $period) {
        $t = intdiv($seconds, $period);
        if ($t > 0) {
            $result[] = ($t > 1) ? "$t {$key}s" : "$t $key";
            $count++;
        }
        $seconds %= $period;
    }
    if ($count > 1) {
        $result[$count - 2] .= ' and ' . array_pop($result);
    }
    return implode(', ', $result);
}
