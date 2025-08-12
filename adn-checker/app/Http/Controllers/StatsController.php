<?php

namespace App\Http\Controllers;

use App\Models\DnaModel;

class StatsController extends Controller
{
    public function getStats()
    {
        $countMutations = DnaModel::where('has_mutation', true)->count();
        $countNoMutation = DnaModel::where('has_mutation', false)->count();

        $ratio = $countNoMutation > 0 
            ? $countMutations / $countNoMutation 
            : 0;

        return response()->json([
            'count_mutations' => $countMutations,
            'count_no_mutation' => $countNoMutation,
            'ratio' => round($ratio, 2)
        ]);
    }
}
