<?php

namespace App\Http\Controllers;

use App\Models\Advisor;
use Illuminate\Http\Request;

class AdvisorController extends Controller
{
    public function index(Request $request)
    {
        $query = Advisor::public();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('specialization', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        if ($speciality = $request->input('speciality')) {
            $query->where('specialization', $speciality);
        }

        $advisors = $query->orderByDesc('rating')->get();

        return view('advisors.index', compact('advisors'));
    }

    public function dashboard()
    {
        // Get all users with their transactions
        $users = \App\Models\User::with('transactions')->get();
        $usersWithTotals = $users->map(function ($user) {
            $totalIncome = $user->transactions->where('amount', '>', 0)->sum('amount');
            $totalExpenses = $user->transactions->where('amount', '<', 0)->sum('amount');
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'totalIncome' => $totalIncome,
                'totalExpenses' => $totalExpenses,
            ];
        });
        return view('advisors.dashboard', compact('usersWithTotals'));
    }

    public function profile()
    {
        $advisor = \App\Models\Advisor::where('user_id', auth()->id())->first();
        return view('advisors.profile', compact('advisor'));
    }

    public function history()
    {
        // Dummy data for now, replace with real connection logs if available
        $connections = [
            (object)['loginTime' => now()->subDays(1)->format('Y-m-d H:i'), 'ipAddress' => '192.168.1.10'],
            (object)['loginTime' => now()->subDays(2)->format('Y-m-d H:i'), 'ipAddress' => '192.168.1.11'],
        ];
        return view('advisors.history', compact('connections'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Advisor::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'description' => 'required|string',
            'rating' => 'required|numeric|min:0|max:5',
            'is_public' => 'boolean',
        ]);

        Advisor::create($validated);

        return redirect()->route('advisors.index')
            ->with('success', 'Conseiller financier ajouté avec succès.');
    }

    public function update(Request $request, Advisor $advisor)
    {
        $this->authorize('update', $advisor);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'description' => 'required|string',
            'rating' => 'required|numeric|min:0|max:5',
            'is_public' => 'boolean',
        ]);

        $advisor->update($validated);

        return redirect()->route('advisors.index')
            ->with('success', 'Informations du conseiller mises à jour avec succès.');
    }

    public function destroy(Advisor $advisor)
    {
        $this->authorize('delete', $advisor);
        $advisor->delete();

        return redirect()->route('advisors.index')
            ->with('success', 'Conseiller supprimé avec succès.');
    }

} 