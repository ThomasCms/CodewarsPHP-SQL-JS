<?php

/**
 * @param array $cells
 * @param int $generations
 * @return array
 */
function get_generation(array $cells, int $generations): array
{

    $height = count($cells);
    $width = count($cells[0]);

    $expandX = array_fill(0, $generations + 1, 0);
    $expandY = array_fill(0, $width + ($generations + 1) * 2, 0);

    for ($y = 0; $y < $height; ++$y) {
        array_splice($cells[$y], 0, 0, $expandX);
        $cells[$y] = array_merge($cells[$y], $expandX);
    }

    for ($i = 0; $i < $generations + 1; ++$i) {
        array_splice($cells, 0, 0, [$expandY]);
        array_push($cells, $expandY);
    }

    for ($g = 0; $g < $generations; ++$g) {
        $newGeneration = $cells;
        for ($x = 0; $x < count($cells); ++$x) {
            for ($y = 0; $y < count($cells[$x]); ++$y) {
                $neighbours = get_neighbours($x, $y, $cells);
                $alive = $cells[$x][$y];

                if ($alive) {
                    if ($neighbours['alive'] < 2) {
                        $newGeneration[$x][$y] = 0;
                    } elseif ($neighbours['alive'] > 3) {
                        $newGeneration[$x][$y] = 0;
                    }
                } else {
                    if ($neighbours['alive'] == 3) {
                        $newGeneration[$x][$y] = 1;
                    }
                }
            }
        }

        $cells = $newGeneration;
    }

    $hasAliveCells = false;
    foreach ($cells as $row) {
        if (in_array(1, $row)) {
            $hasAliveCells = true;
            break;
        }
    }

    if ($hasAliveCells) {
        while (!in_array(1, reset($cells))) {
            array_splice($cells, 0, 1);
        }

        while (!in_array(1, end($cells))) {
            array_splice($cells, -1, 1);
        }

        for ($flip = 0; $flip < 2; ++$flip) {
            $firstCell = count($cells[0]);
            for ($y = 0; $y < count($cells); ++$y) {
                $s = array_search(1, $cells[$y]);
                if ($s !== false && $s < $firstCell) {
                    $firstCell = $s;
                }
            }
            for ($y = 0; $y < count($cells); ++$y) {
                array_splice($cells[$y], 0, $firstCell);
                $cells[$y] = array_reverse($cells[$y]);
            }
        }
    } else {
        $cells = [[]];
    }

    return $cells;
}

/**
 * @param int $x
 * @param int $y
 * @param array $grid
 * @return array
 */
function get_neighbours(int $x, int $y, array $grid): array
{
    $result = ['alive' => 0, 'dead' => 0];
    for ($i = $x - 1; $i <= $x + 1; ++$i) {
        for ($j = $y - 1; $j <= $y + 1; ++$j) {
            if ($i != $x || $j != $y) {
                if (isset($grid[$i][$j]) && $grid[$i][$j] == 1) {
                    $result['alive']++;
                } else {
                    $result['dead']++;
                }
            }
        }
    }

    return $result;
}
