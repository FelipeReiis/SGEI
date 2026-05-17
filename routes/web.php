<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\GerenciadorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TurmaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', function () {
    return Inertia::render('Home');
})->middleware(['auth', 'verified'])->name('Home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/alunos', AlunoController::class);
    Route::resource('/funcionarios', FuncionarioController::class);
    Route::resource('/turmas', TurmaController::class);
    Route::resource('/eventos', EventoController::class);
    Route::resource('/gerencia',GerenciadorController::class);
});

require __DIR__.'/auth.php';
