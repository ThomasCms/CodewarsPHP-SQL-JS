<?php

/**
 * @param $arr
 * @return array
 */
function dirReduc($arr)
{
    $opposites = [
        'NORTH' => 'SOUTH',
        'SOUTH' => 'NORTH',
        'EAST' => 'WEST',
        'WEST' => 'EAST',
    ];
    $stack = [];
    foreach ($arr as $direction) {
        if (end($stack) !== $opposites[$direction]) {
            $stack[] = $direction;
        } else {
            array_pop($stack);
        }
    }

    return $stack;
}
