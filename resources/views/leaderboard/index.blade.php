<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Leaderboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
                <form method="GET" action="{{ route('leaderboard.index') }}">
                    <div class="flex flex-col md:flex-row md:items-end gap-6">
                        <div class="flex-grow">
                            <label for="exercise_id" class="block text-sm font-medium text-gray-300 mb-1">Filter by Exercise</label>
                            <select id="exercise_id" name="exercise_id" class="block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Exercises</option>
                                @foreach ($exercises as $exercise)
                                    <option value="{{ $exercise->id }}" {{ request('exercise_id') == $exercise->id ? 'selected' : '' }}>
                                        {{ $exercise->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                             <label class="block text-sm font-medium text-gray-300 mb-2">Rank By</label>
                             <div class="flex items-center space-x-4">
                                <label class="flex items-center space-x-2 text-gray-300 text-sm"><input type="radio" name="rank_by" value="total_volume_kg" class="form-radio bg-gray-900 text-indigo-500" {{ $rankBy == 'total_volume_kg' ? 'checked' : '' }}><span>Volume</span></label>
                                <label class="flex items-center space-x-2 text-gray-300 text-sm"><input type="radio" name="rank_by" value="total_reps" class="form-radio bg-gray-900 text-indigo-500" {{ $rankBy == 'total_reps' ? 'checked' : '' }}><span>Reps</span></label>
                                <label class="flex items-center space-x-2 text-gray-300 text-sm"><input type="radio" name="rank_by" value="total_sets" class="form-radio bg-gray-900 text-indigo-500" {{ $rankBy == 'total_sets' ? 'checked' : '' }}><span>Sets</span></label>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                             <x-primary-button>{{ __('Update Rankings') }}</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full">
                    <thead class="hidden md:table-header-group border-b border-gray-600">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Total Volume (kg)</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Total Reps</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Total Sets</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 md:divide-none">
                        @forelse ($leaderboard as $index => $entry)
                            <tr class="block md:table-row p-4 md:p-0">
                                <td class="flex justify-between py-1 md:table-cell md:px-6 md:py-4 whitespace-nowrap md:text-2xl font-bold"><span class="md:hidden text-gray-400 uppercase text-xs font-medium">Rank</span><span>{{ $leaderboard->firstItem() + $index }}</span></td>
                                <td class="flex justify-between py-1 md:table-cell md:px-6 md:py-4 whitespace-nowrap font-medium text-white"><span class="md:hidden text-gray-400 uppercase text-xs font-medium">Name</span><span>{{ $entry->user->name }}</span></td>
                                <td class="flex justify-between py-1 md:table-cell md:px-6 md:py-4 whitespace-nowrap text-right font-semibold text-lg {{ $rankBy == 'total_volume_kg' ? 'text-indigo-400' : 'text-white' }}"><span class="md:hidden text-gray-400 uppercase text-xs font-medium">Total Volume</span><span>{{ number_format($entry->total_volume_kg, 0) }} kg</span></td>
                                <td class="flex justify-between py-1 md:table-cell md:px-6 md:py-4 whitespace-nowrap text-right font-semibold text-lg {{ $rankBy == 'total_reps' ? 'text-indigo-400' : 'text-white' }}"><span class="md:hidden text-gray-400 uppercase text-xs font-medium">Total Reps</span><span>{{ number_format($entry->total_reps, 0) }}</span></td>
                                <td class="flex justify-between py-1 md:table-cell md:px-6 md:py-4 whitespace-nowrap text-right font-semibold text-lg {{ $rankBy == 'total_sets' ? 'text-indigo-400' : 'text-white' }}"><span class="md:hidden text-gray-400 uppercase text-xs font-medium">Total Sets</span><span>{{ number_format($entry->total_sets, 0) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-10 text-gray-400"><p>No workout data found for this filter. Try a different exercise!</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-6 border-t border-gray-600">
                    {{ $leaderboard->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>