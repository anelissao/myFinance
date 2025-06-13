@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Flux de trésorerie -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
            <div class="p-6">
                <h2 class="text-2xl font-semibold mb-4">Flux de Trésorerie</h2>
                <canvas id="cashFlowChart" height="200"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Top Dépenses -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-4">Top Dépenses par Catégorie</h2>
                    <canvas id="topExpensesChart"></canvas>
                </div>
            </div>

            <!-- Évolution de l'épargne -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-4">Évolution de l'Épargne</h2>
                    <canvas id="savingsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Statistiques -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-4">Statistiques Mensuelles</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Total des Revenus</h3>
                            <p class="text-3xl font-bold text-green-600">{{ number_format($monthlyStats['income'], 2, ',', ' ') }} €</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Total des Dépenses</h3>
                            <p class="text-3xl font-bold text-red-600">{{ number_format($monthlyStats['expenses'], 2, ',', ' ') }} €</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Épargne du Mois</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ number_format($monthlyStats['savings'], 2, ',', ' ') }} €</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suggestions -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-4">Suggestions Personnalisées</h2>
                    <div class="space-y-4">
                        @foreach($suggestions as $suggestion)
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">{{ $suggestion }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration des couleurs
    const colors = {
        blue: 'rgb(59, 130, 246)',
        green: 'rgb(16, 185, 129)',
        red: 'rgb(239, 68, 68)',
        yellow: 'rgb(245, 158, 11)',
    };

    // Flux de trésorerie
    new Chart(document.getElementById('cashFlowChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($cashFlow['labels']) !!},
            datasets: [{
                label: 'Revenus',
                data: {!! json_encode($cashFlow['income']) !!},
                borderColor: colors.green,
                backgroundColor: colors.green + '20',
                fill: true
            }, {
                label: 'Dépenses',
                data: {!! json_encode($cashFlow['expenses']) !!},
                borderColor: colors.red,
                backgroundColor: colors.red + '20',
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' €';
                        }
                    }
                }
            }
        }
    });

    // Top dépenses
    new Chart(document.getElementById('topExpensesChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($topExpenses['labels']) !!},
            datasets: [{
                data: {!! json_encode($topExpenses['data']) !!},
                backgroundColor: [
                    colors.blue,
                    colors.green,
                    colors.red,
                    colors.yellow,
                    'rgb(139, 92, 246)',
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
            }
        }
    });

    // Évolution de l'épargne
    new Chart(document.getElementById('savingsChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($savings['labels']) !!},
            datasets: [{
                label: 'Épargne',
                data: {!! json_encode($savings['data']) !!},
                borderColor: colors.blue,
                backgroundColor: colors.blue + '20',
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' €';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
