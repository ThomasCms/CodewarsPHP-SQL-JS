<?php

namespace App;

/**
 * Class BreakPieces
 */
class BreakPieces
{

    private $color = 0;

    /**
     * @param array $part
     * @return string
     */
    private function formatPart(array $part): string
    {
        $formattedPart = $part;
        $left = count($part[0]);
        foreach ($formattedPart as $key => $row) {
            ksort($row);
            if (!preg_match('`-|\|`', implode('', $row), $matches, PREG_OFFSET_CAPTURE)) {
                unset($formattedPart[$key]);
            } else {
                if ($matches[0][1] < $left) {
                    $left = $matches[0][1];
                }
            }
        }

        foreach ($formattedPart as $key => $row) {
            ksort($row);
            $tmp = substr(implode('', $row), $left);
            $tmp = preg_replace('` -`', '+-', $tmp);
            $tmp = preg_replace('`- `', '-+', $tmp);
            while (preg_match('`-\+-`', $tmp)) {
                $tmp = preg_replace('`-\+-`', '---', $tmp);
            }
            $tmp = preg_replace('` \+ `', ' | ', $tmp);
            $tmp = preg_replace('`^\+ `', '| ', $tmp);
            $tmp = preg_replace('` \+$`', ' |', $tmp);
            $formattedPart[$key] = rtrim($tmp);
        }

        return implode("\n", array_values($formattedPart));
    }

    /**
     * @param array $coloredShape
     * @param $color
     * @return string
     */
    private function getPart(array $coloredShape, $color): string
    {
        $part = [];
        for ($i = 0; $i < count($coloredShape); $i++) {
            for ($j = 0; $j < count($coloredShape[$i]); $j++) {
                if ($coloredShape[$i][$j] === $color) {
                    $part[$i][$j] = ' ';
                    if (isset($coloredShape[$i - 1][$j]) && !is_numeric($coloredShape[$i - 1][$j])) {
                        $part[$i - 1][$j] = $coloredShape[$i - 1][$j];
                    }
                    if (isset($coloredShape[$i][$j + 1]) && !is_numeric($coloredShape[$i][$j + 1])) {
                        $part[$i][$j + 1] = $coloredShape[$i][$j + 1];
                    }
                    if (isset($coloredShape[$i + 1][$j]) && !is_numeric($coloredShape[$i + 1][$j])) {
                        $part[$i + 1][$j] = $coloredShape[$i + 1][$j];
                    }
                    if (isset($coloredShape[$i][$j - 1]) && !is_numeric($coloredShape[$i][$j - 1])) {
                        $part[$i][$j - 1] = $coloredShape[$i][$j - 1];
                    }
                }
                if (!isset($part[$i][$j]) && $coloredShape[$i][$j] !== $color) {
                    $part[$i][$j] = ' ';
                }
            }
        }
        return $this->formatPart($part);
    }

    /**
     * @param array $coloredShape
     * @return array
     */
    private function filterBackground(array $coloredShape): array
    {
        $background = [];
        for ($j = 0; $j < count($coloredShape[0]); $j++) {
            if (is_numeric($coloredShape[0][$j])) {
                $background[$coloredShape[0][$j]] = true;
            }
            if (is_numeric($coloredShape[count($coloredShape) - 1][$j])) {
                $background[$coloredShape[count($coloredShape) - 1][$j]] = true;
            }
        }
        for ($i = 1; $i < count($coloredShape) - 1; $i++) {
            if (is_numeric($coloredShape[$i][0])) {
                $background[$coloredShape[$i][0]] = true;
            }
            if (is_numeric($coloredShape[$i][count($coloredShape[$i]) - 1])) {
                $background[$coloredShape[$i][count($coloredShape[$i]) - 1]] = true;
            }
        }
        return $background;
    }

    /**
     * @param array $shapeArray
     * @param int $i
     * @param int $j
     * @return array
     */
    private function getColor(array $shapeArray, int $i, int $j): array
    {
        $colors = [];
        if (isset($shapeArray[$i - 1][$j]) && is_numeric($shapeArray[$i - 1][$j])) {
            $colors[] = $shapeArray[$i - 1][$j];
        }
        if (isset($shapeArray[$i][$j - 1]) && is_numeric($shapeArray[$i][$j - 1])) {
            $colors[] = $shapeArray[$i][$j - 1];
        }
        if (empty($colors)) {
            $colors[] = $this->color++;
        }

        return $colors;
    }

    /**
     * @param array $shapeArray
     * @return array
     */
    private function color(array $shapeArray): array
    {
        $coloredShape = $shapeArray;
        $colorFix = [];
        for ($i = 0; $i < count($shapeArray); $i++) {
            for ($j = 0; $j < count($shapeArray[$i]); $j++) {
                if ($coloredShape[$i][$j] === ' ') {
                    $colors = $this->getColor($coloredShape, $i, $j);

                    if (count(array_count_values($colors)) === 2) {
                        $colorFix[min($colors)] = max($colors);
                    }

                    $coloredShape[$i][$j] = min($colors);
                }
            }
        }
        for ($i = 0; $i < count($shapeArray); $i++) {
            for ($j = 0; $j < count($shapeArray[$i]); $j++) {
                foreach ($colorFix as $key => $value) {
                    if ($coloredShape[$i][$j] === $key) {
                        $coloredShape[$i][$j] = $value;
                    }
                }
            }
        }
        return $coloredShape;
    }

    /**
     * @return array
     */
    public function process($shape): array
    {
        $shapeArray = preg_split('`\r\n|\r|\n`', $shape);
        foreach ($shapeArray as $key => $string) {
            $shapeArray[$key] = str_split($string);
        }
        $coloredShape = $this->color($shapeArray);
        $background = $this->filterBackground($coloredShape);
        $shapeParts = [];
        $currentColor = 0;
        while ($currentColor < $this->color) {
            if (!isset($background[(string)$currentColor])) {
                $tmp = $this->getPart($coloredShape, $currentColor);
                if (!empty($tmp)) {
                    $shapeParts[] = $tmp;
                }
            }
            $currentColor++;
        }
        $this->color = 0;

        return $shapeParts;
    }
}
