<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:36
 */

function count_smileys($arr): int
{
    if (empty($arr)) {
        return 0;
    }
    $res = array_filter($arr, function ($smile) {
        $aStr = str_split($smile);
        $cnt = count($aStr);
        if ($cnt > 3) {
            return false;
        }
        for ($i = 0; $i < $cnt; $i++) {
            switch ($i) {
                case 0:
                    if ($aStr[$i] !== ':' && $aStr[$i] !== ';') {
                        return false;
                    }
                    break;
                case 1:
                    if ($aStr[$i] == ')' || $aStr[$i] == 'D') {
                        return true;
                    }
                    if ($aStr[$i] != '-' && $aStr[$i] != '~') {
                        return false;
                    }
                    break;
                case 2:
                    if ($aStr[$i] == ')' || $aStr[$i] == 'D') {
                        return true;
                    }
                    break;
            }
        }
    }
    );
    return count($res);
}
