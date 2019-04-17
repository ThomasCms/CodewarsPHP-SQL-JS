<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 17/04/19
 * Time: 15:04
 */

function sqInRect($lng, $wdth) {
    if ($lng == $wdth) { return null; }

    $result = [];
    $vals = [$lng, $wdth];

    while ($vals[1] > 0) {
        sort($vals);
        $vals[1] -= $vals[0];
        $result[] = $vals[0];
    }

    return $result;
}