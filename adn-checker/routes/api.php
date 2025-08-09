use App\Http\Controllers\DnaController;

Route::post('/mutation', [DnaController::class, 'mutation']);
Route::get('/stats', [DnaController::class, 'stats']);
Route::get('/list', [DnaController::class, 'list']);
