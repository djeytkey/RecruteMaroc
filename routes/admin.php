<?php

use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\ExportController as AdminExportController;
use App\Http\Controllers\Admin\JobOfferController as AdminJobOfferController;
use App\Http\Controllers\Admin\RewardController as AdminRewardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.users.index'))->name('dashboard');
    Route::resource('users', AdminUserController::class)->only(['index', 'edit', 'update'])->names('users');
    Route::resource('companies', AdminCompanyController::class)->only(['index', 'edit', 'update'])->names('companies');
    Route::get('offers', [AdminJobOfferController::class, 'index'])->name('offers.index');
    Route::get('rewards', [AdminRewardController::class, 'index'])->name('rewards.index');
    Route::patch('rewards/{reward}', [AdminRewardController::class, 'update'])->name('rewards.update');
    Route::get('exports', [AdminExportController::class, 'index'])->name('exports.index');
    Route::get('exports/users', [AdminExportController::class, 'users'])->name('exports.users');
    Route::get('exports/companies', [AdminExportController::class, 'companies'])->name('exports.companies');
    Route::get('exports/offers', [AdminExportController::class, 'offers'])->name('exports.offers');
    Route::get('exports/applications', [AdminExportController::class, 'applications'])->name('exports.applications');
    Route::get('exports/rewards', [AdminExportController::class, 'rewards'])->name('exports.rewards');
});
