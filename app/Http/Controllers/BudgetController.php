<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BudgetAlert;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::where('user_id', auth()->id())
            ->whereMonth('month', now()->month)
            ->with('category')
            ->get()
            ->map(function ($budget) {
                $budget->progress = $budget->getProgressPercentage();
                return $budget;
            });

        $categories = Category::all();
        
        return view('budgets.index', compact('budgets', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'planned_amount' => 'required|numeric|min:0',
            'month' => 'required|date',
        ]);

        $budget = Budget::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'category_id' => $validated['category_id'],
                'month' => $validated['month'],
            ],
            [
                'planned_amount' => $validated['planned_amount'],
            ]
        );

        return redirect()->route('budgets.index')
            ->with('success', 'Budget défini avec succès.');
    }

    public function checkAlerts()
    {
        $budgets = Budget::where('user_id', auth()->id())
            ->whereMonth('month', now()->month)
            ->with(['category', 'user'])
            ->get();

        foreach ($budgets as $budget) {
            if ($budget->isOverBudget()) {
                Mail::to($budget->user->email)
                    ->send(new BudgetAlert($budget));
            }
        }
    }

    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);
        $budget->delete();

        return redirect()->route('budgets.index')
            ->with('success', 'Budget supprimé avec succès.');
    }
} 