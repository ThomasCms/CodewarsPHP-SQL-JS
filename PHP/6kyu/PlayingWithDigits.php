<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 04/04/19
 * Time: 10:55
 */

function digPow($n, $p)
{
    $array  = array_map('intval', str_split($n));

    $sum = 0;

    foreach ($array as $key => $value) {
        $sum += ($value ** ($p + $key));
    }

    if ($n != 0 && $sum != 0 && is_int($sum / $n)) {
        return $sum / $n;
    }

    return -1;
}
