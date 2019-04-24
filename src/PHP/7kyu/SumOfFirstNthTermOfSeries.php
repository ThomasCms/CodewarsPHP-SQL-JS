<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:35
 */

/**
 * @param $n
 * @return string
 */
function series_sum($n): string
{
    $sum = 0;

    for ($i = 0; $i <= ($n - 1); $i++) {
        $sum += (1 / (1 + (3*$i)));
    }

    return number_format($sum, 2, '.', '');
}
