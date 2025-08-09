<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DnaController extends Controller
{
    public function hasMutation(Request $request)
    {
        $request->validate([
            'dna' => 'required|array',
            'dna.*' => 'required|string|regex:/^[ATCG]+$/i',
        ]);

        $dna = array_map('strtoupper', $request->dna);
        $n = count($dna);

        // Validar que sea una matriz NxN
        foreach ($dna as $row) {
            if (strlen($row) != $n) {
                return response()->json(['error' => 'La matriz de ADN debe ser NxN'], 400);
            }
        }

        $mutations = 0;

        // Verificar horizontal, vertical y diagonales
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                // Horizontal
                if ($j <= $n - 4) {
                    if ($dna[$i][$j] == $dna[$i][$j+1] && 
                        $dna[$i][$j] == $dna[$i][$j+2] && 
                        $dna[$i][$j] == $dna[$i][$j+3]) {
                        $mutations++;
                        if ($mutations > 1) {
                            $this->saveDnaRecord($dna, true);
                            return response()->json(['message' => 'Mutación detectada'], 200);
                        }
                    }
                }

                // Vertical
                if ($i <= $n - 4) {
                    if ($dna[$i][$j] == $dna[$i+1][$j] && 
                        $dna[$i][$j] == $dna[$i+2][$j] && 
                        $dna[$i][$j] == $dna[$i+3][$j]) {
                        $mutations++;
                        if ($mutations > 1) {
                            $this->saveDnaRecord($dna, true);
                            return response()->json(['message' => 'Mutación detectada'], 200);
                        }
                    }
                }

                // Diagonal derecha
                if ($i <= $n - 4 && $j <= $n - 4) {
                    if ($dna[$i][$j] == $dna[$i+1][$j+1] && 
                        $dna[$i][$j] == $dna[$i+2][$j+2] && 
                        $dna[$i][$j] == $dna[$i+3][$j+3]) {
                        $mutations++;
                        if ($mutations > 1) {
                            $this->saveDnaRecord($dna, true);
                            return response()->json(['message' => 'Mutación detectada'], 200);
                        }
                    }
                }

                // Diagonal izquierda
                if ($i <= $n - 4 && $j >= 3) {
                    if ($dna[$i][$j] == $dna[$i+1][$j-1] && 
                        $dna[$i][$j] == $dna[$i+2][$j-2] && 
                        $dna[$i][$j] == $dna[$i+3][$j-3]) {
                        $mutations++;
                        if ($mutations > 1) {
                            $this->saveDnaRecord($dna, true);
                            return response()->json(['message' => 'Mutación detectada'], 200);
                        }
                    }
                }
            }
        }

        $this->saveDnaRecord($dna, false);
        return response()->json(['message' => 'No se detectó mutación'], 403);
    }

    private function saveDnaRecord($dna, $hasMutation)
    {
        $dnaString = json_encode($dna);
        $hash = md5($dnaString);

        // Verificar si ya existe este ADN en la base de datos
        $existing = DB::table('dna_records')
            ->where('hash', $hash)
            ->first();

        if (!$existing) {
            DB::table('dna_records')->insert([
                'hash' => $hash,
                'dna' => $dnaString,
                'has_mutation' => $hasMutation,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function stats()
    {
        $countMutations = DB::table('dna_records')
            ->where('has_mutation', true)
            ->count();

        $countNoMutation = DB::table('dna_records')
            ->where('has_mutation', false)
            ->count();

        $ratio = $countMutations / ($countNoMutation + $countMutations);

        return response()->json([
            'count_mutations' => $countMutations,
            'count_no_mutation' => $countNoMutation,
            'ratio' => $ratio,
        ]);
    }

    public function list()
    {
        $records = DB::table('dna_records')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($record) {
                return [
                    'date' => $record->created_at,
                    'dna' => json_decode($record->dna),
                    'has_mutation' => $record->has_mutation,
                ];
            });

        return response()->json($records);
    }
}