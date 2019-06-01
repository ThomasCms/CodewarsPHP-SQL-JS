<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 01/06/19
 * Time: 16:58
 */

/**
 * @param $v1
 * @param $v2
 * @param $g
 * @return array|null
 */
function race($v1, $v2, $g)
{
    $hour = 0;
    $min = 0;
    $sec = 0;
    if ($v2>$v1) {
        $secondToCatch = $g/($v2-$v1)*3600;
        $hour = floor($secondToCatch / 3600);
        $min = floor(($secondToCatch - $hour*3600) / 60);
        $sec = floor($secondToCatch - ($hour*3600) - ($min*60));
        $rep = [$hour, $min, $sec];
        return $rep;
    } else {
        return null;
    }
}
