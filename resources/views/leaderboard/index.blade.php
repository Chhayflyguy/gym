<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Leaderboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
                <form method="GET" action="{{ route('leaderboard.index') }}">
                    <div class="flex items-end space-x-4">
                        <div class="flex-grow">
                            <label for="exercise_id" class="block font-medium text-sm text-gray-300">Select Exercise</label>
                            <select id="exercise_id" name="exercise_id" class="block mt-1 w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option disabled selected>Choose an exercise to see rankings...</option>
                                @foreach ($exercises as $exercise)
                                    <option value="{{ $exercise->id }}" {{ request('exercise_id') == $exercise->id ? 'selected' : '' }}>
                                        {{ $exercise->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                             <x-primary-button>
                                {{ __('View Rankings') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if (!empty($leaderboard) && count($leaderboard) > 0)
                        <table class="min-w-full">
                            <thead class="border-b border-gray-600">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Rank</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Max Weight</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach ($leaderboard as $index => $entry)
                                    <tr class="hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-2xl font-bold">
                                            @if($index == 0) 🏆
                                            @elseif($index == 1) 🥈
                                            @elseif($index == 2) 🥉
                                            @else {{ $index + 1 }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-white">{{ $entry->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-lg text-indigo-400">{{ number_format($entry->max_weight, 2) }} kg</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-10 text-gray-400">
                            <p>Select an exercise from the dropdown to see the rankings!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>