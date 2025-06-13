@extends('layouts.app')

@section('title', 'Paramètres du Planning')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-indigo-700">Paramètres du Planning</h2>
                <p class="mt-2 text-gray-600">Configurez vos disponibilités et préférences de rendez-vous</p>
            </div>

            <form method="POST" action="{{ route('advisors.schedule.settings.update') }}" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Working Hours -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Heures de Travail</h3>
                    <div class="space-y-4">
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday'] as $day)
                            <div class="flex items-center space-x-4">
                                <div class="w-32">
                                    <label class="block text-sm font-medium text-gray-700 capitalize">
                                        {{ __("days.$day") }}
                                    </label>
                                </div>
                                <div class="flex-1 grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="{{ $day }}_start" class="sr-only">Début</label>
                                        <select name="working_hours[{{ $day }}][start]" id="{{ $day }}_start"
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            @foreach($timeSlots as $time)
                                                <option value="{{ $time }}" 
                                                        {{ old("working_hours.$day.start", $settings->working_hours[$day]['start'] ?? '') == $time ? 'selected' : '' }}>
                                                    {{ $time }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="{{ $day }}_end" class="sr-only">Fin</label>
                                        <select name="working_hours[{{ $day }}][end]" id="{{ $day }}_end"
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            @foreach($timeSlots as $time)
                                                <option value="{{ $time }}"
                                                        {{ old("working_hours.$day.end", $settings->working_hours[$day]['end'] ?? '') == $time ? 'selected' : '' }}>
                                                    {{ $time }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="working_days[]" value="{{ $day }}"
                                           id="working_day_{{ $day }}"
                                           {{ in_array($day, old('working_days', $settings->working_days ?? [])) ? 'checked' : '' }}
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    <label for="working_day_{{ $day }}" class="sr-only">Jour travaillé</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Appointment Settings -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Paramètres des Rendez-vous</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="appointment_duration" class="block text-sm font-medium text-gray-700">
                                Durée des Rendez-vous
                            </label>
                            <select name="appointment_duration" id="appointment_duration"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="30" {{ old('appointment_duration', $settings->appointment_duration) == 30 ? 'selected' : '' }}>30 minutes</option>
                                <option value="45" {{ old('appointment_duration', $settings->appointment_duration) == 45 ? 'selected' : '' }}>45 minutes</option>
                                <option value="60" {{ old('appointment_duration', $settings->appointment_duration) == 60 ? 'selected' : '' }}>1 heure</option>
                                <option value="90" {{ old('appointment_duration', $settings->appointment_duration) == 90 ? 'selected' : '' }}>1 heure 30</option>
                            </select>
                        </div>

                        <div>
                            <label for="buffer_time" class="block text-sm font-medium text-gray-700">
                                Temps de Pause Entre les Rendez-vous
                            </label>
                            <select name="buffer_time" id="buffer_time"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="0" {{ old('buffer_time', $settings->buffer_time) == 0 ? 'selected' : '' }}>Aucun</option>
                                <option value="5" {{ old('buffer_time', $settings->buffer_time) == 5 ? 'selected' : '' }}>5 minutes</option>
                                <option value="10" {{ old('buffer_time', $settings->buffer_time) == 10 ? 'selected' : '' }}>10 minutes</option>
                                <option value="15" {{ old('buffer_time', $settings->buffer_time) == 15 ? 'selected' : '' }}>15 minutes</option>
                                <option value="30" {{ old('buffer_time', $settings->buffer_time) == 30 ? 'selected' : '' }}>30 minutes</option>
                            </select>
                        </div>

                        <div>
                            <label for="max_daily_appointments" class="block text-sm font-medium text-gray-700">
                                Nombre Maximum de Rendez-vous par Jour
                            </label>
                            <select name="max_daily_appointments" id="max_daily_appointments"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ old('max_daily_appointments', $settings->max_daily_appointments) == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Meeting Types -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Types de Rendez-vous Acceptés</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="meeting_types[]" value="video"
                                       id="meeting_type_video"
                                       {{ in_array('video', old('meeting_types', $settings->meeting_types ?? [])) ? 'checked' : '' }}
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="meeting_type_video" class="font-medium text-gray-700">Visioconférence</label>
                                <p class="text-gray-500">Rendez-vous en ligne via Zoom</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="meeting_types[]" value="in_person"
                                       id="meeting_type_in_person"
                                       {{ in_array('in_person', old('meeting_types', $settings->meeting_types ?? [])) ? 'checked' : '' }}
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="meeting_type_in_person" class="font-medium text-gray-700">En Personne</label>
                                <p class="text-gray-500">Rendez-vous au bureau</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Preferences -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Préférences de Notification</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="notifications[]" value="email_new_request"
                                       id="notification_email_new"
                                       {{ in_array('email_new_request', old('notifications', $settings->notifications ?? [])) ? 'checked' : '' }}
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="notification_email_new" class="font-medium text-gray-700">Email - Nouvelle Demande</label>
                                <p class="text-gray-500">Recevoir un email pour chaque nouvelle demande de rendez-vous</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="notifications[]" value="email_reminder"
                                       id="notification_email_reminder"
                                       {{ in_array('email_reminder', old('notifications', $settings->notifications ?? [])) ? 'checked' : '' }}
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="notification_email_reminder" class="font-medium text-gray-700">Email - Rappel</label>
                                <p class="text-gray-500">Recevoir un rappel par email 24h avant chaque rendez-vous</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="notifications[]" value="sms_reminder"
                                       id="notification_sms_reminder"
                                       {{ in_array('sms_reminder', old('notifications', $settings->notifications ?? [])) ? 'checked' : '' }}
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="notification_sms_reminder" class="font-medium text-gray-700">SMS - Rappel</label>
                                <p class="text-gray-500">Recevoir un rappel par SMS 1h avant chaque rendez-vous</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendar Sync -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Synchronisation du Calendrier</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="calendar_sync[]" value="google"
                                       id="sync_google"
                                       {{ in_array('google', old('calendar_sync', $settings->calendar_sync ?? [])) ? 'checked' : '' }}
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="sync_google" class="font-medium text-gray-700">Google Calendar</label>
                                <p class="text-gray-500">Synchroniser automatiquement avec Google Calendar</p>
                            </div>
                            @if(!in_array('google', $settings->calendar_sync ?? []))
                                <button type="button" onclick="window.location.href='{{ route('advisors.schedule.sync.google') }}'"
                                        class="ml-4 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Connecter
                                </button>
                            @endif
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="calendar_sync[]" value="outlook"
                                       id="sync_outlook"
                                       {{ in_array('outlook', old('calendar_sync', $settings->calendar_sync ?? [])) ? 'checked' : '' }}
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="sync_outlook" class="font-medium text-gray-700">Outlook</label>
                                <p class="text-gray-500">Synchroniser automatiquement avec Outlook</p>
                            </div>
                            @if(!in_array('outlook', $settings->calendar_sync ?? []))
                                <button type="button" onclick="window.location.href='{{ route('advisors.schedule.sync.outlook') }}'"
                                        class="ml-4 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Connecter
                                </button>
                            @endif
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
                        Enregistrer les Modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const workingDayCheckboxes = document.querySelectorAll('input[name="working_days[]"]');
    
    workingDayCheckboxes.forEach(checkbox => {
        const day = checkbox.value;
        const startSelect = document.getElementById(`${day}_start`);
        const endSelect = document.getElementById(`${day}_end`);
        
        function updateSelectsState() {
            const isChecked = checkbox.checked;
            startSelect.disabled = !isChecked;
            endSelect.disabled = !isChecked;
            
            if (!isChecked) {
                startSelect.parentElement.classList.add('opacity-50');
                endSelect.parentElement.classList.add('opacity-50');
            } else {
                startSelect.parentElement.classList.remove('opacity-50');
                endSelect.parentElement.classList.remove('opacity-50');
            }
        }
        
        checkbox.addEventListener('change', updateSelectsState);
        updateSelectsState();
    });
});
</script>
@endpush

@endsection