<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 01/06/19
 * Time: 16:15
 */

/**
 * @param int $n
 * @return float|int
 */
function perimeter(int $n)
{
    $fibonacciNumbers = [1,1];
    for ($i = 2; $i <= $n; $i++) {
        $fibonacciNumbers[$i] = $fibonacciNumbers[$i-2] + $fibonacciNumbers[$i-1];
    }
    return 4 * array_sum($fibonacciNumbers);
}
