@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            @php
                $ongoingProjects = $projects;
            @endphp

            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Ongoing Projects
                    <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">{{ $ongoingProjects->count() }}</span>
                </h6>

                @if ($ongoingProjects->count() > 0)
                    <p class="mb-0 fw-medium"> <a href="{{ route('projects.index') }}"> View All </a> </p>
                @endif
            </div>

            <div class="row g-4 mb-5">
                @forelse ($ongoingProjects as $project)
                    <div class="col-lg-4 col-md-6">
                        <div class="card project-card h-100 shadow-sm border-0">
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="badge text-bg-primary rounded-pill px-3 py-1">
                                            {{ Str::ucfirst($project->status?->value ?? 'Active') }}
                                        </span>
                                        <small class="text-muted d-flex align-items-center gap-1">
                                            <i class="far fa-clock"></i> {{ $project->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <h5 class="fw-bold mb-2 text-dark">{{ $project->name }}</h5>
                                    <p class="text-muted small mb-3">
                                        <i class="far fa-user me-1"></i> Creator: <span class="fw-semibold text-dark">{{ $project->creator?->name ?? 'System' }}</span>
                                    </p>
                                </div>

                                <div class="mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small fw-semibold text-muted">Completion Rate</span>
                                        <span class="small fw-bold text-dark">{{ $project->get_progress() }}%</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 8px;">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                            style="width: {{ $project->get_progress() }}%"
                                            aria-valuenow="{{ $project->get_progress() }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="small text-muted">
                                            <i class="fas fa-layer-group me-1"></i> {{ $project->milestones?->count() ?? 0 }} Milestones
                                        </span>
                                        <a href="{{ route('projects.show', ['ulid' => $project->ulid]) }}"
                                            class="btn btn-sm btn-outline-primary px-3 rounded-pill">
                                            View Project <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info border-0 rounded-3 shadow-sm d-flex align-items-center gap-2" role="alert">
                            <i class="fas fa-info-circle fs-5"></i>
                            <span>No ongoing projects found at this time.</span>
                        </div>
                    </div>
                @endforelse
            </div>


            @hasanyrole('admin')
                <div class="d-flex flex-wrap align-items-end justify-content-between mb-4 pb-3">
                    <h6 class="mb-3 mb-md-0 fs-4">Projects</h6>

                    <div class="d-flex flex-wrap gap-3">
                        <div class="d-flex flex-column">
                            <label for="dataFilter" class="form-label fw-semibold">Filter by Directorate</label>
                            <select id="dataFilter" class="form-select form-select-sm">
                                <option value="all">All Directorates</option>
                                @foreach ($directorates as $directorate)
                                    <option value="{{ $directorate->id }}">{{ $directorate->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex flex-column">
                            <label for="officeFilter" class="form-label fw-semibold">Filter by Office</label>
                            <select id="officeFilter" class="form-select form-select-sm">
                                <option value="all">All Offices</option>
                                @foreach ($offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center">
                            <canvas id="histogramChart"></canvas>
                        </div>
                    </div>
                </div>
            @endrole
        </div>
    </div>

    @include('custom.customizer')
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let chartInstance = null;

        function fetchHistogramData() {
            const directorateId = document.getElementById("dataFilter").value;
            const officeId = document.getElementById("officeFilter").value;

            const url = `{{ route('histogram.data') }}?directorate_id=${directorateId}&office_id=${officeId}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('histogramChart').getContext('2d');

                    if (chartInstance) {
                        chartInstance.destroy();
                    }

                    const getColor = (percentage) => {
                        if (percentage < 25) return 'red';
                        if (percentage <= 50) return 'orange';
                        if (percentage <= 70) return 'yellow';
                        if (percentage <= 89) return 'blue';
                        return 'green';
                    };

                    const backgroundColors = data.map(item => getColor(item.percentage));

                    chartInstance = new Chart(ctx, {
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
                                        callback: (value) => value + "%",
                                    }
                                },
                                x: {
                                    display: false
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
                                        generateLabels: () => [{
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
                                        ]
                                    }
                                }
                            },
                            onClick: (event, elements) => {
                                if (!elements.length) return;
                                const index = elements[0].index;
                                const projectId = data[index].ulid;
                                navigateToProject(projectId);
                            }
                        },
                    });
                }).catch(error => console.error("Error fetching data:", error));
        }

        function navigateToProject(projectId) {
            window.location.href = `/projects/show/${projectId}`;
        }

        document.getElementById("dataFilter").addEventListener("change", fetchHistogramData);
        document.getElementById("officeFilter").addEventListener("change", fetchHistogramData);

        fetchHistogramData();
    </script>
@endsection
