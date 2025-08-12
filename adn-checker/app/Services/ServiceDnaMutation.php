<?php
namespace App\Services;

class ServiceDnaMutation
{
    public function hasMutation(array $dna): bool
    {
        $n = count($dna);
        if ($n < 4) return false;

        // Normalizar a mayúsculas
        $dna = array_map('strtoupper', $dna);

        $countSequences = 0;

        // DP arrays
        $dpVer = array_fill(0, $n, array_fill(0, $n, 1));
        $dpDiag = array_fill(0, $n, array_fill(0, $n, 1));
        $dpInv  = array_fill(0, $n, array_fill(0, $n, 1));

        for ($i = 0; $i < $n; $i++) {
            $dpHor = array_fill(0, $n, 1);
            for ($j = 0; $j < $n; $j++) {
                // Horizontal
                if ($j > 0 && $dna[$i][$j] === $dna[$i][$j - 1]) {
                    $dpHor[$j] = $dpHor[$j - 1] + 1;
                    if ($dpHor[$j] === 4) $countSequences++;
                }

                // Vertical
                if ($i > 0 && $dna[$i][$j] === $dna[$i - 1][$j]) {
                    $dpVer[$i][$j] = $dpVer[$i - 1][$j] + 1;
                    if ($dpVer[$i][$j] === 4) $countSequences++;
                }

                // Diagonal (\)
                if ($i > 0 && $j > 0 && $dna[$i][$j] === $dna[$i - 1][$j - 1]) {
                    $dpDiag[$i][$j] = $dpDiag[$i - 1][$j - 1] + 1;
                    if ($dpDiag[$i][$j] === 4) $countSequences++;
                }

                // Diagonal invertida (/)
                if ($i > 0 && $j < $n - 1 && $dna[$i][$j] === $dna[$i - 1][$j + 1]) {
                    $dpInv[$i][$j] = $dpInv[$i - 1][$j + 1] + 1;
                    if ($dpInv[$i][$j] === 4) $countSequences++;
                }

                // early exit
                if ($countSequences >= 2) return true;
            }
        }

        return $countSequences >= 2;
    }
}
