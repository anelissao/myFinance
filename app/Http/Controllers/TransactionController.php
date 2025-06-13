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
