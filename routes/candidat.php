<?php

use App\Http\Controllers\Candidat\DashboardController as CandidatDashboard;
use App\Http\Controllers\Candidat\ProfileController as CandidatProfile;
use App\Http\Controllers\Candidat\ApplicationController as CandidatApplication;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:candidate'])->prefix('candidat')->name('candidat.')->group(function () {
    Route::get('/', [CandidatDashboard::class, 'index'])->name('dashboard');
    Route::get('/profil', [CandidatProfile::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [CandidatProfile::class, 'update'])->name('profile.update');
    Route::post('/profil/cv', [CandidatProfile::class, 'uploadCv'])->name('profile.cv.upload');
    Route::get('/candidatures', [CandidatDashboard::class, 'applications'])->name('applications.index');
    Route::get('/candidatures/creer/{jobOffer}', [CandidatApplication::class, 'create'])->name('applications.create');
    Route::post('/candidatures/creer/{jobOffer}', [CandidatApplication::class, 'store'])->name('applications.store');
});
