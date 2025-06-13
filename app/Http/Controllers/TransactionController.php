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


class TransactionController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index() {
        $transactions = Transaction::where('user_id', Auth::id())->with('category', 'account')->latest()->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create() {
        // Get all categories from the database
        $categories = Category::all();
        
        // Get accounts for the authenticated user
        $accounts = Account::where('user_id', Auth::id())
            ->with('transactions') // Load transactions relation
            ->get();

        return view('transactions.create', compact('categories', 'accounts'));
    }

    public function store(Request $request)
{
    $request->validate([
        'account_id' => 'required|exists:accounts,id',
        'category_id' => 'required|exists:categories,id',
        'amount' => 'required|numeric',
        'date' => 'required|date',
    ]);

    $transaction = Transaction::create([
        'user_id' => Auth::id(),
        'account_id' => $request->account_id,
        'category_id' => $request->category_id,
        'amount' => $request->amount,
        'description' => $request->description,
        'date' => $request->date,
    ]);

    // 🔔 Budget Alert Logic
    $month = date('Y-m', strtotime($request->date)); // e.g., "2025-06"

    $total = Transaction::where('user_id', Auth::id())
        ->where('category_id', $request->category_id)
        ->where('date', 'like', "$month%")
        ->sum('amount');

    $budget = Budget::where('user_id', Auth::id())
        ->where('category_id', $request->category_id)
        ->where('month', $month)
        ->first();

    if ($budget && $total > $budget->amount) {
        Mail::to(Auth::user()->email)->send(new BudgetExceeded($budget, $total));
        return redirect('/transactions')->with('success', 'Transaction added. ⚠️ Budget exceeded!');
    }

    return redirect('/transactions')->with('success', 'Transaction added.');
}


    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = fopen($request->file('csv_file'), 'r');
        $header = fgetcsv($file); // skip header

        while (($row = fgetcsv($file)) !== false) {
            Transaction::create([
                'user_id' => Auth::id(),
                'amount' => $row[0],
                'description' => $row[1],
                'category_id' => isset($row[2]) ? $row[2] : null,
                'date' => isset($row[3]) ? $row[3] : now(),
            ]);
        }

        fclose($file);

        return redirect()->back()->with('success', 'Transactions imported!');
    }
}
