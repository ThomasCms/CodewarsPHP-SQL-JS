<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 01/06/19
 * Time: 17:08
 */

/**
 * @param $m
 * @return int
 */
function findNb($m)
{
    $n = 0;
    while ($m > 0) {
        $m -= ++$n ** 3;
    }

    return $m == 0 ? $n : -1;
}
