{{-- resources/views/schedules/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">Create a New Schedule</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('schedules.store') }}">
                    @csrf
                    <div>
                        <x-input-label for="name" value="Schedule Name" class="text-lg"/>
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                    </div>
                    <div class="mt-8 space-y-6">
                        @foreach ($days as $day)
                            <div>
                                <label for="day_{{ $day }}" class="block font-medium text-lg text-white">{{ $day }}</label>
                                <x-text-input id="day_{{ $day }}" class="block mt-1 w-full" type="text" name="days[{{ $day }}][body_part]" placeholder="e.g., Chest & Triceps, Leg Day, or Rest"/>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-end mt-8">
                        <x-primary-button>Create Schedule</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>