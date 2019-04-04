<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:26
 */

function duplicate_encode($word)
{
    $array = str_split($word);
    $string = '';

    foreach ($array as $letter) {
        $str = implode($array);

        $regex = '/'.preg_quote($letter, '/').'/';

        $str = strtolower(preg_replace($regex, '', $str, 1));

        $array2 = str_split($str);

        $letter = strtolower($letter);

        if (in_array($letter, $array2)) {
            $string .= ')';
        } else {
            $string .= '(';
        }
    }

    return $string;
}
