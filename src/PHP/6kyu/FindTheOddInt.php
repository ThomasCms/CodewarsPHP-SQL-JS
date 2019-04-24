<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:27
 */

function findIt(array $seq) : int
{
    $number = 0;

    foreach ($seq as $data) {
        $number ^= $data;
    }

    return $number;
}
