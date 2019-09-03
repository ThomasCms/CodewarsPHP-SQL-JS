<?php

/**
 * @param string $a
 * @param string $b
 * @return string
 */
function multiply(string $a, string $b): string
{
    $a = array_reverse(str_split(ltrim($a, '0')));
    $b = array_reverse(str_split(ltrim($b, '0')));
    $r = [];

    foreach ($a as $ai => $av) {
        foreach ($b as $bi => $bv) {
            $r[$ai + $bi] += $av * $bv;
            if ($r[$ai + $bi] >= 10) {
                $r[$ai + $bi + 1] += floor($r[$ai + $bi] / 10);
                $r[$ai + $bi] = $r[$ai + $bi] % 10;
            }
        }
    }

    return implode('', array_reverse($r));
}
