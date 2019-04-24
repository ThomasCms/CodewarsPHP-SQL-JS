<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:15
 */

function getCount($str)
{
    $vowelsCount = 0;
    $vowels = ['a', 'e', 'i', 'o', 'u'];

    $array = str_split($str);

    foreach ($array as $letter) {
        if (in_array($letter, $vowels)) {
            $vowelsCount++;
        }
    }

    return $vowelsCount;
}
