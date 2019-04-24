<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:24
 */

/**
 * @param $arr
 * @return bool
 */
function in_asc_order($arr): bool
{
    $prev = $arr[0];

    foreach ($arr as $key => $value) {
        if ($prev > $value) {
            return false;
        }
        $prev = $value;
    }

    return true;
}
