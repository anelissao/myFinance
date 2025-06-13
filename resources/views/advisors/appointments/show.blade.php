@extends('layouts.app')

@section('title', 'Détails du Rendez-vous')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <!-- Header -->
            <div class="border-b border-gray-200 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-indigo-700">Rendez-vous #{{ $appointment->id }}</h2>
                        <p class="mt-1 text-gray-600">
                            {{ $appointment->date->format('d/m/Y') }} à {{ $appointment->time }}
                        </p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                               {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $appointment->status === 'confirmed' ? 'Confirmé' : 'En attente' }}
                    </span>
                </div>
            </div>

            <div class="px-8 py-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Client Information -->
                    <div class="md:col-span-2">
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations Client</h3>
                            <div class="flex items-center mb-6">
                                <div class="flex-shrink-0">
                                    <img class="h-16 w-16 rounded-full" src="{{ $appointment->client->profile_photo_url }}" alt="">
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $appointment->client->name }}</h4>
                                    <div class="mt-1 text-sm text-gray-500">
                                        <p>{{ $appointment->client->email }}</p>
                                        <p>{{ $appointment->client->phone }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Client depuis</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->client->created_at->format('d/m/Y') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Rendez-vous précédents</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $previousAppointmentsCount }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Objectifs financiers</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $activeGoalsCount }} actifs</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Budget mensuel</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($monthlyBudget, 2) }} €</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Details -->
                    <div>
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Détails du Rendez-vous</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Type de rendez-vous</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $appointment->meeting_type === 'video' ? 'Visioconférence' : 'En personne' }}
                                    </dd>
                                </div>

                                @if($appointment->meeting_type === 'video')
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Lien Zoom</dt>
                                        <dd class="mt-1">
                                            <a href="{{ $appointment->zoom_link }}" target="_blank"
                                               class="text-sm text-indigo-600 hover:text-indigo-900">
                                                Rejoindre la réunion
                                                <svg class="inline-block ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                        </dd>
                                    </div>
                                @else
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Lieu</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            Bureau principal<br>
                                            123 rue de la Finance<br>
                                            75008 Paris
                                        </dd>
                                    </div>
                                @endif

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sujet</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $appointment->topic_label }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Durée</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $appointment->duration }} minutes</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Détails supplémentaires</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $appointment->details ?: 'Aucun détail fourni' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 flex flex-col space-y-3">
                            @if($appointment->status === 'pending')
                                <button onclick="confirmAppointment()"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Confirmer le Rendez-vous
                                </button>
                            @endif

                            <button onclick="window.location.href='{{ route('advisors.appointments.edit', $appointment) }}'"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Modifier
                            </button>

                            <button onclick="cancelAppointment()"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                <svg class="h-5 w-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="mt-8">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                        
                        <!-- Add Note Form -->
                        <form method="POST" action="{{ route('advisors.appointments.notes.store', $appointment) }}" class="mb-6">
                            @csrf
                            <div>
                                <label for="note" class="sr-only">Ajouter une note</label>
                                <textarea name="note" id="note" rows="3" required
                                          class="shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"
                                          placeholder="Ajouter une note..."></textarea>
                            </div>
                            <div class="mt-3 flex justify-end">
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Ajouter
                                </button>
                            </div>
                        </form>

                        <!-- Notes List -->
                        <div class="space-y-4">
                            @forelse($appointment->notes as $note)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center">
                                            <img class="h-8 w-8 rounded-full" src="{{ $note->user->profile_photo_url }}" alt="">
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $note->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $note->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                        @if($note->user_id === auth()->id())
                                            <button onclick="deleteNote({{ $note->id }})"
                                                    class="text-gray-400 hover:text-gray-500">
                                                <span class="sr-only">Supprimer</span>
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="mt-2 text-sm text-gray-700">
                                        {{ $note->content }}
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">Aucune note pour le moment</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmAppointment() {
    if (confirm('Voulez-vous confirmer ce rendez-vous ?')) {
        fetch('{{ route('advisors.appointments.confirm', $appointment) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            }
        });
    }
}

function cancelAppointment() {
    if (confirm('Voulez-vous vraiment annuler ce rendez-vous ?')) {
        fetch('{{ route('advisors.appointments.cancel', $appointment) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        }).then(response => {
            if (response.ok) {
                window.location.href = '{{ route('advisors.schedule') }}';
            }
        });
    }
}

function deleteNote(noteId) {
    if (confirm('Voulez-vous vraiment supprimer cette note ?')) {
        fetch(`{{ route('advisors.appointments.notes.destroy', ['appointment' => $appointment->id, 'note' => '']) }}/${noteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            }
        });
    }
}
</script>
@endpush

@endsection