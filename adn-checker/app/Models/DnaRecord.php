<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class DnaRecord extends Eloquent
{
    protected $connection = 'mongodb';

    protected $fillable = ['dna', 'mutation'];

    protected $casts = [
        'dna' => 'array',
        'mutation' => 'boolean'
    ];
}
