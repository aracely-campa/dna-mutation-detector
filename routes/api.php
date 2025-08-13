<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DnaMutationController;

Route::post('/mutation', [DnaMutationController::class, 'hasMutation']);
Route::get('/stats', [DnaMutationController::class, 'stats']);
Route::get('/list', [DnaMutationController::class, 'list']);
