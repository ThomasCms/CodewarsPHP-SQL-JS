<?php

/**
 * @param array $field
 * @return bool
 */
function validate_battlefield(array $field): bool
{
    $battleships = $cruisers = $destroyers = $subs = 0;

    foreach ($field as $r => $row) {
        foreach ($row as $c => $cell) {
            if ($cell == 1) {
                // checking submarines:
                if ($field[$r-1][$c-1] == 0
                    && $field[$r-1][$c] == 0
                    && $field[$r-1][$c+1] == 0
                    && $field[$r][$c-1] == 0
                    && $field[$r][$c+1] == 0
                    && $field[$r+1][$c-1] == 0
                    && $field[$r+1][$c] == 0
                    && $field[$r+1][$c+1] == 0
                ) {
                    $subs++;
                } elseif ($field[$r][$c-1] == 0 && $field[$r][$c+1] == 1 && $field[$r][$c+2] == 0) { // destroyers
                    $destroyers++;
                } elseif ($field[$r-1][$c] == 0 && $field[$r+1][$c] == 1 && $field[$r+2][$c] == 0) {
                    $destroyers++;
                } elseif ($field[$r][$c-1] == 0 && $field[$r][$c+1] == 1 && $field[$r][$c+2] == 1
                    && $field[$r][$c+3]==0) { // cruisers
                    $cruisers++;
                } elseif ($field[$r-1][$c] == 0 && $field[$r+1][$c] == 1 && $field[$r+2][$c] == 1
                    && $field[$r+3][$c]==0) {
                    $cruisers++;
                } elseif ($field[$r][$c-1] == 0 && $field[$r][$c+1] == 1 && $field[$r][$c+2] == 1 && $field[$r][$c+3]==1
                    && $field[$r][$c+4]==0) { // battleships
                    $battleships++;
                } elseif ($field[$r-1][$c] == 0 && $field[$r+1][$c] == 1 && $field[$r+2][$c] == 1 && $field[$r+3][$c]==1
                    && $field[$r+4][$c]==0) {
                    $battleships++;
                }
            }
        }
    }

    if ($battleships !== 1 || $cruisers !== 2 || $destroyers !== 3 || $subs !== 4) {
        return false;
    }

    return true;
}
