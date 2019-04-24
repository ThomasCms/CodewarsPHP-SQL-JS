<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 05/04/19
 * Time: 09:18
 */

function inArray($array1, $array2)
{
    $array1 = array_keys(array_flip($array1));
    $data = [];

    for ($i=0; $i<count($array1); $i++) {
        for ($j=0; $j<count($array2); $j++) {
            $pos = strpos($array2[$j], $array1[$i]);

            if ($pos !== false) {
                $data[] = $array1[$i];
                break;
            }
        }
    }
    sort($data);

    return $data;
}
