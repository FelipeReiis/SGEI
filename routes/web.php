<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\GerenciadorController;
use App\Http\Controllers\GerenciaMensalidadeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TurmaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Schedule;

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

Route::get('/health', function () {
    return response('OK', 200);
});

Route::get('/home', function () {
    return Inertia::render('Home');
})->middleware(['auth', 'verified'])->name('Home');

Route::middleware('auth')->group(function () {
     Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/alunos', AlunoController::class);
    Route::get('/aluno/status/{id}', [AlunoController::class , 'updateStatus'])->name('update.status');
    Route::resource('/funcionarios', FuncionarioController::class);
    Route::resource('/turmas', TurmaController::class);
    Route::resource('/eventos', EventoController::class);
    Route::resource('/gerencia',GerenciadorController::class);
    Route::resource('/mensalidades',GerenciaMensalidadeController::class);
    Route::get('/export/evento_aluno/{idEvento}', [GerenciadorController::class, 'eventoAlunoExport'])->name('export.evento.alunos');
    Route::get('/export/mensalidade_aluno/{idMensalidade}', [GerenciaMensalidadeController::class, 'mensalidadeAlunoExport'])->name('export.mensalidade.alunos');
});


// Executa o comando criado uma vez por ano (1º de Janeiro às 00:00)
Schedule::command('mensalidades:gerar-ano')->yearly();
require __DIR__.'/auth.php';
