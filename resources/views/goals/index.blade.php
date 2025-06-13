@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Objectifs Financiers</h2>
            <a href="{{ route('goals.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvel objectif
            </a>
        </div>

        <!-- Goals Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($goals as $goal)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $goal->name }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $goal->is_completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $goal->is_completed ? 'Complété' : 'En cours' }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-500">{{ $goal->description }}</p>
                        </div>

                        <div class="mb-4">
                            <div class="flex justify-between text-sm font-medium text-gray-900 mb-1">
                                <span>Progression</span>
                                <span>{{ number_format($goal->current_amount, 2) }} € / {{ number_format($goal->target_amount, 2) }} €</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ min(100, ($goal->current_amount / $goal->target_amount) * 100) }}%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                            <div>
                                <span class="block text-gray-500">Date limite</span>
                                <span class="block font-medium text-gray-900">{{ $goal->due_date->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500">Épargne mensuelle</span>
                                <span class="block font-medium text-gray-900">{{ number_format($goal->monthly_saving, 2) }} €</span>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('goals.edit', $goal) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                            <form action="{{ route('goals.destroy', $goal) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet objectif ?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12 bg-white rounded-lg shadow-sm">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun objectif</h3>
                        <p class="mt-1 text-sm text-gray-500">Commencez par créer un objectif financier pour suivre vos progrès.</p>
                        <div class="mt-6">
                            <a href="{{ route('goals.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Créer un objectif
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        @if($goals->hasPages())
            <div class="mt-6">
                {{ $goals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 