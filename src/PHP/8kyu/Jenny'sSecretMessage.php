<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:00
 */

function greet($name)
{
    if ($name != 'Johnny') {
        return "Hello, $name!";
    } elseif ($name === 'Johnny') {
        return 'Hello, my love!';
    }
}
