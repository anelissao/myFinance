@extends('layouts.app')

@section('title', 'Tableau de bord Conseiller')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Users Expenses & Income Overview -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 mb-8">
            <h2 class="text-xl font-bold mb-4 text-indigo-800">Vue d'ensemble des utilisateurs</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenus</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Dépenses</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($usersWithTotals as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user['name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user['email'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">{{ number_format($user['totalIncome'], 2, ',', ' ') }} €</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">{{ number_format($user['totalExpenses'], 2, ',', ' ') }} €</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-400">Aucun utilisateur trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-indigo-700">Bienvenue, {{ auth()->user()->name }}</h2>
                    <p class="text-gray-600">Conseiller Financier</p>
                </div>
                <div class="space-x-4">
                    <a href="{{ route('advisors.profile') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Mon Profil Public
                    </a>
                    <a href="{{ route('advisors.history') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Historique
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Clients Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Mes Clients</h3>
                <div class="space-y-4">
                    @forelse($clients as $client)
                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-indigo-600">{{ $client->name }}</h4>
                                    <p class="text-sm text-gray-500">Dernière activité: {{ $client->last_activity_at?->diffForHumans() }}</p>
                                </div>
                                <a href="{{ route('advisors.clients.show', $client) }}" class="text-indigo-600 hover:text-indigo-800">
                                    Voir détails →
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucun client pour le moment</p>
                    @endforelse
                </div>
            </div>

            <!-- Analytics Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Analyses & Statistiques</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-indigo-50 rounded-lg p-4">
                            <p class="text-sm text-indigo-600">Total Clients</p>
                            <p class="text-2xl font-bold text-indigo-800">{{ $totalClients }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-sm text-green-600">Objectifs Atteints</p>
                            <p class="text-2xl font-bold text-green-800">{{ $achievedGoals }}</p>
                        </div>
                    </div>
                    
                    <!-- Recent Activities -->
                    <div class="mt-6">
                        <h4 class="text-md font-medium mb-3 text-gray-700">Activités Récentes</h4>
                        <div class="space-y-3">
                            @forelse($recentActivities as $activity)
                                <div class="flex items-center text-sm">
                                    <span class="w-2 h-2 bg-indigo-400 rounded-full mr-2"></span>
                                    <span class="text-gray-600">{{ $activity->description }}</span>
                                    <span class="ml-auto text-gray-400 text-xs">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-2">Aucune activité récente</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Actions Rapides</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('advisors.clients.create') }}" class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                    <svg class="w-6 h-6 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="text-gray-600 hover:text-indigo-600">Ajouter un Client</span>
                </a>
                <a href="{{ route('advisors.reports.create') }}" class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                    <svg class="w-6 h-6 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-gray-600 hover:text-indigo-600">Générer un Rapport</span>
                </a>
                <a href="{{ route('advisors.schedule') }}" class="flex items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                    <svg class="w-6 h-6 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-gray-600 hover:text-indigo-600">Gérer mon Planning</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
