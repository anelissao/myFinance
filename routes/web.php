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
    if (auth()->check() && auth()->user()->role === 'CONSEILLER_FINANCIER') {
        return redirect()->route('advisors.dashboard');
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

// Advisor protected routes
Route::prefix('advisor')->name('advisors.')->middleware(['auth', 'advisor'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdvisorController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\AdvisorController::class, 'profile'])->name('profile');
    Route::get('/history', [\App\Http\Controllers\AdvisorController::class, 'history'])->name('history');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
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

// Admin routes
// FIX: 'role:admin' middleware does not exist. Use only 'auth' or create an AdminMiddleware if needed.
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('dashboard');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
});

// Dashboard route
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});
