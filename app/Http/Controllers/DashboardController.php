<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Budget;
use App\Models\FinancialGoal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now();
        $sixMonthsAgo = $now->copy()->subMonths(6);

        // Données pour le graphique de flux de trésorerie
        $cashFlow = $this->getCashFlowData($user, $sixMonthsAgo, $now);

        // Données pour le graphique des top dépenses
        $topExpenses = $this->getTopExpensesData($user);

        // Données pour le graphique d'évolution de l'épargne
        $savings = $this->getSavingsData($user, $sixMonthsAgo, $now);

        // Statistiques mensuelles
        $monthlyStats = $this->getMonthlyStats($user);

        // Suggestions personnalisées
        $suggestions = $this->generateSuggestions($user);

        return view('dashboard', compact(
            'cashFlow',
            'topExpenses',
            'savings',
            'monthlyStats',
            'suggestions'
        ));
    }

    private function getCashFlowData($user, $startDate, $endDate)
    {
        $monthlyData = Transaction::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(CASE WHEN amount >= 0 THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN amount < 0 THEN ABS(amount) ELSE 0 END) as expenses')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $income = [];
        $expenses = [];

        foreach ($monthlyData as $data) {
            $date = Carbon::createFromDate($data->year, $data->month, 1);
            $labels[] = $date->format('M Y');
            $income[] = $data->income;
            $expenses[] = $data->expenses;
        }

        return [
            'labels' => $labels,
            'income' => $income,
            'expenses' => $expenses
        ];
    }

    private function getTopExpensesData($user)
    {
        $topExpenses = Transaction::where('user_id', $user->id)
            ->where('amount', '<', 0)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(ABS(amount)) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return [
            'labels' => $topExpenses->pluck('name')->toArray(),
            'data' => $topExpenses->pluck('total')->toArray()
        ];
    }

    private function getSavingsData($user, $startDate, $endDate)
    {
        $monthlySavings = Transaction::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(amount) as net_savings')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];
        $runningTotal = 0;

        foreach ($monthlySavings as $saving) {
            $date = Carbon::createFromDate($saving->year, $saving->month, 1);
            $labels[] = $date->format('M Y');
            $runningTotal += $saving->net_savings;
            $data[] = $runningTotal;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getMonthlyStats($user)
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        $stats = Transaction::where('user_id', $user->id)
            ->whereBetween('date', [$currentMonth, $nextMonth])
            ->select(
                DB::raw('SUM(CASE WHEN amount >= 0 THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN amount < 0 THEN ABS(amount) ELSE 0 END) as expenses')
            )
            ->first();

        return [
            'income' => $stats->income ?? 0,
            'expenses' => $stats->expenses ?? 0,
            'savings' => ($stats->income ?? 0) - ($stats->expenses ?? 0)
        ];
    }

    private function generateSuggestions($user)
    {
        $suggestions = [];
        
        // Analyse des dépenses par catégorie
        $categorySpending = Transaction::where('user_id', $user->id)
            ->where('amount', '<', 0)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(ABS(amount)) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();

        // Comparaison avec le mois précédent
        $previousMonthTotal = Transaction::where('user_id', $user->id)
            ->where('amount', '<', 0)
            ->whereMonth('date', Carbon::now()->subMonth()->month)
            ->whereYear('date', Carbon::now()->subMonth()->year)
            ->sum(DB::raw('ABS(amount)'));

        $currentMonthTotal = Transaction::where('user_id', $user->id)
            ->where('amount', '<', 0)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum(DB::raw('ABS(amount)'));

        // Générer des suggestions basées sur l'analyse
        if ($currentMonthTotal > $previousMonthTotal) {
            $suggestions[] = "Vos dépenses ce mois-ci sont plus élevées que le mois dernier. Pensez à revoir votre budget.";
        }

        if ($categorySpending->isNotEmpty()) {
            $topCategory = $categorySpending->first();
            $suggestions[] = "Votre catégorie de dépenses la plus importante est '{$topCategory->name}'. Envisagez d'établir un budget spécifique pour cette catégorie.";
        }

        // Suggestion d'épargne
        $monthlyIncome = Transaction::where('user_id', $user->id)
            ->where('amount', '>', 0)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');

        $savingsRate = ($monthlyIncome > 0) ? (($monthlyIncome - $currentMonthTotal) / $monthlyIncome) * 100 : 0;

        if ($savingsRate < 20) {
            $suggestions[] = "Votre taux d'épargne est actuellement de " . round($savingsRate, 1) . "%. Essayez d'atteindre un objectif de 20% d'épargne mensuelle.";
        }

        return $suggestions;
    }
} 