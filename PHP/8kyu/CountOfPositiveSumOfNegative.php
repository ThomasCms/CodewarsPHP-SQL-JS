<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:17
 */

function countPositivesSumNegatives($input) {
    $solution = [0, 0];

    if (is_array($input)) {
        foreach ($input as $number) {
            if ($number > 0) {
                $solution[0] = $solution[0] + 1;
            }
            if ($number <0) {
                $solution[1] = $solution[1] + $number;
            }
        }
    }

    if ($solution === [0, 0]) {
        $solution = [];
    }

    return $solution;
}
