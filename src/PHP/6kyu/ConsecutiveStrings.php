<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 17/04/19
 * Time: 15:18
 */

/**
 * @param $strarr
 * @param $k
 * @return string
 */
function longestConsec($strarr, $k): string
{
    $longest = '';

    if ($k > 0) {
        for ($i = 0; $i < count($strarr) - $k + 1; $i++) {
            $consecutive = implode('', array_slice($strarr, $i, $k));
            $longest = strlen($consecutive) > strlen($longest) ? $consecutive : $longest;
        }
    }

    return $longest;
}
