<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth')->group(function () {
    //Dashboard
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    //Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Deposit
    Route::get('/deposit', [TransactionController::class, 'depositIndex'])->name('deposits.index');
    Route::get('/deposit/create', [TransactionController::class, 'depositCreate'])->name('deposits.create');
    Route::post('/deposit', [TransactionController::class, 'depositStore'])->name('deposits.store');

    //Withdrawal
    Route::get('/withdrawal', [TransactionController::class, 'withdrawalIndex'])->name('withdrawals.index');
    Route::post('/withdrawal', [TransactionController::class, 'withdrawalStore'])->name('withdrawals.store');



});

require __DIR__.'/auth.php';
