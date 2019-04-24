<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 17/04/19
 * Time: 15:13
 */

function comp($a1, $a2)
{
    if (!is_null($a1) && !is_null($a2)) {
        sort($a1);
        sort($a2);

        foreach ($a1 as $n => $norm) {
            $a1[$n] = ($norm*$norm);
        }

        return $a1 === $a2;
    }

    return false;
}
