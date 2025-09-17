<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Progress Charts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{
                selectedExercise: '',
                chart: null,
                fetchChartData() {
                    // If a chart instance already exists, destroy it first.
                    if (this.chart) {
                        this.chart.destroy();
                    }

                    // If no exercise is selected, do nothing further.
                    if (!this.selectedExercise) {
                        return;
                    }
                    
                    fetch(`/progress/chart-data/${this.selectedExercise}`)
                        .then(response => response.json())
                        .then(data => {
                            const ctx = document.getElementById('progressChart').getContext('2d');
                            
                            // Create a brand new chart instance with the new data.
                            this.chart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: data.labels.length > 0 ? data.labels : ['No PRs recorded yet'],
                                    datasets: [{
                                        label: 'Max Weight (kg)',
                                        data: data.data,
                                        borderColor: 'rgb(99, 102, 241)',
                                        backgroundColor: 'rgba(99, 102, 241, 0.2)',
                                        borderWidth: 2,
                                        tension: 0.1,
                                        fill: true,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: { beginAtZero: false, ticks: { color: 'rgb(156, 163, 175)' }, grid: { color: 'rgba(255,255,255,0.1)' } },
                                        x: { ticks: { color: 'rgb(156, 163, 175)' }, grid: { color: 'rgba(255,255,255,0.1)' } }
                                    },
                                    plugins: {
                                        legend: { labels: { color: 'rgb(209, 213, 219)' } }
                                    }
                                }
                            });
                        });
                }
            }" 
            class="bg-gray-800 rounded-lg shadow-md p-4 sm:p-6">

                <div class="mb-4">
                    <label for="exercise_select" class="block text-sm font-medium text-gray-300">Select an Exercise to View Progress</label>
                    <select x-model="selectedExercise" @change="fetchChartData()" id="exercise_select" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 rounded-md shadow-sm">
                        <option value="">-- Choose an exercise --</option>
                        @foreach ($exercises as $exercise)
                            <option value="{{ $exercise->id }}">{{ $exercise->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative h-96">
                    <canvas id="progressChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>