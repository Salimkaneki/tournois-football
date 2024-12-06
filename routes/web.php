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



Route::get('/kpessekou-history', [KpessekouMatchController::class, 'matchHistory'])->name('kpessekou.history');


Route::get('/zobibi', [MatchZobibiController::class, 'index'])->name('zobibi.index');
Route::post('/generer-match-zobibi', [MatchZobibiController::class, 'genererMatch'])->name('generer-match-zobibi');

Route::get('/zobibi/historique', [MatchZobibiController::class, 'historique'])->name('zobibi-historique');
Route::post('/zobibi/reinitialiser', [MatchZobibiController::class, 'reinitialiserMatchs'])->name('reinitialiser-matchs');

Route::get('/kpessekou/historique', [KpessekouMatchController::class, 'matchHistory'])
    ->name('kpessekou.historique');