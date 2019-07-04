<?php

/**
 * @param $braces
 * @return bool
 */
function validBraces($braces)
{
    $truth = 1;

    while ($truth) {
        $braces = str_replace(['[]', '()', '{}'], '', $braces, $truth);
    }

    return $braces === '';
}
