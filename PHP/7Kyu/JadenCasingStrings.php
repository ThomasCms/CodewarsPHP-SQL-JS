<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:18
 */

function toJadenCase($string)
{
    $solution = mb_convert_case($string, MB_CASE_TITLE);

    return $solution;
}
