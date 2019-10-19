<?php

namespace App;

/**
 * Class GeneticAlgorithm
 */
class GeneticAlgorithm
{
    /**
     * @param $length
     * @return array
     * @throws Exception
     */
    protected function generate($length): array
    {
        $chromosome = [];
        for ($i = 0; $i < $length; $i++) {
            $chromosome[] = random_int(0, 1);
        }

        return $chromosome;
    }

    /**
     * @param $population
     * @param $fitness
     * @return array
     */
    protected function select($population, $fitness): array
    {
        $all_fitness = [];

        foreach ($population as $chromosome) {
            $all_fitness[] = $fitness(implode('', $chromosome));
        }

        asort($all_fitness);
        $keys = array_reverse(array_keys($all_fitness));

        return [$population[$keys[0]], $population[$keys[1]]];
    }

    /**
     * @param $chromosome
     * @param $p
     */
    protected function mutate(&$chromosome, $p)
    {
        foreach ($chromosome as &$bit) {
            if (mt_rand() / mt_getrandmax() < $p) {
                if ($bit) {
                    $bit = 0;
                } else {
                    $bit = 1;
                }
            }
        }
    }

    /**
     * @param $chromosome1
     * @param $chromosome2
     * @return array
     * @throws Exception
     */
    protected function crossover($chromosome1, $chromosome2): array
    {
        $random_bit = random_int(0, count($chromosome1) - 1);
        if ($random_bit > 0) {
            $temp = $chromosome2;
            $chromosome2 = array_merge(
                array_slice($chromosome2, 0, $random_bit),
                array_slice($chromosome1, $random_bit)
            );
            $chromosome1 = array_merge(array_slice($chromosome1, 0, $random_bit), array_slice($temp, $random_bit));
        }

        return [$chromosome1, $chromosome2];
    }

    /**
     * @param $fitness
     * @param $length
     * @param $p_c
     * @param $p_m
     * @param int $iterations
     * @return string
     * @throws Exception
     */
    public function run($fitness, $length, $p_c, $p_m, int $iterations = 200): string
    {
        $population = [];

        for ($i = 0; $i < 20; $i++) {
            $population[] = $this->generate($length);
        }

        for ($i = 0; $i < $iterations; $i++) {
            $new_population = [];
            while (count($new_population) < count($population)) {
                $selected = $this->select($population, $fitness);

                if (mt_rand() / mt_getrandmax() < $p_c) {
                    $selected = $this->crossover($selected[0], $selected[1]);
                }

                $this->mutate($selected[0], $p_m);
                $this->mutate($selected[1], $p_m);

                $new_population[] = $selected[0];
                $new_population[] = $selected[1];
            }

            $population = $new_population;
        }

        $selected = $this->select($population, $fitness);
        return implode('', $selected[0]);
    }
}
