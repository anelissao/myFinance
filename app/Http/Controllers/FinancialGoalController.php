<?php

namespace App\Http\Controllers;

use App\Models\FinancialGoal;
use Illuminate\Http\Request;

class FinancialGoalController extends Controller
{
    public function index()
    {
        $goals = FinancialGoal::where('user_id', auth()->id())
            ->paginate(20)
            ->through(function ($goal) {
                $goal->progress = $goal->getProgressPercentage();
                $goal->remaining = $goal->getRemainingAmount();
                return $goal;
            });

        return view('goals.index', compact('goals'));
    }

    public function create()
    {
        return view('goals.create');
    }

    public function edit(FinancialGoal $goal)
    {
        return view('goals.edit', compact('goal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'current_amount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date|after:today',
        ]);

        // Default current_amount to 0 if not provided
        $validated['current_amount'] = $validated['current_amount'] ?? 0;

        $goal = FinancialGoal::create($validated + [
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('goals.index')
            ->with('success', 'Objectif financier créé avec succès.');
    }

    public function update(Request $request, FinancialGoal $goal)
    {
        $this->authorize('update', $goal);

        $validated = $request->validate([
            'current_amount' => 'required|numeric|min:0',
        ]);

        $goal->update($validated);

        if ($goal->isCompleted()) {
            return redirect()->route('goals.index')
                ->with('success', 'Félicitations ! Objectif atteint !');
        }

        return redirect()->route('goals.index')
            ->with('success', 'Progression mise à jour avec succès.');
    }

    public function simulateSavings(FinancialGoal $goal)
    {
        $this->authorize('view', $goal);

        $monthsLeft = now()->diffInMonths($goal->due_date);
        $remainingAmount = $goal->getRemainingAmount();
        
        if ($monthsLeft > 0) {
            $monthlySavingsNeeded = $remainingAmount / $monthsLeft;
        } else {
            $monthlySavingsNeeded = $remainingAmount;
        }

        return view('goals.simulate', compact('goal', 'monthlySavingsNeeded', 'monthsLeft'));
    }

    public function destroy(FinancialGoal $goal)
    {
        $goal->delete();

        return redirect()->route('goals.index')
            ->with('success', 'Objectif supprimé avec succès.');
    }
} 