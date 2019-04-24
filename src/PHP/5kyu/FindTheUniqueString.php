<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 12:29
 */

function find_uniq($a)
{
    $output = [];
    foreach ($a as $s) {
        $duplicatesRemoved = array_unique(str_split(strtolower($s)));
        sort($duplicatesRemoved);
        $key = implode('', $duplicatesRemoved);
        $output[$key][] = $s;
    }
    foreach ($output as $o) {
        if (count($o) == 1) {
            return $o[0];
        }
    }
}
