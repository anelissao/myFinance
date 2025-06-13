@extends('layouts.app')

@section('title', 'Nos Conseillers Financiers')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Nos Experts Financiers
            </h2>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Une équipe de professionnels qualifiés pour vous accompagner dans la gestion de vos finances personnelles
            </p>
        </div>

        <div class="mt-12 space-y-4 sm:mt-16 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-6 lg:max-w-4xl lg:mx-auto xl:max-w-none xl:grid-cols-3">
            @foreach($advisors as $advisor)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm divide-y divide-gray-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">{{ $advisor->name }}</h3>
                                <p class="mt-1 text-sm text-indigo-600">{{ $advisor->specialization }}</p>
                            </div>
                            <img class="h-20 w-20 rounded-full" src="{{ $advisor->profile_photo_url }}" alt="{{ $advisor->name }}">
                        </div>

                        <!-- Rating -->
                        <div class="mt-4 flex items-center">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-5 w-5 {{ $i <= $advisor->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="ml-2 text-sm text-gray-500">{{ number_format($advisor->rating, 1) }}/5</span>
                            </div>
                            <span class="mx-2 text-gray-500">•</span>
                            <span class="text-sm text-gray-500">{{ $advisor->reviews_count }} avis</span>
                        </div>

                        <!-- Description -->
                        <p class="mt-4 text-sm text-gray-500">
                            {{ Str::limit($advisor->description, 150) }}
                        </p>

                        <!-- Expertise Areas -->
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-900">Domaines d'expertise</h4>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($advisor->expertise_areas as $area)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $area }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Stats -->
                        <dl class="mt-6 grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <dt class="text-sm font-medium text-gray-500">Clients Actifs</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $advisor->active_clients_count }}</dd>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <dt class="text-sm font-medium text-gray-500">Années d'exp.</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $advisor->years_of_experience }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Action Buttons -->
                    <div class="px-6 py-4">
                        @auth
                            @if(auth()->user()->role === 'UTILISATEUR')
                                <button onclick="window.location.href='{{ route('advisors.request', $advisor) }}'"
                                        class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                    Demander un Rendez-vous
                                </button>
                            @else
                                <p class="text-center text-sm text-gray-500">Connectez-vous en tant qu'utilisateur pour prendre rendez-vous</p>
                            @endif
                        @else
                            <button onclick="window.location.href='{{ route('login') }}'"
                                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Se connecter pour prendre rendez-vous
                            </button>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($advisors->hasPages())
            <div class="mt-8">
                {{ $advisors->links() }}
            </div>
        @endif

        <!-- Contact Section -->
        <div class="mt-16 bg-indigo-50 rounded-2xl px-6 py-10 sm:px-12">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-indigo-900">Vous ne trouvez pas le bon conseiller ?</h3>
                <p class="mt-4 text-lg text-indigo-700">
                    Notre équipe peut vous aider à trouver le conseiller financier idéal pour vos besoins spécifiques.
                </p>
                <button onclick="window.location.href='{{ route('contact') }}'"
                        class="mt-8 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    Nous Contacter
                </button>
            </div>
        </div>
    </div>
</div>
@endsection 