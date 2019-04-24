<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:12
 */

/**
 * @param $op
 * @param $val1
 * @param $val2
 * @return float|int
 */
function basicOp($op, $val1, $val2)
{
    if ($op == '+') {
        return $val1 + $val2;
    }

    if ($op == '-') {
        return $val1 - $val2;
    }

    if ($op == '*') {
        return $val1 * $val2;
    }

    if ($op == '/') {
        return $val1 / $val2;
    }
}
