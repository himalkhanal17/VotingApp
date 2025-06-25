<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PollController as AdminPollController;
use App\Http\Controllers\PollController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('polls', AdminPollController::class);
    Route::post('/polls/{poll}/stop', [AdminPollController::class, 'stop'])->name('polls.stop');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', fn() => view('welcome'));

Route::get('/polls', [PollController::class, 'index'])->name('website.polls.index');
Route::get('/polls/{poll}', [PollController::class, 'show'])->name('website.polls.show');

Route::get('/polls/vote/{token}', [PollController::class, 'voteForm'])->name('website.polls.voteForm');
Route::post('/polls/vote', [PollController::class, 'vote'])->name('polls.vote');
Route::post('/polls/{poll}/request-token', [PollController::class, 'requestToken'])->name('polls.request-vote-token');

Route::get('/dashboard', fn() => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
