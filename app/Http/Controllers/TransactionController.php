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

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $categories = auth()->user()->categories;
        $accounts = auth()->user()->accounts;
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
            'account_id' => 'required|exists:accounts,id',
        ]);

        DB::transaction(function () use ($validated) {
            $transaction = Transaction::create($validated + ['user_id' => auth()->id()]);
            $account = Account::find($validated['account_id']);
            $account->updateBalance();
        });

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
