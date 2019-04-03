<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:04
 */

function boolToWord(bool $bool): string {
    if ($bool == TRUE) {
        $bool = 'Yes';
    } else {
        $bool = 'No';
    }
    return $bool;
}
