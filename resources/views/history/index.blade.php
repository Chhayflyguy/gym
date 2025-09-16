<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Workout History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">

                @forelse ($workoutsByDate as $date => $logsForDate)
                    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        {{-- Date Heading --}}
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white">
                                {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                            </h3>
                        </div>

                        {{-- Alpine.js component to control the accordion for this date's exercises --}}
                        <div x-data="{ openExercise: '' }" class="border-t border-gray-700">
                            
                            {{-- This loop now GROUPS all sets by the exercise name --}}
                            @foreach ($logsForDate->groupBy('exercise.name') as $exerciseName => $sets)
                                @php
                                    // Calculate summary stats for the exercise
                                    $totalSets = $sets->count();
                                    $totalVolume = $sets->sum(fn($s) => $s->weight_kg * $s->reps);
                                    $avgReps = $sets->avg('reps');
                                @endphp

                                {{-- Accordion Item Header --}}
                                <div class="border-b border-gray-700">
                                    <button @click="openExercise = (openExercise === '{{ $exerciseName }}' ? '' : '{{ $exerciseName }}')" class="w-full flex justify-between items-center p-6 text-left hover:bg-gray-700/50 focus:outline-none">
                                        <div class="flex-grow">
                                            <h4 class="text-lg font-semibold text-white">{{ $exerciseName }}</h4>
                                            <p class="text-sm text-gray-400">
                                                {{ $totalSets }} sets &bull; {{ number_format($totalVolume, 0) }} kg total volume
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0 ml-4">
                                            {{-- Chevron icon that rotates on click --}}
                                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300" :class="{ 'rotate-180': openExercise === '{{ $exerciseName }}' }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </div>
                                    </button>

                                    {{-- Accordion Item Body (collapsible details) --}}
                                    <div x-show="openExercise === '{{ $exerciseName }}'" x-transition class="px-6 pb-4 bg-gray-900/50">
                                        <ul class="divide-y divide-gray-700 pt-4">
                                            @foreach ($sets as $set)
                                                <li class="py-2 flex justify-between items-center text-sm">
                                                    <span class="text-gray-400">Set {{ $set->set_number }}</span>
                                                    <span class="font-mono text-gray-200">{{ $set->weight_kg }} kg &times; {{ $set->reps }} reps</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-10 text-center">
                        <p class="text-gray-400">You haven't logged any workouts yet. Go lift something!</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>