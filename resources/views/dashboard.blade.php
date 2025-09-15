<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __("Today's Workout") }} - {{ now()->format('l, F j, Y') }}
        </h2>
    </x-slot>

    {{-- The entire Alpine component is now defined directly in x-data --}}
    <div x-data="{
        isModalOpen: false,
        newExercise: { name: '', body_part: '' },
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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="bg-green-500 text-white font-bold rounded-lg px-4 py-3 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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

                    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-white mb-4">Logged Today</h3>
                        <div class="space-y-4">
                            @forelse ($logsToday as $log)
                                <div class="bg-gray-700 p-4 rounded-lg flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-white">{{ $log->exercise->name }}</p>
                                        <p class="text-sm text-gray-400">Set {{ $log->set_number }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-lg text-white">{{ $log->weight_kg }} kg</p>
                                        <p class="text-sm text-gray-400">{{ $log->reps }} reps</p>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-gray-700 p-4 rounded-lg text-center text-gray-400">
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
                    <div class="mb-4">
                        <x-input-label for="new_exercise_name" value="Exercise Name" />
                        <x-text-input id="new_exercise_name" x-model="newExercise.name" class="block mt-1 w-full" type="text" required />
                    </div>
                    <div class="mb-6">
                        <x-input-label for="new_exercise_body_part" value="Body Part (e.g., Chest, Legs, Back)" />
                        <x-text-input id="new_exercise_body_part" x-model="newExercise.body_part" class="block mt-1 w-full" type="text" required />
                    </div>
                    <div class="flex justify-end space-x-4">
                         <button type="button" @click="isModalOpen = false" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
                         <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Exercise</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>