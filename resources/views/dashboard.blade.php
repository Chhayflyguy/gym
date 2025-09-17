<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __("Today's Workout") }} &mdash; {{ now()->format('l, F j, Y') }}
        </h2>
    </x-slot>

    <div x-data="{
        isModalOpen: false,
        newExercise: { name: '', body_part: '' },
        isEditModalOpen: false,
        editingLog: {},
        openEditModal(log) {
            // Make a copy of the log to avoid unintended reactivity issues with the list
            this.editingLog = JSON.parse(JSON.stringify(log));
            this.isEditModalOpen = true;
        },
        saveNewExercise() {
            fetch('{{ route('exercises.store') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content') },
                body: JSON.stringify(this.newExercise)
            })
            .then(response => {
                if (!response.ok) { throw new Error('Exercise already exists or invalid input.'); }
                return response.json();
            })
            .then(data => {
                const dropdown = document.getElementById('exercise_id');
                const newOption = new Option(data.name, data.id, true, true);
                dropdown.add(newOption);
                this.isModalOpen = false;
                this.newExercise = { name: '', body_part: '' };
            })
            .catch(error => { alert(error.message); });
        }
    }">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                @if (session('success'))
                <div class="bg-green-500 text-white font-bold rounded-lg px-4 py-3 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif
                @if (session('pr_achieved'))
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold rounded-lg px-4 py-3 mb-4" role="alert">
                    <p>{{ session('pr_achieved') }}</p>
                </div>
                @endif
                @if (session('achievement_unlocked'))
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold rounded-lg px-4 py-3 mb-4" role="alert">
                    <p>{{ session('achievement_unlocked') }}</p>
                </div>
                @endif

                <div class="flex flex-col gap-8">
                    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-white mb-4">Log a New Lift</h3>
                        <form method="POST" action="{{ route('workout-logs.store') }}">
                            @csrf
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-1">
                                    <x-input-label for="exercise_id" :value="__('Exercise')" />
                                    <button type="button" @click="isModalOpen = true" class="px-3 py-1 text-xs bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md">+ Add New</button>
                                </div>
                                <select id="exercise_id" name="exercise_id" class="block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option disabled selected value="">Choose an exercise...</option>
                                    @foreach ($exercises as $exercise)
                                    <option value="{{ $exercise->id }}">{{ $exercise->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="weight_kg" :value="__('Weight (kg)')" />
                                    <x-text-input id="weight_kg" class="block mt-1 w-full" type="number" name="weight_kg" :value="old('weight_kg')" required step="0.25" />
                                </div>
                                <div>
                                    <x-input-label for="reps" :value="__('Reps')" />
                                    <x-text-input id="reps" class="block mt-1 w-full" type="number" name="reps" :value="old('reps')" required />
                                </div>
                                <div>
                                    <x-input-label for="sets" :value="__('Sets')" />
                                    <x-text-input id="sets" class="block mt-1 w-full" type="number" name="sets" value="1" required />
                                </div>
                            </div>
                            <div class="flex items-center justify-end mt-6">
                                <x-primary-button>{{ __('Log Set(s)') }}</x-primary-button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-white">Logged Today</h3>
                        </div>
                        <div x-data="{ openExercise: '' }" class="border-t border-gray-700">
                            @forelse ($logsToday->groupBy('exercise.name') as $exerciseName => $sets)
                            @php
                            $totalSets = $sets->count();
                            $totalVolume = $sets->sum(fn($s) => $s->weight_kg * $s->reps);
                            @endphp
                            <div class="border-b border-gray-700 last:border-b-0">
                                <button @click="openExercise = (openExercise === '{{ $exerciseName }}' ? '' : '{{ $exerciseName }}')" class="w-full flex justify-between items-center p-6 text-left hover:bg-gray-700/50 focus:outline-none">
                                    <div class="flex-grow">
                                        <h4 class="text-lg font-semibold text-white">{{ $exerciseName }}</h4>
                                        <p class="text-sm text-gray-400">
                                            {{ $totalSets }} sets &bull; {{ number_format($totalVolume, 0) }} kg total volume
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 ml-4">
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300" :class="{ 'rotate-180': openExercise === '{{ $exerciseName }}' }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </div>
                                </button>
                                <div x-show="openExercise === '{{ $exerciseName }}'" x-transition class="px-6 pb-4 bg-gray-900/50">
                                    <ul class="divide-y divide-gray-700 pt-4">
                                        @foreach ($sets->sortBy('set_number') as $set)
                                        <li class="py-2 flex justify-between items-center text-sm">
                                            <div>
                                                <span class="text-gray-400">Set {{ $set->set_number }}</span>
                                                <span class="font-mono text-gray-200 ml-4">{{ $set->weight_kg }} kg &times; {{ $set->reps }} reps</span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <button @click="openEditModal({{ $set }})" class="text-yellow-400 hover:text-yellow-300 text-xs font-semibold uppercase">Edit</button>
                                                <form method="POST" action="{{ route('workout-logs.destroy', $set) }}" onsubmit="return confirm('Are you sure you want to delete this set?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-400 text-xs font-semibold uppercase">Delete</button>
                                                </form>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @empty
                            <div class="p-6 text-center text-gray-400">
                                <p>No lifts logged for today yet. Let's get to work! 💪</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="isModalOpen" @click.away="isModalOpen = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
            <div class="bg-gray-800 text-white rounded-lg shadow-xl p-8 w-full max-w-md">
                <h2 class="text-2xl font-bold mb-4">Add a New Exercise</h2>
                <form @submit.prevent="saveNewExercise">
                    <div class="mb-4"><x-input-label for="new_exercise_name" value="Exercise Name" /><x-text-input id="new_exercise_name" x-model="newExercise.name" class="block mt-1 w-full" type="text" required /></div>
                    <div class="mb-6"><x-input-label for="new_exercise_body_part" value="Body Part (e.g., Chest, Legs, Back)" /><x-text-input id="new_exercise_body_part" x-model="newExercise.body_part" class="block mt-1 w-full" type="text" required /></div>
                    <div class="flex justify-end space-x-4"><button type="button" @click="isModalOpen = false" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button><button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Exercise</button></div>
                </form>
            </div>
        </div>

        <div x-show="isEditModalOpen" @click.away="isEditModalOpen = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
            <div class="bg-gray-800 text-white rounded-lg shadow-xl p-8 w-full max-w-md">
                <h2 class="text-2xl font-bold mb-4">Edit Set: <span x-text="editingLog.exercise ? editingLog.exercise.name : ''"></span></h2>
                <form :action="'/workout-logs/' + editingLog.id" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2 gap-4">
                        <div><x-input-label for="edit_weight_kg" value="Weight (kg)" /><x-text-input id="edit_weight_kg" name="weight_kg" x-model="editingLog.weight_kg" class="block mt-1 w-full" type="number" required step="0.25" /></div>
                        <div><x-input-label for="edit_reps" value="Reps" /><x-text-input id="edit_reps" name="reps" x-model="editingLog.reps" class="block mt-1 w-full" type="number" required /></div>
                    </div>
                    <div class="flex justify-end space-x-4 mt-6">
                        <button type="button" @click="isEditModalOpen = false" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>