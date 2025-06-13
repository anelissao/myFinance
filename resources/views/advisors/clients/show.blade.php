@extends('layouts.app')

@section('title', 'Détails Client')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Client Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-indigo-700">{{ $client->name }}</h2>
                    <p class="text-gray-600">Client depuis {{ $client->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="space-x-4">
                    <button onclick="window.location.href='{{ route('advisors.reports.generate', $client) }}'" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Générer Rapport
                    </button>
                    <button onclick="window.location.href='{{ route('advisors.clients.edit', $client) }}'" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Modifier
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Financial Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Vue d'ensemble</h3>
                <div class="space-y-4">
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-sm text-green-600">Solde Total</p>
                        <p class="text-2xl font-bold text-green-800">{{ number_format($totalBalance, 2, ',', ' ') }} €</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-blue-600">Revenus Mensuels</p>
                        <p class="text-2xl font-bold text-blue-800">{{ number_format($monthlyIncome, 2, ',', ' ') }} €</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4">
                        <p class="text-sm text-red-600">Dépenses Mensuelles</p>
                        <p class="text-2xl font-bold text-red-800">{{ number_format($monthlyExpenses, 2, ',', ' ') }} €</p>
                    </div>
                </div>
            </div>

            <!-- Goals Progress -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Objectifs Financiers</h3>
                <div class="space-y-4">
                    @forelse($goals as $goal)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-medium text-gray-800">{{ $goal->name }}</h4>
                                <span class="text-sm {{ $goal->progress >= 100 ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ number_format($goal->progress, 0) }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ min($goal->progress, 100) }}%"></div>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">
                                {{ number_format($goal->current_amount, 2, ',', ' ') }} € / {{ number_format($goal->target_amount, 2, ',', ' ') }} €
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucun objectif défini</p>
                    @endforelse
                    <a href="{{ route('advisors.goals.create', $client) }}" class="block text-center text-indigo-600 hover:text-indigo-800">
                        + Ajouter un objectif
                    </a>
                </div>
            </div>

            <!-- Budget Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Budget Mensuel</h3>
                <div class="space-y-4">
                    @foreach($budgets as $budget)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-medium text-gray-800">{{ $budget->category->name }}</h4>
                                <span class="text-sm {{ $budget->percentage > 100 ? 'text-red-600' : 'text-gray-500' }}">
                                    {{ number_format($budget->percentage, 0) }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full {{ $budget->percentage > 100 ? 'bg-red-600' : 'bg-indigo-600' }}" 
                                     style="width: {{ min($budget->percentage, 100) }}%"></div>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">
                                {{ number_format($budget->actual_amount, 2, ',', ' ') }} € / {{ number_format($budget->planned_amount, 2, ',', ' ') }} €
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Transactions Récentes</h3>
                <a href="{{ route('advisors.transactions.index', $client) }}" class="text-indigo-600 hover:text-indigo-800">
                    Voir tout →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentTransactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->category->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($transaction->amount, 2, ',', ' ') }} €
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recommendations -->
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Recommandations</h3>
            <div class="space-y-4">
                @forelse($recommendations as $recommendation)
                    <div class="flex items-start space-x-3 p-4 {{ $loop->first ? 'bg-indigo-50 rounded-lg' : '' }}">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 {{ $loop->first ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">{{ $recommendation->title }}</h4>
                            <p class="text-sm text-gray-600">{{ $recommendation->description }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Aucune recommandation pour le moment</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 