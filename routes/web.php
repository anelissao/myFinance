<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\FinancialGoalController;
use App\Http\Controllers\AdvisorController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/advisors', [AdvisorController::class, 'index'])->name('advisors.index');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Transactions
    Route::resource('transactions', TransactionController::class);
    Route::post('/transactions/import', [TransactionController::class, 'import'])->name('transactions.import');
    
    // Budgets
    Route::resource('budgets', BudgetController::class);
    Route::get('/budgets/check-alerts', [BudgetController::class, 'checkAlerts'])->name('budgets.check-alerts');
    
    // Financial Goals
    Route::resource('goals', FinancialGoalController::class);
    Route::get('/goals/{goal}/simulate', [FinancialGoalController::class, 'simulateSavings'])->name('goals.simulate');
});

// Admin routes
Route::middleware(['auth', 'role:ADMIN'])->group(function () {
    Route::resource('advisors', AdvisorController::class)->except(['index']);
});
