<?php
use Jenssegers\Mongodb\Eloquent\Model;

class Dna extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'dnas';
    protected $fillable = ['dna','mutation','hash'];
    protected $casts = [
        'dna' => 'array',
        'mutation' => 'boolean',
    ];
    public $timestamps = true;
}
