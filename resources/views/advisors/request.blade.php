@extends('layouts.app')

@section('title', 'Demande de Rendez-vous')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-indigo-700">Demande de Rendez-vous</h2>
                <p class="mt-2 text-gray-600">avec {{ $advisor->name }}, {{ $advisor->specialization }}</p>
            </div>

            <form method="POST" action="{{ route('advisors.request.store', $advisor) }}" class="space-y-6">
                @csrf

                <!-- Meeting Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de Rendez-vous</label>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-4 shadow-sm hover:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2">
                            <label class="flex cursor-pointer">
                                <input type="radio" name="meeting_type" value="video" class="sr-only" checked>
                                <div class="flex-1">
                                    <span class="block text-sm font-medium text-gray-900">Visioconférence</span>
                                    <span class="block text-sm text-gray-500">Rendez-vous en ligne via Zoom</span>
                                </div>
                                <div class="pointer-events-none absolute -inset-px rounded-lg border-2" aria-hidden="true"></div>
                            </label>
                        </div>

                        <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-4 shadow-sm hover:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2">
                            <label class="flex cursor-pointer">
                                <input type="radio" name="meeting_type" value="in_person" class="sr-only">
                                <div class="flex-1">
                                    <span class="block text-sm font-medium text-gray-900">En Personne</span>
                                    <span class="block text-sm text-gray-500">Rendez-vous au bureau</span>
                                </div>
                                <div class="pointer-events-none absolute -inset-px rounded-lg border-2" aria-hidden="true"></div>
                            </label>
                        </div>
                    </div>
                    @error('meeting_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Date Souhaitée</label>
                        <div class="mt-1">
                            <input type="date" name="date" id="date" required
                                   min="{{ now()->addDay()->format('Y-m-d') }}"
                                   max="{{ now()->addMonths(2)->format('Y-m-d') }}"
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="time" class="block text-sm font-medium text-gray-700">Heure Préférée</label>
                        <div class="mt-1">
                            <select name="time" id="time" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Sélectionner une heure...</option>
                                @foreach($availableTimeSlots as $slot)
                                    <option value="{{ $slot }}">{{ $slot }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Consultation Topic -->
                <div>
                    <label for="topic" class="block text-sm font-medium text-gray-700">Sujet de la Consultation</label>
                    <div class="mt-1">
                        <select name="topic" id="topic" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Sélectionner un sujet...</option>
                            <option value="investment">Stratégie d'Investissement</option>
                            <option value="retirement">Planification de la Retraite</option>
                            <option value="tax">Optimisation Fiscale</option>
                            <option value="budget">Gestion Budgétaire</option>
                            <option value="debt">Gestion de la Dette</option>
                            <option value="estate">Planification Successorale</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                    @error('topic')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Details -->
                <div>
                    <label for="details" class="block text-sm font-medium text-gray-700">
                        Détails Supplémentaires
                        <span class="text-gray-500">(optionnel)</span>
                    </label>
                    <div class="mt-1">
                        <textarea name="details" id="details" rows="4"
                                  placeholder="Décrivez brièvement votre situation et vos objectifs..."
                                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                    @error('details')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Preferences -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Préférences de Contact</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="reminder_email" id="reminder_email"
                                       checked
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="reminder_email" class="font-medium text-gray-700">Rappel par email</label>
                                <p class="text-gray-500">Recevoir un rappel 24h avant le rendez-vous</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="reminder_sms" id="reminder_sms"
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="reminder_sms" class="font-medium text-gray-700">Rappel par SMS</label>
                                <p class="text-gray-500">Recevoir un rappel par SMS 1h avant le rendez-vous</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="rounded-md bg-gray-50 p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">
                                En soumettant cette demande, vous acceptez nos 
                                <a href="{{ route('terms') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                    conditions d'utilisation
                                </a> 
                                et notre 
                                <a href="{{ route('privacy') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                    politique de confidentialité
                                </a>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="window.history.back()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Annuler
                    </button>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Demander le Rendez-vous
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');
    const meetingTypeInputs = document.querySelectorAll('input[name="meeting_type"]');

    // Update available time slots when date or meeting type changes
    function updateTimeSlots() {
        const selectedDate = dateInput.value;
        const selectedType = document.querySelector('input[name="meeting_type"]:checked').value;

        if (selectedDate) {
            fetch(`/api/advisors/{{ $advisor->id }}/available-slots?date=${selectedDate}&type=${selectedType}`)
                .then(response => response.json())
                .then(data => {
                    timeSelect.innerHTML = '<option value="">Sélectionner une heure...</option>';
                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot;
                        option.textContent = slot;
                        timeSelect.appendChild(option);
                    });
                });
        }
    }

    dateInput.addEventListener('change', updateTimeSlots);
    meetingTypeInputs.forEach(input => {
        input.addEventListener('change', updateTimeSlots);
    });
});
</script>
@endpush

@endsection 