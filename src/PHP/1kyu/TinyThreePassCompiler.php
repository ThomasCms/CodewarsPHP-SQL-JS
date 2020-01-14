<?php

namespace App;

class Compiler
{
    public function compile($program)
    {
        return pass3(pass2(pass1($program)));
    }

    public function tokenize($program)
    {
        /*
         * Turn a program string into an array of tokens.  Each token
         * is either '[', ']', '(', ')', '+', '-', '*', '/', a variable
         * name or a number (as a string)
         */
        $tokens = preg_split('/\s+/', trim(preg_replace('/([-+*\/\(\)\[\]])/', ' $1 ', $program)));
        foreach ($tokens as &$token) {
            if (is_numeric($token)) {
                $token = (int) $token;
            }
        }
        return $tokens;
    }

    public function pass1($program)
    {
        // Returns an un-optimized AST
        $tokens = $this->tokenize($program);
        $this->args = array_flip(array_slice($tokens, 1, array_search(']', $tokens) - 1));
        $this->expression = array_slice($tokens, array_search(']', $tokens) + 1);
        return $this->expression();
    }

    private function expression()
    {
        $node = $this->term();
        while (in_array(current($this->expression), ['+', '-'])) {
            $token = current($this->expression);
            if (in_array($token, ['+', '-'])) {
                next($this->expression);
                $node = ['a' => $node, 'op' => $token, 'b' => $this->term()];
            }
        }
        return $node;
    }

    private function term()
    {
        $node = $this->factor();
        while (in_array(current($this->expression), ['*', '/'])) {
            $token = current($this->expression);
            if (in_array($token, ['*', '/'])) {
                next($this->expression);
                $node = ['a' => $node, 'op' => $token, 'b' => $this->factor()];
            }
        }
        return $node;
    }

    private function factor()
    {
        $token = current($this->expression);
        if (is_numeric($token)) {
            next($this->expression);
            return ['op' => 'imm', 'n' => $token];
        } elseif (array_key_exists($token, $this->args)) {
            next($this->expression);
            return ['op' => 'arg', 'n' => $this->args[$token]];
        } elseif ($token === '(') {
            next($this->expression);
            $node = $this->expression();
            next($this->expression);
            return $node;
        }
        return $token;
    }

    private function apply($a, $b, $op)
    {
        switch ($op) {
            case '*':
                return $a * $b;
            case '+':
                return $a + $b;
            case '-':
                return $a - $b;
            case '/':
                return $a / $b;
        }
    }

    public function pass2($node)
    {
        if (in_array($node['op'], ['*','+','-','/'])) {
            $node['a'] = $this->pass2($node['a'], 0);
            $node['b'] = $this->pass2($node['b'], 0);
            if ('imm' == $node['a']['op'] && $node['a']['op'] == $node['b']['op']) {
                return [
                    'op' => 'imm',
                    'n' => $this->apply($node['a']['n'], $node['b']['n'], $node['op'])
                ];
            }
        }
        return $node;
    }

    public function pass3($node, $p = 1)
    {
        switch ($node['op']) {
            case 'imm':
                return ['IM ' . $node['n']];
            case 'arg':
                return ['SW', 'AR ' . $node['n']];
            case '*':
                $op = 'MU';
                break;
            case '+':
                $op = 'AD';
                break;
            case '-':
                $op = 'SU';
                break;
            case '/':
                $op = 'DI';
                break;
        }

        $ins = array_merge(
            $this->pass3($node['a'], 0),
            ['PU'],
            $this->pass3($node['b'], 0),
            ['SW', 'PO', $op, 'PU']
        );
        $ins[] = 'PO';
        return $ins;
    }
}
