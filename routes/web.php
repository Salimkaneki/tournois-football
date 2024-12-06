<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KpessekouController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\KpessekouMatchController;
use App\Http\Controllers\MatchGeneratorController;
use App\Http\Controllers\MatchZobibiController;


// Route::get('/', function () {
//     return view('welcome');
// });




Route::get('/', function () {
    return view('menu');
})->name('menu.view');



// Route::get('/zobibi', function () {
//     return view('zobibi');
// })->name('zobibi.view');

// Route::get('/stats', function () {
//     return view('statistiques');
// })->name('stats.view');

// Route::get('/reset', function () {
//     return view('reset');
// })->name('reset.view');

Route::get('/kpessekou', [KpessekouMatchController::class, 'index'])->name('kpessekou.index');
Route::post('/generate-kpessekou-match', [KpessekouMatchController::class, 'generateMatch'])->name('generate.kpessekou');



// Optional: Route to get match history
Route::get('/kpessekou-history', [KpessekouMatchController::class, 'matchHistory'])
    ->name('kpessekou.history');

    Route::get('/match-generator', [MatchGeneratorController::class, 'index'])->name('match.index');
Route::post('/generer-match', [MatchGeneratorController::class, 'genererMatch'])->name('match.generer');

Route::get('/match-zobibi', [MatchZobibiController::class, 'index']);
Route::post('/generer-match-zobibi', [MatchZobibiController::class, 'genererMatch'])->name('generer-match-zobibi');