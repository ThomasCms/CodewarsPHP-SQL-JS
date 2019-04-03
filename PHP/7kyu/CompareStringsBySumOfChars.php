<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:28
 */

function compare($s1, $s2) {
    $s1 = ctype_alpha($s1) ? strtoupper($s1) : "";
    $s2 = ctype_alpha($s2) ? strtoupper($s2) : "";

    $s1_sum = 0;
    for ($i = 0; $i < strlen($s1); $i++) {
        $s1_sum += ord($s1[$i]);
    }

    $s2_sum = 0;
    for ($i = 0; $i < strlen($s2); $i++) {
        $s2_sum += ord($s2[$i]);
    }

    return $s1_sum == $s2_sum;
}
