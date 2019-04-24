<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:22
 */

/**
 * @param $litres
 * @param $price_per_liter
 * @return float|int
 */
function fuel_price($litres, $price_per_liter)
{
    $discount = floor($litres/2) * 0.05;

    if ($discount > 0.25) {
        $discount = 0.25;
    }

    return $litres * ($price_per_liter - $discount);
}
