@extends('layouts.admin')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">

            @include('custom.project-counter')

            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">List of Projects</h6>

                <div>
                    <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                        <a href="{{ route('projects.index') }}" style="color:white;">View Projects</a>
                    </span>
                </div>
            </div>

            <div class="row">
                <canvas id="histogramChart"></canvas>
            </div>
        </div>
    </div>

    @include('custom.customizer')
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        fetch("{{ route('histogram.data') }}")
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('histogramChart').getContext('2d');


                const getColor = (percentage) => {
                    if (percentage < 25) return 'red';
                    if (percentage <= 50) return 'orange';
                    if (percentage <= 70) return 'yellow';
                    if (percentage <= 89) return 'blue';
                    return 'green';
                };

                const backgroundColors = data.map(item => getColor(item.percentage));


                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(item => item.name),
                        datasets: [{
                            label: 'Percentage',
                            data: data.map(item => item.percentage),
                            backgroundColor: backgroundColors,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 0.5,
                            barThickness: 70,
                            maxBarThickness: 70,

                        }]
                    },
                    options: {
                        responsive: true,
                        layout: {
                            padding: 0
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                ticks: {
                                    callback: function(value) {
                                        return value + "%";
                                    }
                                }
                            },
                            x: {
                                display: false,
                            },
                        },
                        plugins: {
                            tooltip: {
                                enabled: true
                            },
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    generateLabels: function(chart) {
                                        return [{
                                                text: 'Less than 25% - Red',
                                                fillStyle: 'red'
                                            },
                                            {
                                                text: '26% - 50% - Orange',
                                                fillStyle: 'orange'
                                            },
                                            {
                                                text: '51% - 70% - Yellow',
                                                fillStyle: 'yellow'
                                            },
                                            {
                                                text: '71% - 89% - Blue',
                                                fillStyle: 'blue'
                                            },
                                            {
                                                text: '90% - 100% - Green',
                                                fillStyle: 'green'
                                            }
                                        ];
                                    }
                                }
                            }
                        },
                        onClick: (event, elements) => {
                            if (!elements.length) return;
                            if (elements.length > 0) {
                                const index = elements[0].index;
                                const projectId = data[index].ulid;
                                navigateToProject(projectId);
                            }
                        }
                    }
                });
            });

        function navigateToProject(projectId) {
            window.location.href = `/projects/show/${projectId}`;
        }
    </script>
@endsection
