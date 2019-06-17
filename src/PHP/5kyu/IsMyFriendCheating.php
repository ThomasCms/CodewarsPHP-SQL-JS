<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 17/06/19
 * Time: 20:58
 */

/**
 * @param $n
 * @return array
 */
function removeNb($n): array
{
    $result = [];
    $sum = (1 + $n) * $n / 2;
    for ($a = 1; $a < $n; $a++) {
        $b = ($sum - $a) / ($a + 1);
        if (round($b) == $b && $b <= $n) {
            $result[] = [$a, $b];
        };
    }
    return $result;
}
