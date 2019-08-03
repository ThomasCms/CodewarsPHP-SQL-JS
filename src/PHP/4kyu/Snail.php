<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 01/06/19
 * Time: 15:57
 */

/**
 * @param array $array
 * @return array
 */
function snail(array $array): array
{
    $result = [];
    while (!empty($array)) {
        $result = array_merge($result, array_shift($array));
        $array = rotate($array);
    }
    return $result;
}

/**
 * @param array $array
 * @return array
 */
function rotate(array $array): array
{
    if (!isset($array[0])) {
        return [];
    }
    $rotated = [];
    for ($i=0; $i < count($array[0]); $i++) {
        $rotated[] = array_column($array, $i);
    }
    return array_reverse($rotated);
}
