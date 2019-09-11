<?php

function calc(string $expression): float
{
    $expression = str_replace([" ", "--"], ["", "+"], $expression);
    if (preg_match_all('/\((?:[^\(]*?\))/', $expression, $matches)) {
        foreach (array_unique($matches[0]) as $match) {
            $calc = calc(substr($match, 1, -1));
            $expression = str_replace([$match, "--", "*+", "/+"], [$calc, "+", "*", "/"], $expression);
        }
        return calc($expression);
    }
    $parts = explode("|", str_replace([ "+", "-", "*", "/", "||-|"], ["|+|", "|-|", "|*|", "|/|", "|-"], $expression));
    $index = 0;
    while ($index < count($parts)) {
        switch ($parts[$index]) {
            case "*":
                $value = array_pop($terms) * $parts[++$index];
                break;
            case "/":
                $value = array_pop($terms) / $parts[++$index];
                break;
            default:
                $value = $parts[$index];
                break;
        }
        $terms[] = $value;
        $index++;
    }
    return array_sum(explode("|", str_replace(["--", "+",  "-"], ["+", "|+", "|-"], implode($terms))));
}
