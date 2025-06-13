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
    if (auth()->check()) {
        switch(auth()->user()->role) {
            case 'ADMIN':
                return redirect()->route('admin.dashboard');
            case 'CONSEILLER_FINANCIER':
                return redirect()->route('advisors.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }
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

// Test route to send a goal alert email manually
use Illuminate\Support\Facades\Mail;
use App\Mail\GoalAlert;
use App\Models\FinancialGoal;

Route::get('/test-goal-alert', function (\Illuminate\Http\Request $request) {
    $to = $request->query('to', 'anouarelissaoui20@gmail.com');
    $goal = FinancialGoal::first();
    if (!$goal) return 'No financial goals found.';
    Mail::to($to)->send(new GoalAlert($goal));
    return 'Goal alert email sent to ' . $to;
});

// Admin routes
Route::middleware(['auth', 'role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('dashboard');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
});

// Advisor routes
Route::middleware(['auth', 'role:CONSEILLER_FINANCIER'])->prefix('advisor')->name('advisors.')->group(function () {
    Route::get('/dashboard', [AdvisorController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AdvisorController::class, 'profile'])->name('profile');
    Route::get('/history', [AdvisorController::class, 'history'])->name('history');
    Route::get('/clients', [AdvisorController::class, 'clients'])->name('clients');
    Route::get('/reports', [AdvisorController::class, 'reports'])->name('reports');
    Route::get('/schedule', [AdvisorController::class, 'schedule'])->name('schedule');
    Route::get('/schedule/settings', [AdvisorController::class, 'scheduleSettings'])->name('schedule.settings');
});

// User routes
Route::middleware(['auth', 'role:UTILISATEUR'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Transactions
    Route::get('/transactions/export', [TransactionController::class, 'exportCsv'])->name('transactions.export');
    Route::resource('transactions', TransactionController::class);
    Route::post('/transactions/import', [TransactionController::class, 'import'])->name('transactions.import');
    
    // Budgets
    Route::resource('budgets', BudgetController::class);
    Route::get('/budgets/check-alerts', [BudgetController::class, 'checkAlerts'])->name('budgets.check-alerts');
    
    // Financial Goals
    Route::resource('goals', FinancialGoalController::class);
    Route::get('/goals/{goal}/simulate', [FinancialGoalController::class, 'simulateSavings'])->name('goals.simulate');
});
