@extends('layouts.app')

@section('title', isset($client) ? 'Modifier Client' : 'Nouveau Client')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
            <h2 class="text-2xl font-bold mb-6 text-indigo-700">
                {{ isset($client) ? 'Modifier Client' : 'Nouveau Client' }}
            </h2>

            <form method="POST" action="{{ isset($client) ? route('advisors.clients.update', $client) : route('advisors.clients.store') }}">
                @csrf
                @if(isset($client))
                    @method('PUT')
                @endif

                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de Base</h3>
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom Complet</label>
                                <div class="mt-1">
                                    <input type="text" name="name" id="name" 
                                           value="{{ old('name', $client->name ?? '') }}"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <div class="mt-1">
                                    <input type="email" name="email" id="email" 
                                           value="{{ old('email', $client->email ?? '') }}"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                                <div class="mt-1">
                                    <input type="tel" name="phone" id="phone" 
                                           value="{{ old('phone', $client->phone ?? '') }}"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="birthdate" class="block text-sm font-medium text-gray-700">Date de Naissance</label>
                                <div class="mt-1">
                                    <input type="date" name="birthdate" id="birthdate" 
                                           value="{{ old('birthdate', isset($client) ? $client->birthdate->format('Y-m-d') : '') }}"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                @error('birthdate')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Financial Profile -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Profil Financier</h3>
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                            <div>
                                <label for="income" class="block text-sm font-medium text-gray-700">Revenu Mensuel (€)</label>
                                <div class="mt-1">
                                    <input type="number" step="0.01" name="income" id="income" 
                                           value="{{ old('income', $client->income ?? '') }}"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                @error('income')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="risk_profile" class="block text-sm font-medium text-gray-700">Profil de Risque</label>
                                <div class="mt-1">
                                    <select name="risk_profile" id="risk_profile" 
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Sélectionner...</option>
                                        <option value="conservative" {{ old('risk_profile', $client->risk_profile ?? '') == 'conservative' ? 'selected' : '' }}>Conservateur</option>
                                        <option value="moderate" {{ old('risk_profile', $client->risk_profile ?? '') == 'moderate' ? 'selected' : '' }}>Modéré</option>
                                        <option value="aggressive" {{ old('risk_profile', $client->risk_profile ?? '') == 'aggressive' ? 'selected' : '' }}>Agressif</option>
                                    </select>
                                </div>
                                @error('risk_profile')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations Complémentaires</h3>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <div class="mt-1">
                                <textarea name="notes" id="notes" rows="4"
                                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('notes', $client->notes ?? '') }}</textarea>
                            </div>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Preferences -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Préférences</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="notifications_enabled" id="notifications_enabled"
                                           {{ old('notifications_enabled', $client->notifications_enabled ?? false) ? 'checked' : '' }}
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="notifications_enabled" class="font-medium text-gray-700">Activer les notifications</label>
                                    <p class="text-gray-500">Recevoir des alertes sur les objectifs et le budget</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="monthly_report" id="monthly_report"
                                           {{ old('monthly_report', $client->monthly_report ?? false) ? 'checked' : '' }}
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="monthly_report" class="font-medium text-gray-700">Rapport mensuel</label>
                                    <p class="text-gray-500">Recevoir un rapport mensuel détaillé</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <button type="button" onclick="window.history.back()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Annuler
                    </button>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ isset($client) ? 'Mettre à jour' : 'Créer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 