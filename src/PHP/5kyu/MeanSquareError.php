<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 17/04/19
 * Time: 15:24
 */

/**
 * @param array $a
 * @param array $b
 * @return float|int
 */
function solution(array $a, array $b)
{
    $absoluteDiff = [];

    for ($i = 0; $i < count($a); $i++) {
        array_push($absoluteDiff, abs($a[$i] - $b[$i]));
    }
    $squared = array_map(function ($value) {
        return pow($value, 2);
    }, $absoluteDiff);
    return array_sum($squared) / count($squared);
}
