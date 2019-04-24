<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 24/04/19
 * Time: 10:04
 */

/**
 * @param $str
 * @return string
 */
function orderWeight($str): string
{
    if ($str === "") {
        return "";
    }

    $nums = explode(" ", $str);
    sort($nums, 5);

    $sortArr = [];
    $conArr = [];

    for ($i=0; $i < count($nums); $i++) {
        $uSortArr[$i] = numSum($nums[$i]);
    }

    $sorted = arrSort($sortArr);

    for ($i = 0; $i < count($nums); $i++) {
        $conArr[$i] = $nums[array_search($sorted[$i], $sortArr)];
        unset($sortArr[array_search($sorted[$i], $sortArr)]);
    }

    return implode(' ', $conArr);
}

/**
 * @param $array
 * @return array
 */
function arrSort($array): array
{
    sort($array);

    return $array;
}

/**
 * @param $num
 * @return float
 */
function numSum($num): float
{
    $sum = 0;

    while ($num > 0) {
        $sum += $num%10;
        $num = floor($num/10);
    }

    return $sum;
}
