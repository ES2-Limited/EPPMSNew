<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $milestone->name }} Milestone Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }

        .company-logo {
            width: 100px;
            height: auto;
        }
    </style>
</head>

<body>

    <div class="container text-center my-4">
        <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between no-print">

            <div class="no-print">
                <label for="viewSelector" class="form-label fw-bold">Select View:</label>
                <select id="viewSelector" class="form-select w-auto d-inline-block" onchange="toggleView()">
                    <option value="table">Table View</option>
                    <option value="graph">Graph View</option>
                </select>
            </div>

            <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-md no-print">
                <a href="{{ route('project.milestones', ['project_ulid' => $milestone->project?->ulid]) }}"
                    style="color: white; text-decoration:none;">
                    Back to Project</a>
            </span>
        </div>

        <img src="{{ setting_asset('app_logo', 'assets/images/logos/ncaalogo.png') }}" alt="Logo" class="company-logo">
        <h3 class="mt-3 fw-bold">{{ setting('organization_name', 'Nigerian Civil Aviation Authority HQ, Abuja') }}</h3>
        <p class="lead fw-semibold text-primary">{{ setting('ipc_certificate_title', 'Project Milestone Completion/Interim Payment Certificate (IPC)') }}</p>
    </div>

    <div class="container">
        <div>
            <h5 class="my-4 fw-bold border-bottom pb-2">Milestone & Interim Payment Details</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <p><strong>Milestone Name:</strong> {{ $milestone->name }}</p>
                    <p><strong>Project Name:</strong> {{ $milestone->project?->name }}</p>
                    <p><strong>Project Total Cost:</strong> {{ format_currency($milestone->project?->cost) }}</p>
                    <p><strong>Overall Project Progress:</strong> {{ $milestone->project?->get_progress() }}%</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Milestone Certified Value:</strong> <span class="fs-5 fw-bold text-success">{{ format_currency($milestone->amount) }}</span></p>
                    <p><strong>Milestone Status:</strong> {!! status_badge($milestone->status) !!}</p>
                    <p><strong>Created:</strong> {{ $milestone->created_at->format('Y-m-d') }}</p>
                </div>
            </div>
            @if($milestone->description)
                <div class="mt-2">
                    <p><strong>Milestone Scope Description:</strong> <span class="text-muted">{{ $milestone->description }}</span></p>
                </div>
            @endif
        </div>

        <div>
            <h5 class="my-4">Milestones Tasks</h5>
            <div id="tableView">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Cost</th>
                            <th>Start Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($milestone->tasks as $task)
                            <tr>
                                <td class="fw-semibold">{{ $task->name }}</td>
                                <td>{{ format_currency($task->cost) }}</td>
                                <td>{{ $task->start_date }}</td>
                                <td>{!! status_badge($task->status) !!}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">No milestone tasks registered.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="graphView" class="d-none">
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="d-flex align-items-center">
                            <canvas id="taskChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-4 pt-3 border-top g-4">
            <div class="col-md-6">
                <h6 class="fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px;">Supervising Team & Personnel:</h6>
                @forelse ($milestone->project?->project_personnel as $project_personnel)
                    @php
                        $personnel = $project_personnel->personnel?->user;
                    @endphp
                    <div class="p-2 border rounded bg-light mb-2">
                        <p class="mb-0 fw-bold">{{ $personnel->name ?? $personnel->first_name . ' ' . $personnel->last_name }}</p>
                        <p class="mb-0 small text-muted">{{ $project_personnel->personnel?->designation ?? 'Field Inspector' }} &bull; {{ $project_personnel->created_at->format('Y-m-d') }}</p>
                    </div>
                @empty
                    <p class="text-muted small">No assigned project personnel recorded.</p>
                @endforelse
            </div>

            <div class="col-md-6">
                <h6 class="fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px;">Authorized Executive Approval:</h6>
                @if(isset($signatory) && $signatory)
                    @if($signatory->signature && file_exists(public_path('storage/signature_files/' . $signatory->signature)))
                        <img src="{{ asset('storage/signature_files/' . $signatory->signature) }}" alt="Signature" style="max-height: 60px; display: block; margin-bottom: 5px;">
                    @else
                        <div style="border-bottom: 1.5px solid #333; width: 220px; margin-bottom: 8px;"></div>
                    @endif
                    <p class="mb-0 fw-bold fs-6">{{ $signatory->name }}</p>
                    <p class="text-muted small mb-0">{{ $signatory->description ?? 'Authorized Executive' }} &bull; <i>{{ now()->format(setting('date_format', 'F j, Y')) }}</i></p>
                @else
                    <div style="border-bottom: 1.5px solid #333; width: 220px; margin-bottom: 8px;"></div>
                    <p class="mb-0 fw-bold">{{ setting('organization_name', 'Director-General / CEO') }}</p>
                    <p class="text-muted small mb-0">Official Clearance &bull; <i>{{ now()->format('F j, Y') }}</i></p>
                @endif
            </div>
        </div>
    </div>

    <div class="text-center my-4 no-print">
        <button class="btn btn-primary" onclick="window.print()">Print Report</button>
    </div>

</body>
<script>
    function toggleView() {
        var selectedView = document.getElementById('viewSelector').value;
        var tableView = document.getElementById('tableView');
        var graphView = document.getElementById('graphView');

        if (selectedView === 'table') {
            tableView.classList.remove('d-none');
            graphView.classList.add('d-none');
        } else {
            tableView.classList.add('d-none');
            graphView.classList.remove('d-none');
            drawChart();
        }
    }

    const tasks = @json($milestone->tasks);
    let taskChartInstance = null;

    function drawChart() {
        var ctx = document.getElementById('taskChart').getContext('2d');

        if (taskChartInstance !== null) {
            taskChartInstance.destroy();
        }

        var labels = tasks.map(m => m.name);
        var dates = tasks.map(m => new Date(m.due_date));
        var years = dates.map(d => d.getFullYear());

        var minYear = Math.min(...years);
        var maxYear = Math.max(...years);

        taskChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Task Due Dates',
                    data: years,
                    backgroundColor: 'blue',
                    borderWidth: 0.5,
                    barThickness: 30,
                    maxBarThickness: 30,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: minYear - 1,
                        max: maxYear + 1,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return value;
                            }
                        }
                    },
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(tooltipItem) {
                                let taskIndex = tooltipItem.dataIndex;
                                let task = tasks[taskIndex];
                                return `task: ${task.name}\nDate: ${task.due_date}`;
                            }
                        }
                    }
                },
                onClick: (event, elements) => {
                    if (elements.length > 0) {
                        let index = elements[0].index;
                        let task = tasks[index];
                        alert(`Task: ${task.name}\nDate: ${task.due_date}`);
                    }
                }
            }
        });
    }
</script>

</html>
