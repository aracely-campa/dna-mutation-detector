<?php

namespace App\Services;

class DnaService
{
    public function hasMutation(array $dna): bool
    {
        $n = count($dna);
        $count = 0;

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $letter = $dna[$i][$j] ?? null;
                if (!$letter || !in_array($letter, ['A','T','C','G'])) return false;

                if ($this->checkDirection($dna, $i, $j, 1, 0) ||
                    $this->checkDirection($dna, $i, $j, 0, 1) ||
                    $this->checkDirection($dna, $i, $j, 1, 1) ||
                    $this->checkDirection($dna, $i, $j, 1, -1)) {
                    $count++;
                    if ($count > 1) return true;
                }
            }
        }
        return false;
    }

    private function checkDirection($dna, $x, $y, $dx, $dy): bool
    {
        $n = count($dna);
        $char = $dna[$x][$y] ?? null;
        if (!$char) return false;

        for ($k = 1; $k < 4; $k++) {
            $nx = $x + $dx * $k;
            $ny = $y + $dy * $k;
            if ($nx < 0 || $ny < 0 || $nx >= $n || $ny >= $n || $dna[$nx][$ny] !== $char) {
                return false;
            }
        }
        return true;
    }
}
