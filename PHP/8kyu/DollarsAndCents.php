<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:16
 */

function format_money(float $amount): string {
    $amount = str_split($amount);
    $length = count($amount);
    $index = $length - 3;

    if ($amount[$index] !== '.') {
        $amount = implode($amount);
        $amount = str_pad($amount, strlen($amount)+1, "0");
        if (!strstr($amount, '.')) {
            $amount = substr($amount, 0, -1) . '.00';
        }

        return '$' . $amount;
    }

    $amount = implode($amount);

    return '$' . $amount;
}
