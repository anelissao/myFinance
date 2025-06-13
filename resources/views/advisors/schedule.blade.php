@extends('layouts.app')

@section('title', 'Gestion du Planning')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-indigo-700">Mon Planning</h2>
                    <p class="mt-1 text-gray-600">Gérez vos disponibilités et rendez-vous</p>
                </div>
                <button onclick="window.location.href='{{ route('advisors.schedule.settings') }}'"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Paramètres
                </button>
            </div>

            <!-- Calendar Navigation -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <button onclick="previousWeek()"
                            class="p-2 rounded-full hover:bg-gray-100">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <h3 class="text-lg font-medium text-gray-900" id="currentWeek">
                        Semaine du {{ $startOfWeek->format('d/m/Y') }} au {{ $endOfWeek->format('d/m/Y') }}
                    </h3>
                    <button onclick="nextWeek()"
                            class="p-2 rounded-full hover:bg-gray-100">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                <div class="flex space-x-2">
                    <button onclick="window.location.href='{{ route('advisors.schedule.export') }}'"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Exporter
                    </button>
                    <button onclick="window.location.href='{{ route('advisors.schedule.sync') }}'"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Synchroniser
                    </button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="grid grid-cols-8 gap-px bg-gray-200">
                    <!-- Time Column -->
                    <div class="bg-gray-50 p-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Heure
                    </div>
                    <!-- Days Columns -->
                    @foreach($weekDays as $day)
                        <div class="bg-gray-50 p-2 text-center">
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $day->format('D') }}
                            </div>
                            <div class="text-sm font-semibold text-gray-900">
                                {{ $day->format('d/m') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-8 gap-px bg-gray-200">
                    @foreach($timeSlots as $time)
                        <!-- Time Column -->
                        <div class="bg-white p-2 text-xs text-gray-500">
                            {{ $time }}
                        </div>
                        <!-- Appointments Slots -->
                        @foreach($weekDays as $day)
                            <div class="bg-white relative min-h-[60px] group">
                                @php
                                    $appointment = $appointments->first(function($apt) use ($day, $time) {
                                        return $apt->date->format('Y-m-d') === $day->format('Y-m-d') 
                                            && $apt->time === $time;
                                    });
                                @endphp

                                @if($appointment)
                                    <div class="absolute inset-1 rounded-lg {{ $appointment->status === 'confirmed' ? 'bg-indigo-50 border-2 border-indigo-200' : 'bg-yellow-50 border-2 border-yellow-200' }} p-2">
                                        <div class="flex flex-col h-full">
                                            <p class="text-sm font-medium {{ $appointment->status === 'confirmed' ? 'text-indigo-700' : 'text-yellow-700' }}">
                                                {{ $appointment->client->name }}
                                            </p>
                                            <p class="text-xs {{ $appointment->status === 'confirmed' ? 'text-indigo-500' : 'text-yellow-500' }}">
                                                {{ $appointment->topic_label }}
                                            </p>
                                            <div class="mt-auto flex justify-end space-x-1">
                                                <button onclick="viewAppointment({{ $appointment->id }})"
                                                        class="p-1 rounded hover:bg-white">
                                                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                                @if($appointment->status === 'pending')
                                                    <button onclick="confirmAppointment({{ $appointment->id }})"
                                                            class="p-1 rounded hover:bg-white">
                                                        <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <button onclick="createAppointment('{{ $day->format('Y-m-d') }}', '{{ $time }}')"
                                            class="absolute inset-1 rounded-lg border-2 border-dashed border-gray-200 p-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="sr-only">Ajouter un rendez-vous</span>
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            <!-- Legend -->
            <div class="mt-6 flex items-center justify-end space-x-6 text-sm">
                <div class="flex items-center">
                    <span class="h-4 w-4 rounded border-2 border-indigo-200 bg-indigo-50 mr-2"></span>
                    <span class="text-gray-600">Confirmé</span>
                </div>
                <div class="flex items-center">
                    <span class="h-4 w-4 rounded border-2 border-yellow-200 bg-yellow-50 mr-2"></span>
                    <span class="text-gray-600">En attente</span>
                </div>
                <div class="flex items-center">
                    <span class="h-4 w-4 rounded border-2 border-dashed border-gray-200 mr-2"></span>
                    <span class="text-gray-600">Disponible</span>
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Prochains Rendez-vous</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sujet</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($upcomingAppointments as $appointment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $appointment->date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $appointment->time }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <img class="h-8 w-8 rounded-full" src="{{ $appointment->client->profile_photo_url }}" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $appointment->client->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $appointment->client->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $appointment->topic_label }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $appointment->meeting_type === 'video' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $appointment->meeting_type === 'video' ? 'Visio' : 'En personne' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $appointment->status === 'confirmed' ? 'Confirmé' : 'En attente' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <button onclick="viewAppointment({{ $appointment->id }})"
                                                class="text-indigo-600 hover:text-indigo-900">
                                            Voir
                                        </button>
                                        @if($appointment->status === 'pending')
                                            <button onclick="confirmAppointment({{ $appointment->id }})"
                                                    class="text-green-600 hover:text-green-900">
                                                Confirmer
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previousWeek() {
    const currentDate = new Date('{{ $startOfWeek->format('Y-m-d') }}');
    currentDate.setDate(currentDate.getDate() - 7);
    window.location.href = `{{ route('advisors.schedule') }}?date=${currentDate.toISOString().split('T')[0]}`;
}

function nextWeek() {
    const currentDate = new Date('{{ $startOfWeek->format('Y-m-d') }}');
    currentDate.setDate(currentDate.getDate() + 7);
    window.location.href = `{{ route('advisors.schedule') }}?date=${currentDate.toISOString().split('T')[0]}`;
}

function viewAppointment(id) {
    window.location.href = `{{ route('advisors.appointments.show', '') }}/${id}`;
}

function confirmAppointment(id) {
    if (confirm('Voulez-vous confirmer ce rendez-vous ?')) {
        fetch(`{{ route('advisors.appointments.confirm', '') }}/${id}`, {
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

function createAppointment(date, time) {
    window.location.href = `{{ route('advisors.appointments.create') }}?date=${date}&time=${time}`;
}
</script>
@endpush

@endsection