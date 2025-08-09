<?php

namespace App\Http\Controllers;

use App\Services\DnaService;
use App\Models\DnaRecord;
use Illuminate\Http\Request;

class DnaController extends Controller
{
    public function mutation(Request $request, DnaService $service)
    {
        $data = $request->validate([
            'dna' => 'required|array|min:4',
            'dna.*' => 'required|string'
        ]);

        $hasMutation = $service->hasMutation($data['dna']);

        DnaRecord::firstOrCreate(
            ['dna' => $data['dna']],
            ['mutation' => $hasMutation]
        );

        return $hasMutation
            ? response()->json(['mutation' => true], 200)
            : response()->json(['mutation' => false], 403);
    }

    public function stats()
    {
        $mutations = DnaRecord::where('mutation', true)->count();
        $noMutations = DnaRecord::where('mutation', false)->count();

        $ratio = $noMutations > 0 ? $mutations / $noMutations : 0;

        return response()->json([
            'count_mutations' => $mutations,
            'count_no_mutation' => $noMutations,
            'ratio' => $ratio
        ]);
    }

    public function list()
    {
        return DnaRecord::latest()->take(10)->get();
    }
}
