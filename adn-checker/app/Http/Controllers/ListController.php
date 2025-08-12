<?php

namespace App\Http\Controllers;

use App\Models\DnaModel;

class ListController extends Controller
{
    public function getLastRecords()
    {
        $records = DnaModel::orderBy('created_at', 'desc')
            ->take(10)
            ->get(['dna', 'has_mutation', 'created_at']);

        return response()->json($records);
    }
}
