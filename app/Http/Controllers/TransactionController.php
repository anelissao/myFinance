<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Budget;
use Illuminate\Support\Facades\Mail;
use App\Mail\BudgetExceeded;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class TransactionController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function exportCsv(Request $request)
    {
        $userId = auth()->id();
        $expenses = \App\Models\Transaction::where('user_id', $userId)->where('type', 'EXPENSE')->get();
        $income = \App\Models\Transaction::where('user_id', $userId)->where('type', 'INCOME')->get();
        $goals = \App\Models\FinancialGoal::where('user_id', $userId)->get();

        $callback = function() use ($expenses, $income, $goals) {
            $handle = fopen('php://output', 'w');
            // Expenses
            fputcsv($handle, ['--- Dépenses ---']);
            fputcsv($handle, ['Date', 'Montant', 'Description', 'Catégorie', 'Compte']);
            foreach ($expenses as $t) {
                fputcsv($handle, [
                    $t->date,
                    $t->amount,
                    $t->description,
                    $t->category ? $t->category->name : '',
                    $t->account ? $t->account->name : '',
                ]);
            }
            fputcsv($handle, []);
            // Income
            fputcsv($handle, ['--- Revenus ---']);
            fputcsv($handle, ['Date', 'Montant', 'Description', 'Catégorie', 'Compte']);
            foreach ($income as $t) {
                fputcsv($handle, [
                    $t->date,
                    $t->amount,
                    $t->description,
                    $t->category ? $t->category->name : '',
                    $t->account ? $t->account->name : '',
                ]);
            }
            fputcsv($handle, []);
            // Goals
            fputcsv($handle, ['--- Objectifs Financiers ---']);
            fputcsv($handle, ['Nom', 'Montant cible', 'Montant actuel', 'Date limite']);
            foreach ($goals as $g) {
                fputcsv($handle, [
                    $g->name,
                    $g->target_amount,
                    $g->current_amount,
                    $g->due_date,
                ]);
            }
            fclose($handle);
        };

        $filename = 'mes_finances_' . now()->format('Ymd_His') . '.csv';
        return response()->stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
        ]);
    }
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request)
    {
        $query = Transaction::where('user_id', auth()->id())
            ->with(['category', 'account']);

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->orderByDesc('date')->paginate(20);

        $categories = Category::all();
        $accounts = Account::where('user_id', auth()->id())->get();
        // --- Persistent Goal Overrun Notification ---
        $userId = auth()->id();
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $totalExpenses = \App\Models\Transaction::where('user_id', $userId)
            ->where('type', 'EXPENSE')
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->sum('amount');
        $goals = \App\Models\FinancialGoal::where('user_id', $userId)->get();
        $exceededGoals = [];
        foreach ($goals as $goal) {
            if ($totalExpenses > $goal->target_amount) {
                $exceededGoals[] = $goal->name;
            }
        }
        $goalAlert = null;
        if (!empty($exceededGoals)) {
            $goalAlert = "Alerte : Vous avez dépassé le montant cible pour l'objectif financier : " . implode(', ', $exceededGoals);
        }
        return view('transactions.index', compact('transactions', 'categories', 'accounts', 'goalAlert'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        $accounts = \App\Models\Account::where('user_id', auth()->id())->get();
        return view('transactions.create', compact('categories', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:INCOME,EXPENSE',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Use the user's default (first) bank account
        $account = \App\Models\Account::where('user_id', auth()->id())->firstOrFail();
        $validated['account_id'] = $account->id;

        DB::transaction(function () use ($validated) {
            $transaction = Transaction::create($validated + ['user_id' => auth()->id()]);
            $account = Account::find($validated['account_id']);
            $account->updateBalance();
        });

        // --- Financial Goal Overrun Alert ---
        $goalExceeded = false;
        $exceededGoals = [];
        if ($validated['type'] === 'EXPENSE') {
            $userId = auth()->id();
            $currentMonth = now()->month;
            $currentYear = now()->year;
            $totalExpenses = \App\Models\Transaction::where('user_id', $userId)
                ->where('type', 'EXPENSE')
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->sum('amount');
            $goals = \App\Models\FinancialGoal::where('user_id', $userId)->get();
            foreach ($goals as $goal) {
                if ($totalExpenses > $goal->target_amount) {
                    $goalExceeded = true;
                    $exceededGoals[] = $goal->name;
                }
            }
        }


        return redirect()->route('transactions.index')
            ->with('success', 'Transaction créée avec succès.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'account_id' => 'required|exists:accounts,id',
        ]);

        $csv = Reader::createFromPath($request->file('file')->getPathname());
        $csv->setHeaderOffset(0);

        DB::transaction(function () use ($csv, $request) {
            foreach ($csv as $record) {
                Transaction::create([
                    'user_id' => auth()->id(),
                    'account_id' => $request->account_id,
                    'type' => $record['type'],
                    'amount' => $record['amount'],
                    'description' => $record['description'],
                    'date' => $record['date'],
                    'category_id' => $record['category_id'],
                ]);
            }

            $account = Account::find($request->account_id);
            $account->updateBalance();
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transactions importées avec succès.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        DB::transaction(function () use ($transaction) {
            $account = $transaction->account;
            $transaction->delete();
            $account->updateBalance();
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction supprimée avec succès.');
    }
}
