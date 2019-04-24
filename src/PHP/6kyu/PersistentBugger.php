<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 05/04/19
 * Time: 09:27
 */

/**
 * @param int $num
 * @return int
 */
function persistence(int $num): int
{
    $i=0;

    while ($num > 9) {
        $num = array_product(str_split($num));
        $i++;
    }

    return $i;
}
