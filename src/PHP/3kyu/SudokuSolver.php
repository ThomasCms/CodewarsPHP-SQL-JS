<?php

/**
 * @param array $puzzle
 * @return array
 */
function sudoku(array $puzzle): array
{
    $grids = [1=>[0,1,2], 2=>[3,4,5], 3=>[6,7,8]];
    $num = [1,2,3,4,5,6,7,8,9];
    while (!empty($num)) {
        foreach ($num as $i) {
            $should = ['row'=>[], 'col'=>[], 'grd'=>[]];
            foreach ($puzzle as $key => $val) {
                if (in_array($i, $val)) {
                    $row = $key;
                    $col = array_flip($val)[$i];
                    $should['row'][] = $row;
                    $should['col'][] = $col;
                    $should['grd'][] = [floor($row/3)+1, floor($col/3)+1];
                }
            }
            if (count($should['row'])==9) {
                unset($num[$i-1]);
                continue;
            }
            for ($j=1; $j<=3; $j++) {
                for ($k=1; $k<=3; $k++) {
                    if (in_array([$j,$k], $should['grd'])) {
                        continue;
                    }
                    $fill = [];
                    $rows = array_diff($grids[$j], $should['row']);
                    $cols = array_diff($grids[$k], $should['col']);
                    foreach ($rows as $row) {
                        foreach ($cols as $col) {
                            $puzzle[$row][$col] !== 0 ? : $fill[] = [$row,$col];
                        }
                    }
                    count($fill) !== 1 ? : $puzzle[$fill[0][0]][$fill[0][1]] = $i;
                }
            }
        }
    }
    return $puzzle;
}
