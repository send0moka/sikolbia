<!-- Chart Tab Content -->
<div class="space-y-8">
    @foreach($results as $index => $resultSet)
        @php
            $queueItem = $resultSet['queue_item'];
            $pivotData = $resultSet['data'];
        @endphp
        
        <!-- Dataset Header -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-neutral-700 dark:to-neutral-600 rounded-lg p-4 mb-4">
            <h4 class="text-lg font-semibold text-neutral-900 dark:text-white">
                Grafik Dataset {{ $index + 1 }}: {{ $queueItem['topik'] }}
            </h4>
        </div>

        @if(!empty($pivotData['data']))
            <!-- Chart Container -->
            <div class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-600 p-6">
                <canvas id="chart-{{ $index }}" width="400" height="200"></canvas>
            </div>

            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx{{ $index }} = document.getElementById('chart-{{ $index }}').getContext('2d');
                    
                    // Prepare data for Chart.js
                    const data{{ $index }} = @json($pivotData['data']);
                    const totals{{ $index }} = @json($pivotData['totals'] ?? []);
                    
                    // Extract labels and datasets
                    const labels = Object.keys(data{{ $index }});
                    const datasets = [];
                    
                    // Get all unique variable-year combinations
                    const allCombinations = new Set();
                    Object.values(data{{ $index }}).forEach(wilayahData => {
                        Object.keys(wilayahData).forEach(variabel => {
                            Object.keys(wilayahData[variabel]).forEach(tahunBulan => {
                                allCombinations.add(`${variabel} - ${tahunBulan}`);
                            });
                        });
                    });
                    
                    // Create datasets for each combination
                    const colors = [
                        'rgb(34, 197, 94)', 'rgb(59, 130, 246)', 'rgb(168, 85, 247)',
                        'rgb(239, 68, 68)', 'rgb(245, 158, 11)', 'rgb(16, 185, 129)',
                        'rgb(139, 92, 246)', 'rgb(236, 72, 153)', 'rgb(6, 182, 212)'
                    ];
                    
                    Array.from(allCombinations).forEach((combination, idx) => {
                        const [variabel, tahunBulan] = combination.split(' - ');
                        const color = colors[idx % colors.length];
                        
                        const dataPoints = labels.map(label => {
                            return data{{ $index }}[label][variabel] && data{{ $index }}[label][variabel][tahunBulan] 
                                ? data{{ $index }}[label][variabel][tahunBulan] 
                                : 0;
                        });
                        
                        datasets.push({
                            label: combination,
                            data: dataPoints,
                            backgroundColor: color + '20', // 20 for transparency
                            borderColor: color,
                            borderWidth: 2,
                            tension: 0.4
                        });
                    });
                    
                    new Chart(ctx{{ $index }}, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                title: {
                                    display: true,
                                    text: '{{ $queueItem["topik"] }} - {{ implode(", ", $queueItem["variabels"]) }}'
                                },
                                legend: {
                                    display: true,
                                    position: 'top'
                                }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: '{{ ucfirst($variabelVertikal) }}'
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Nilai (Ton)'
                                    },
                                    beginAtZero: true
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            }
                        }
                    });
                });
            </script>
            @endpush
        @else
            <div class="text-center py-8">
                <flux:icon.chart-bar-square class="w-12 h-12 text-neutral-400 mx-auto mb-4" />
                <p class="text-neutral-600 dark:text-neutral-400">
                    Tidak ada data untuk digambarkan dalam grafik.
                </p>
            </div>
        @endif
        
        @if(!$loop->last)
            <hr class="border-neutral-200 dark:border-neutral-600">
        @endif
    @endforeach
</div>

@if(!empty($results))
    @push('styles')
    <style>
        canvas {
            max-height: 400px !important;
        }
    </style>
    @endpush
@endif
