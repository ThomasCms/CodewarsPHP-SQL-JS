<?php

/**
 * @param $roman
 * @return float|int
 */
function solution($roman)
{
    $number = 0;
    $numerals = [
        'CM' => 900,
        'M' => 1000,
        'CD' => 400,
        'D' => 500,
        'XC' => 90,
        'C' => 100,
        'XL' => 40,
        'L' => 50,
        'IX' => 9,
        'X' => 10,
        'IV' => 4,
        'V' => 5,
        'I' => 1
    ];

    foreach ($numerals as $numeral => $value) {
        $roman = str_replace($numeral, "", $roman, $count);
        $number += $count * $value;
    }

    return $number;
}
