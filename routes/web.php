<?php

use Illuminate\Support\Facades\Route;

// routes/web.php
Route::get('/', function () {
    return response()->json(['message' => 'API Laravel funcionando en Vercel']);
});
