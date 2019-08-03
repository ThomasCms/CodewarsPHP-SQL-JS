<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 04/04/19
 * Time: 10:31
 */

/**
 * @param $distance
 * @return int|null
 */
function fold_to($distance)
{
    $num = 0.0001;
    $result = 0;

    while ($num < $distance) {
        $result++;
        $num = $num * 2;
    }

    return $distance <= 0 ? null : $result;
}
