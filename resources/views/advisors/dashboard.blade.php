@extends('layouts.app')

@section('title', 'Tableau de bord Conseiller')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
            <h2 class="text-2xl font-bold mb-4 text-indigo-700">Bienvenue, Conseiller Financier</h2>
            <p class="mb-6 text-gray-600">Vous êtes connecté en tant que conseiller financier. Utilisez les liens ci-dessous pour accéder à vos fonctionnalités.</p>
            <div class="space-y-4">
                <a href="{{ route('advisors.profile') }}" class="block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Voir mon profil public</a>
                <a href="{{ route('advisors.history') }}" class="block px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Historique des connexions</a>
            </div>
        </div>
    </div>
</div>
@endsection
