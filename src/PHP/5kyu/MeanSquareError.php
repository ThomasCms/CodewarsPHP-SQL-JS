<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 17/04/19
 * Time: 15:24
 */

function solution(array $a, array $b): float
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
