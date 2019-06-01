<?php
/**
 * Created by PhpStorm.
 * User: thocou
 * Date: 01/06/19
 * Time: 15:57
 */

/**
 * @param Node $node
 * @return int
 */
function loop_size(Node $node): int
{
    $i = 0;
    while (!isset($node->myf)) {
        $node->myf = $i;
        $node = $node->getNext();
        $i++;
    }
    return $i - $node->myf;
}
