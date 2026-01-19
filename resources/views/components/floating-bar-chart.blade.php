@php
    $chartId = 'chart-' . Str::random(6); // Generate a unique ID only once.
@endphp

<h6>{{ $title }}</h6>
<canvas id="{{ $chartId }}" width="400" height="70"></canvas>


@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('{{ $chartId }}').getContext(
                '2d'); // Use the consistent ID here.
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels), // Ensure $labels is passed correctly
                    datasets: [{
                        label: '{{ $title }}',
                        data: @json($values), // Ensure $values is passed correctly
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush
