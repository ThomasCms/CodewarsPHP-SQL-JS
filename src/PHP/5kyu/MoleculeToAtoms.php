<?php

/**
 * @param string $formula
 * @return array
 */
function parse_molecule(string $formula): array
{
    $molecule = '(' . $formula . ')';
    do {
        $molecule = preg_replace_callback('/[\(\[\{](\w+)[\)\]\}](\d+)?/', function ($matches) {
            return parseBrackets($matches[1], $matches[2] ?? 1);
        }, $molecule, -1, $count);
    } while ($count);

    $atoms = [];
    preg_match_all('/([A-Z][a-z]*)(\d+)?/', $molecule, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $atom = $match[1];
        $atoms[$atom] =  ($atoms[$atom] ?? 0) + $match[2];
    }
    return $atoms;
}

/**
 * @param string $string
 * @param $multiply
 * @return string|string[]|null
 */
function parseBrackets(string $string, $multiply)
{
    return preg_replace_callback('/([A-Z][a-z]*)(\d+)?/', function ($matches) use ($multiply) {
        return $matches[1] . ($matches[2] ?? 1) * $multiply;
    }, $string);
}
