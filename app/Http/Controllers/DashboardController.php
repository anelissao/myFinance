<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Budget;
use App\Models\FinancialGoal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get monthly income and expenses
        $monthlyStats = Transaction::where('user_id', $user->id)
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->select(
                DB::raw('SUM(CASE WHEN type = "INCOME" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "EXPENSE" THEN amount ELSE 0 END) as total_expenses')
            )
            ->first();

        // Get top expense categories
        $topExpenses = Transaction::where('user_id', $user->id)
            ->where('type', 'EXPENSE')
            ->whereMonth('date', now()->month)
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(amount) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Get budget progress
        $budgets = Budget::where('user_id', $user->id)
            ->whereMonth('month', now()->month)
            ->with('category')
            ->get()
            ->map(function ($budget) {
                $budget->progress = $budget->getProgressPercentage();
                return $budget;
            });

        // Get financial goals progress
        $goals = FinancialGoal::where('user_id', $user->id)
            ->get()
            ->map(function ($goal) {
                $goal->progress = $goal->getProgressPercentage();
                return $goal;
            });

        // Get recent transactions
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with(['category', 'account'])
            ->orderByDesc('date')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'monthlyStats',
            'topExpenses',
            'budgets',
            'goals',
            'recentTransactions'
        ));
    }
} 