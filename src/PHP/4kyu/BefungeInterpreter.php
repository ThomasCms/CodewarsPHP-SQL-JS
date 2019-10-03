<?php

/**
 * @param string $code
 * @return string
 */
function interpret(string $code): string
{
    $output = "";

    $code = explode("\n", $code);

    // program state
    $stack = [];
    $p = [0, 0];
    $dir = '>';
    $stringMode = false;

    while (true) {
        $c = $code[$p[0]][$p[1]] ?? ' ';
        $step = 1;

        if ($stringMode && $c !== '"') {
            $stack[] = ord($c);
        } elseif ($c === '"') {
            $stringMode = !$stringMode;
        } elseif (is_numeric($c)) {
            $stack[] = (int)$c;
        } elseif (in_array($c, ['+','-','*','/','%', '`', '\\'])) {
            binaryOp($stack, $c);
        } elseif (in_array($c, ['!', '$', '.', ','])) {
            unaryOp($stack, $c, $output);
        } elseif ($c === ':') {
            $stack[] = (int)end($stack);
        } elseif (in_array($c, ['^','>','v','<'])) {
            $dir = $c;
        } elseif ($c === '?') {
            $dir = ['^','>','v','<'][rand(0, 3)];
        } elseif ($c === '_') {
            $dir = (int)array_pop($stack) ? '<' : '>';
        } elseif ($c === '|') {
            $dir = (int)array_pop($stack) ? '^' : 'v';
        } elseif ($c === '#') {
            $step = 2;
        } elseif ($c === 'p') {
            $x = (int)array_pop($stack);
            $y = (int)array_pop($stack);
            $code[$x][$y] = chr((int)array_pop($stack));
        } elseif ($c === 'g') {
            $x = (int)array_pop($stack);
            $y = (int)array_pop($stack);
            $stack[] = ord($code[$x][$y] ?? ' ');
        } elseif ($c === '@') {
            break;
        }

        if ($dir === '^') {
            $p[0] -= $step;
        } elseif ($dir === '>') {
            $p[1] += $step;
        } elseif ($dir === 'v') {
            $p[0] += $step;
        } elseif ($dir === '<') {
            $p[1] -= $step;
        }
    }

    return $output;
}

/**
 * @param array $stack
 * @param string $operator
 */
function binaryOp(array &$stack, string $operator)
{
    $a = (int)array_pop($stack);
    $b = (int)array_pop($stack);
    if ($operator === '+') {
        $stack[] = $a + $b;
    } elseif ($operator === '-') {
        $stack[] = $b - $a;
    } elseif ($operator === '*') {
        $stack[] = $a * $b;
    } elseif ($operator === '/') {
        $stack[] = $a ? $b / $a : 0;
    } elseif ($operator === '%') {
        $stack[] = $a ? $b % $a : 0;
    } elseif ($operator === '`') {
        $stack[] = $b > $a ? 1 : 0;
    } elseif ($operator === '\\') {
        $stack[] = $a;
        $stack[] = $b;
    }
}

/**
 * @param array $stack
 * @param string $operator
 * @param string $output
 */
function unaryOp(array &$stack, string $operator, string &$output)
{
    $a = (int)array_pop($stack);
    if ($operator === '!') {
        $stack[] = $a ? 0 : 1;
    } elseif ($operator === '$') {
        // discard
    } elseif ($operator === '.') {
        $output .= $a;
    } elseif ($operator === ',') {
        $output .= chr($a);
    }
}
