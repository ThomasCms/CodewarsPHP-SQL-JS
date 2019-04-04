<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:15
 */

function getMiddle($text)
{
    $lenght = strlen($text);
    $evenOrOdd = null;
    $array = str_split($text);

    if (0 === $lenght % 2) {
        $evenOrOdd = 'Even';
    } else {
        $evenOrOdd = 'Odd';
    }

    if ($evenOrOdd === 'Odd') {
        $letter = $lenght / 2;

        $return = $array[$letter];
    } else {
        $letter2 = round($letter = $lenght / 2);
        $letter1 = $letter - 1;

        $return = $array[$letter1] . $array[$letter2];
    }
    return $return;
}
