<?php

use App\Http\Controllers\Recruteur\DashboardController as RecruteurDashboard;
use App\Http\Controllers\Recruteur\JobOfferController as RecruteurJobOffer;
use App\Http\Controllers\Recruteur\ApplicationController as RecruteurApplication;
use App\Http\Controllers\Recruteur\PaymentController as RecruteurPayment;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:recruiter'])->prefix('recruteur')->name('recruteur.')->group(function () {
    Route::get('/', [RecruteurDashboard::class, 'index'])->name('dashboard');
    Route::get('offres/{jobOffer}/paiement', [RecruteurPayment::class, 'checkout'])->name('offres.checkout');
    Route::get('offres/{jobOffer}/paiement/success', [RecruteurPayment::class, 'success'])->name('payment.success');
    Route::post('offres/{jobOffer}/publish', [RecruteurJobOffer::class, 'publish'])->name('offres.publish');
    Route::resource('offres', RecruteurJobOffer::class)->names('offres');
    Route::get('offres/{jobOffer}/candidatures', [RecruteurApplication::class, 'index'])->name('offres.candidatures');
    Route::get('offres/{jobOffer}/candidatures/comparer', [RecruteurApplication::class, 'compare'])->name('offres.candidatures.compare');
    Route::post('candidatures/{application}/action', [RecruteurApplication::class, 'action'])->name('candidatures.action');
});
