<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:27
 */

/**
 * @param array $seq
 * @return int|mixed
 */
function findIt(array $seq)
{
    $number = 0;

    foreach ($seq as $data) {
        $number ^= $data;
    }

    return $number;
}
