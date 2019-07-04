<?php

/**
 * @param $m
 * @param $n
 * @return array
 */
function listSquared($m, $n)
{
    $result = array();
    foreach (range($m, $n) as $p) {
        $divisor_sum = 0;
        for ($i = 1; $i < sqrt($p); $i++) {
            if ($p % $i === 0) {
                $divisor_sum += $i * $i + intdiv($p, $i) * intdiv($p, $i);
            }
        }

        if ((int) sqrt($p) * (int) sqrt($p) === $p) {
            $divisor_sum += $p;
        }
        if ((int) sqrt($divisor_sum) * (int) sqrt($divisor_sum) === $divisor_sum) {
            $result[] = [$p, $divisor_sum];
        }
    }
    return $result;
}
