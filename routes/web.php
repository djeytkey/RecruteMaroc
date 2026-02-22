<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobOfferController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/offres', [JobOfferController::class, 'index'])->name('job-offers.index');
Route::get('/offres/{jobOffer}', [JobOfferController::class, 'show'])->name('job-offers.show');
Route::get('/contact', fn () => view('contact'))->name('contact');

Route::post('webhook/stripe', [\App\Http\Controllers\Recruteur\PaymentController::class, 'webhook'])->name('webhook.stripe');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isCandidate()) return redirect()->route('candidat.dashboard');
    if ($user->isRecruiter()) return redirect()->route('recruteur.dashboard');
    if ($user->isAdmin()) return redirect()->route('admin.dashboard');
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/candidat.php';
require __DIR__.'/recruteur.php';
require __DIR__.'/admin.php';
