<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RochaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JazidaController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard/rocha', [RochaController::class, 'index'])->name('rochas.index');

Route::resource('rochas', RochaController::class)->names('Rocha');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Rota Jazidas:
Route::resource('/jazidas', JazidaController::class)->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
