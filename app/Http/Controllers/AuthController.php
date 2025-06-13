<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        Auth::login($user);
        return redirect('/dashboard');
    }

    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function dashboard() {
        $user = auth()->user();
        
        // Get recent transactions
        $recentTransactions = $user->transactions()
            ->with(['category', 'account'])
            ->latest()
            ->take(5)
            ->get();

        // Calculate monthly totals
        $thisMonth = now()->startOfMonth();
        $monthlyTransactions = $user->transactions()
            ->whereMonth('date', $thisMonth->month)
            ->whereYear('date', $thisMonth->year)
            ->get();

        $monthlyIncome = $monthlyTransactions->where('amount', '>', 0)->sum('amount');
        $monthlyExpenses = abs($monthlyTransactions->where('amount', '<', 0)->sum('amount'));

        // Calculate total balance
        $totalBalance = $user->accounts()->sum('balance');

        // Calculate budget usage
        $totalBudget = $user->budgets()
            ->whereMonth('month', $thisMonth->format('Y-m'))
            ->sum('amount');
        
        $budgetUsagePercentage = $totalBudget > 0 
            ? round(($monthlyExpenses / $totalBudget) * 100) 
            : 0;

        return view('dashboard', compact(
            'recentTransactions',
            'monthlyIncome',
            'monthlyExpenses',
            'totalBalance',
            'budgetUsagePercentage'
        ));
    }

    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
}
