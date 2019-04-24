<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 14:10
 */

/**
 * @param string $s
 * @return int
 */
function solution(string $s): int
{
    $max = 0;
    for ($i = 0; $i <= strlen($s) - 5; ++$i) {
        $number = substr($s, $i, 5);
        if ($number > $max) {
            $max = $number;
        }
    }

    return $max;
}
