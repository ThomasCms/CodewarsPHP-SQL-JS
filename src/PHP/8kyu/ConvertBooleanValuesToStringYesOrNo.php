<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:04
 */

/**
 * @param bool $bool
 * @return string
 */
function boolToWord(bool $bool): string
{
    if ($bool == true) {
        $bool = 'Yes';
    } else {
        $bool = 'No';
    }
    return $bool;
}
