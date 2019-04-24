<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:06
 */

/**
 * @param string $s
 * @return string
 */
function remove_char(string $s): string
{
    return substr($s, 1, strlen($s)-2);
}
