@extends('layouts.app')

@section('title', 'Générer un Rapport')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
            <h2 class="text-2xl font-bold mb-6 text-indigo-700">Générer un Rapport Financier</h2>

            <form method="POST" action="{{ route('advisors.reports.generate') }}">
                @csrf

                <div class="space-y-6">
                    <!-- Client Selection -->
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                        <div class="mt-1">
                            <select name="client_id" id="client_id" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Sélectionner un client...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('client_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Report Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type de Rapport</label>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-4 shadow-sm hover:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2">
                                <label class="flex cursor-pointer">
                                    <input type="radio" name="report_type" value="monthly" class="sr-only" checked>
                                    <div class="flex-1">
                                        <span class="block text-sm font-medium text-gray-900">Rapport Mensuel</span>
                                        <span class="block text-sm text-gray-500">Analyse détaillée du mois</span>
                                    </div>
                                    <div class="pointer-events-none absolute -inset-px rounded-lg border-2" aria-hidden="true"></div>
                                </label>
                            </div>

                            <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-4 shadow-sm hover:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2">
                                <label class="flex cursor-pointer">
                                    <input type="radio" name="report_type" value="annual" class="sr-only">
                                    <div class="flex-1">
                                        <span class="block text-sm font-medium text-gray-900">Rapport Annuel</span>
                                        <span class="block text-sm text-gray-500">Bilan annuel complet</span>
                                    </div>
                                    <div class="pointer-events-none absolute -inset-px rounded-lg border-2" aria-hidden="true"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Date de Début</label>
                            <div class="mt-1">
                                <input type="date" name="start_date" id="start_date" required
                                       value="{{ old('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Date de Fin</label>
                            <div class="mt-1">
                                <input type="date" name="end_date" id="end_date" required
                                       value="{{ old('end_date', now()->endOfMonth()->format('Y-m-d')) }}"
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Report Sections -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Sections du Rapport</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="sections[]" value="overview" id="section_overview"
                                           checked
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="section_overview" class="font-medium text-gray-700">Vue d'ensemble</label>
                                    <p class="text-gray-500">Résumé des finances et indicateurs clés</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="sections[]" value="transactions" id="section_transactions"
                                           checked
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="section_transactions" class="font-medium text-gray-700">Transactions</label>
                                    <p class="text-gray-500">Détail des transactions par catégorie</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="sections[]" value="budgets" id="section_budgets"
                                           checked
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="section_budgets" class="font-medium text-gray-700">Budgets</label>
                                    <p class="text-gray-500">Analyse des budgets et écarts</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="sections[]" value="goals" id="section_goals"
                                           checked
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="section_goals" class="font-medium text-gray-700">Objectifs</label>
                                    <p class="text-gray-500">Progression des objectifs financiers</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="sections[]" value="recommendations" id="section_recommendations"
                                           checked
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="section_recommendations" class="font-medium text-gray-700">Recommandations</label>
                                    <p class="text-gray-500">Suggestions d'amélioration personnalisées</p>
                                </div>
                            </div>
                        </div>
                        @error('sections')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Format -->
                    <div>
                        <label for="format" class="block text-sm font-medium text-gray-700">Format du Rapport</label>
                        <div class="mt-1">
                            <select name="format" id="format" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                <option value="excel" {{ old('format') == 'excel' ? 'selected' : '' }}>Excel</option>
                            </select>
                        </div>
                        @error('format')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <button type="button" onclick="window.history.back()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Annuler
                    </button>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Générer le Rapport
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportTypeInputs = document.querySelectorAll('input[name="report_type"]');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    function updateDateRange() {
        const selectedType = document.querySelector('input[name="report_type"]:checked').value;
        const today = new Date();

        if (selectedType === 'monthly') {
            startDateInput.value = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
            endDateInput.value = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().split('T')[0];
        } else if (selectedType === 'annual') {
            startDateInput.value = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
            endDateInput.value = new Date(today.getFullYear(), 11, 31).toISOString().split('T')[0];
        }
    }

    reportTypeInputs.forEach(input => {
        input.addEventListener('change', updateDateRange);
    });
});
</script>
@endpush

@endsection 