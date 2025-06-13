@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Conseillers Financiers</h2>
            <p class="mt-1 text-sm text-gray-500">Trouvez un conseiller financier qualifié pour vous aider à atteindre vos objectifs.</p>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <form action="{{ route('advisors.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Recherche</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nom ou spécialité..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="speciality" class="block text-sm font-medium text-gray-700">Spécialité</label>
                        <select name="speciality" id="speciality" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Toutes les spécialités</option>
                            <option value="investment" {{ request('speciality') == 'investment' ? 'selected' : '' }}>Investissement</option>
                            <option value="retirement" {{ request('speciality') == 'retirement' ? 'selected' : '' }}>Retraite</option>
                            <option value="tax" {{ request('speciality') == 'tax' ? 'selected' : '' }}>Fiscalité</option>
                            <option value="estate" {{ request('speciality') == 'estate' ? 'selected' : '' }}>Patrimoine</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Rechercher
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Advisors Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($advisors as $advisor)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <img class="h-16 w-16 rounded-full" src="{{ $advisor->profile_photo_url }}" alt="{{ $advisor->name }}">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900">{{ $advisor->name }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ $advisor->title }}</p>
                                
                                <!-- Rating -->
                                <div class="flex items-center mt-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-5 w-5 {{ $i <= $advisor->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-500">({{ $advisor->reviews_count }} avis)</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-900">Spécialités</h4>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($advisor->specialities as $speciality)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $speciality }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4">
                            <p class="text-sm text-gray-500">{{ Str::limit($advisor->description, 150) }}</p>
                        </div>

                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                <span>{{ $advisor->experience }} ans d'expérience</span>
                            </div>
                            <a href="{{ route('advisors.show', $advisor) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                Voir le profil
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12 bg-white rounded-lg shadow-sm">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun conseiller trouvé</h3>
                        <p class="mt-1 text-sm text-gray-500">Essayez de modifier vos critères de recherche.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($advisors->hasPages())
            <div class="mt-6">
                {{ $advisors->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 