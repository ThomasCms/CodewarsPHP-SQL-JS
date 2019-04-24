<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:28
 */

/**
 * @param array $a
 * @return array
 */
function invert(array $a): array
{
    return array_map(
        function ($n) {
            return -$n;
        },
        $a
    );
}
