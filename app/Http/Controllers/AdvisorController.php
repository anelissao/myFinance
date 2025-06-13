<?php

namespace App\Http\Controllers;

use App\Models\Advisor;
use Illuminate\Http\Request;

class AdvisorController extends Controller
{
    public function index()
    {
        $advisors = Advisor::public()
            ->orderByDesc('rating')
            ->get();

        return view('advisors.index', compact('advisors'));
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