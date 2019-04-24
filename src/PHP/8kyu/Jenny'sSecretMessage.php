<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:00
 */

/**
 * @param $name
 * @return string
 */
function greet($name): string
{
    if ($name != 'Johnny') {
        return "Hello, $name!";
    } elseif ($name === 'Johnny') {
        return 'Hello, my love!';
    }
}
