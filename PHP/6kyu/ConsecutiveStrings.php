<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 17/04/19
 * Time: 15:18
 */

function longestConsec($strarr, $k)
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
