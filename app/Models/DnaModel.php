<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class DnaModel extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'dna_records'; 
    protected $fillable = ['dna', 'mutation'];
    protected $casts = [
        'dna' => 'array',
        'mutation' => 'boolean'
    ];
}
