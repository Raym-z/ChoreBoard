<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('user-chores', App\Http\Controllers\UserChoreController::class)->only(['update']);
    Route::resource('chores', App\Http\Controllers\ChoreController::class);
    Route::get('/household/invite', [App\Http\Controllers\InvitationController::class, 'showInvite'])->name('invitations.showInvite');
    Route::get('/join/{code?}', [App\Http\Controllers\InvitationController::class, 'joinForm'])->name('invitations.joinForm');
    Route::post('/join', [App\Http\Controllers\InvitationController::class, 'join'])->name('invitations.join');
    Route::post('/household/invite', [App\Http\Controllers\InvitationController::class, 'sendInvite'])->name('invitations.sendInvite');
    Route::delete('/invitations/{id}/revoke', [App\Http\Controllers\InvitationController::class, 'revoke'])->name('invitations.revoke');
});

require __DIR__.'/auth.php';