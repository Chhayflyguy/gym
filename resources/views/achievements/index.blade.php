<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Achievements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-12">

                @php
                    $tierStyles = [
                        'Gold' => ['heading' => 'text-yellow-400', 'border' => 'border-yellow-400', 'shadow' => 'shadow-yellow-400/10', 'progress' => 'bg-yellow-400'],
                        'Silver' => ['heading' => 'text-slate-300', 'border' => 'border-slate-400', 'shadow' => 'shadow-slate-400/10', 'progress' => 'bg-slate-300'],
                        'Bronze' => ['heading' => 'text-orange-400', 'border' => 'border-orange-500', 'shadow' => 'shadow-orange-500/10', 'progress' => 'bg-orange-400'],
                    ];
                    $tiers = ['Gold', 'Silver', 'Bronze'];
                @endphp

                @foreach ($tiers as $tier)
                    @if(isset($achievementsByTier[$tier]))
                        <div>
                            <h3 class="text-2xl font-bold mb-4 {{ $tierStyles[$tier]['heading'] ?? 'text-white' }}">{{ $tier }} Tier</h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($achievementsByTier[$tier] as $achievement)
                                    @php
                                        $isUnlocked = in_array($achievement->id, $userAchievements);
                                        $progress = $progressData[$achievement->id] ?? ['current' => 0, 'goal' => $achievement->value, 'percent' => 0];
                                    @endphp

                                    <div class="bg-gray-800 rounded-lg p-6 flex flex-col text-center transition-transform duration-300 hover:-translate-y-2 
                                        {{ $isUnlocked ? 'border-2 ' . ($tierStyles[$tier]['border'] ?? '') . ' shadow-lg ' . ($tierStyles[$tier]['shadow'] ?? '') : 'opacity-60' }}">
                                        
                                        <div class="text-6xl mb-4 transition-transform duration-300 {{ $isUnlocked ? 'grayscale-0' : 'grayscale' }}">{{ $achievement->icon }}</div>
                                        <h4 class="text-lg font-bold {{ $isUnlocked ? 'text-white' : 'text-gray-400' }}">{{ $achievement->name }}</h4>
                                        <p class="text-sm text-gray-400 mt-2 flex-grow">{{ $achievement->description }}</p>

                                        @if($isUnlocked)
                                            <div class="mt-4 text-xs font-semibold uppercase {{ $tierStyles[$tier]['heading'] ?? 'text-white' }}">Unlocked</div>
                                        @else
                                            <div class="w-full mt-4">
                                                <div class="text-xs text-gray-300 mb-1 text-right">{{ number_format($progress['current']) }} / {{ number_format($progress['goal']) }}</div>
                                                <div class="w-full bg-gray-700 rounded-full h-2.5">
                                                    <div class="{{ $tierStyles[$tier]['progress'] }} h-2.5 rounded-full" style="width: {{ min($progress['percent'], 100) }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>