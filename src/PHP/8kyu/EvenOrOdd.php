<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 10:58
 */

/**
 * @param int $n
 * @return string
 */
function even_or_odd(int $n): string
{
    if (0 == $n % 2) {
        return "Even";
    } else {
        return "Odd";
    }
}
