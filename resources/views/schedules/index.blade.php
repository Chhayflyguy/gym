<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Shared Schedules</h2>
            <a href="{{ route('schedules.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Create New Schedule
            </a>
        </div>
    </x-slot>

    <div x-data="{
        isOpen: false,
        schedule: {},
        viewSchedule(scheduleId) {
            fetch(`/schedules/${scheduleId}`)
                .then(response => response.json())
                .then(data => {
                    this.schedule = data;
                    const dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    this.schedule.schedule_days.sort((a, b) => dayOrder.indexOf(a.day_of_week) - dayOrder.indexOf(b.day_of_week));
                    this.isOpen = true;
                });
        }
    }">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="space-y-6">
                    @forelse ($schedules as $schedule)
                        <div class="bg-gray-800 p-6 rounded-lg shadow-md flex justify-between items-center">
                            <div>
                                <h3 class="text-2xl font-bold text-white">{{ $schedule->name }}</h3>
                                <p class="text-gray-400">Created by: {{ $schedule->user->name }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <button @click="viewSchedule({{ $schedule->id }})" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">View</button>
                                @canany(['update', 'delete'], $schedule)
                                    <a href="{{ route('schedules.edit', $schedule) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Edit</a>
                                    <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                                    </form>
                                @endcanany
                            </div>
                        </div>
                    @empty
                         <div class="bg-gray-800 p-6 rounded-lg text-center text-gray-400">
                            <p>No schedules have been created yet. Be the first!</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-8">{{ $schedules->links() }}</div>
            </div>
        </div>

        <div x-show="isOpen" @click.away="isOpen = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
            <div class="bg-gray-800 text-white rounded-lg shadow-xl p-8 w-full max-w-2xl">
                <h2 class="text-3xl font-bold mb-4" x-text="schedule.name"></h2>
                <div class="border-t border-gray-700 pt-4">
                    <ul class="space-y-2">
                        <template x-for="day in schedule.schedule_days" :key="day.id">
                            <li class="flex justify-between items-center bg-gray-700 p-3 rounded">
                                <span class="font-semibold" x-text="day.day_of_week"></span>
                                <span x-text="day.body_part"></span>
                            </li>
                        </template>
                    </ul>
                </div>
                <button @click="isOpen = false" class="mt-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded w-full">Close</button>
            </div>
        </div>
    </div>
</x-app-layout>