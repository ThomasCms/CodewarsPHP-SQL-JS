<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 03/04/19
 * Time: 11:35
 */

function cooking_time(int $eggs): int {
    return ceil($eggs/8)*5;
}
