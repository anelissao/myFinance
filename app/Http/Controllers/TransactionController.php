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

class TransactionController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index() {
        $transactions = Transaction::where('user_id', Auth::id())->with('category', 'account')->latest()->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create() {
        $categories = Category::all();
        $accounts = Account::where('user_id', Auth::id())->get();
        return view('transactions.create', compact('categories', 'accounts'));
    }

    public function store(Request $request) {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        Transaction::create([
            'user_id' => Auth::id(),
            'account_id' => $request->account_id,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect('/transactions')->with('success', 'Transaction added.');
    }
}
