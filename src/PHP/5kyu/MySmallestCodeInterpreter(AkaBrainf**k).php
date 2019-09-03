<?php

/**
 * @param string $code
 * @param string $input
 * @return string
 */
function brainfuck(string $code, string $input): string
{
    $cell = [];
    $iptr  = 0;
    $ptr   = 0;
    $checked = "if(!array_key_exists(\$ptr, \$cell))  \$cell[\$ptr] = 0;  ";
    $output = "";
    $code = implode("\n", array_map(function ($char) use ($checked) {
        switch ($char) {
            case '>':
                return "\$ptr += 1;";
            case '<':
                return "\$ptr -= 1;";
            case '+':
                return "$checked \$cell[\$ptr] = (\$cell[\$ptr] + 1) % 256;";
            case '-':
                return "$checked \$cell[\$ptr] = (\$cell[\$ptr] + 255) % 256;";
            case '.':
                return "$checked\$output .= chr(\$cell[\$ptr]);";
            case ',':
                return "\$cell[\$ptr] = ord(\$input[\$iptr++]);";
            case '[':
                return "$checked while(\$cell[\$ptr]){ ";
            case ']':
                return "$checked if(!\$cell[\$ptr]) break; }";
        }
    }, str_split($code)));

    echo $code;
    eval($code);
    return $output;
}
