<?php
namespace App\Http\Controllers;

use App\Models\DnaModel;
use App\Services\ServiceDnaMutation;
use Illuminate\Http\Request;
use App\Services\MutationService;
use App\Models\Dna;
use MongoDB\Driver\Exception\BulkWriteException;

class DnaMutationController extends Controller
{
    public function hasMutation(Request $request, ServiceDnaMutation $service)
    {
        $request->validate([
            'dna' => 'required|array|min:4',
            'dna.*' => ['required','string','regex:/^[ATCGatcg]+$/']
        ]);

        $dna = array_map('strtoupper', $request->input('dna'));
        $n = count($dna);
        foreach ($dna as $row) {
            if (strlen($row) !== $n) {
                return response()->json(['error' => 'Matriz no es NxN'], 422);
            }
        }

        $hash = sha1(serialize($dna));

        // check cache in DB
        $record = DnaModel::where('hash', $hash)->first();
        if ($record) {
            $hasMutation = (bool) $record->mutation;
        } else {
            $hasMutation = $service->hasMutation($dna);
            try {
                DnaModel::create(['dna'=>$dna,'mutation'=>$hasMutation,'hash'=>$hash]);
            } catch (BulkWriteException $e) {
                // posible duplicado por carrera: recuperar registro existente
                $existing = DnaModel::where('hash', $hash)->first();
                if ($existing) $hasMutation = (bool) $existing->mutation;
            }
        }

        return $hasMutation
            ? response()->json(['mutation' => true], 200)
            : response()->json(['mutation' => false], 403);
    }

    public function stats()
    {
        $mutations = DnaModel::where('mutation', true)->count();
        $noMutations = DnaModel::where('mutation', false)->count();
        $ratio = $noMutations > 0 ? round($mutations / $noMutations, 2) : null;
        return response()->json([
            'count_mutations' => $mutations,
            'count_no_mutation' => $noMutations,
            'ratio' => $ratio
        ]);
    }

    public function list()
    {
        $dnas = DnaModel::orderBy('created_at','desc')->take(10)->get();
        return response()->json($dnas->map(fn($i) => [
            'dna' => $i->dna,
            'mutation' => $i->mutation,
            'created_at' => $i->created_at,
        ]));
    }
}
