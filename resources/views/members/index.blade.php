<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Members') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Responsive Grid for Member Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($users as $user)
                    <div class="bg-gray-800 rounded-lg p-6 text-center">
                        <h3 class="text-lg font-bold text-white">{{ $user->name }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Joined: {{ $user->created_at->format('M Y') }}</p>
                        <a href="{{ route('members.show', $user) }}" class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm py-2 px-4 rounded">
                            View Profile
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            <div class="mt-8">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>