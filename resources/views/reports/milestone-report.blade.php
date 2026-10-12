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

        <img src="{{ asset('/assets/images/logos/ncaalogo.png') }}" alt="Logo" class="company-logo">
        <h3 class="mt-3">Nigerian Civil Aviation Authority HQ, Abuja</h3>
        <p class="lead">Project Milestone Completion/Interim Payment Certificate (IPC)</p>
    </div>

    <div class="container">
        <div>
            <h5 class="my-4">Milestones Details</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <p><strong>Milestone Name:</strong> {{ $milestone->name }}</p>
                    <p><strong>Project Name:</strong> {{ $milestone->project?->name }}</p>
                    <p><strong>Project Cost:</strong> ₦{{ number_format($milestone->project?->cost, 2) }}</p>
                    <p><strong>Project Progress:</strong> {{ $milestone->project?->get_progress() }}%</p>
                </div>
                <div class="col-md-6 mb-2">
                    <p><strong>Milestone Amount:</strong> ₦{{ number_format($milestone->amount, 2) }}</p>
                    <p><strong>Description:</strong> {{ $milestone->description }}</p>
                    <p><strong>Created:</strong> {{ $milestone->created_at->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>
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
                                <td>{{ $task->name }}</td>
                                <td>{{ $task->cost }}</td>
                                <td>{{ $task->start_date }}</td>
                                <td>{{ Str::ucfirst($task->status?->value) }}</td>
                            </tr>
                        @empty
                            <tr colspan="4">
                                No milestone tasks added!!!
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
        <div>
            <h5>Authorizations</h5>
            @forelse ($milestone->project?->project_personnel as $project_personnel)
                @php
                    $personnel = $project_personnel->personnel?->user;
                @endphp
                <div class="p-3 rounded mt-2">
                    <p><strong>Name:</strong>
                        {{ $personnel->name ?? $personnel->first_name . ' ' . $personnel->last_name }}
                    </p>

                    <p><strong>Designation:</strong>
                        {{ $personnel->hasRole('personnel') ? 'Organization Personnel' : 'User' }}
                    </p>

                    <p><strong>Date Assigned:</strong>
                        {{ $project_personnel->created_at->format('Y-m-d H:i:s') }}
                    </p>
                </div>
            @empty
                <p>No project Personnel</p>
            @endforelse
        </div>

        <div class="p-3 rounded">
            <h6 class="text-secondary mb-3">Approved By</h6>

            <p><strong>Project Director Name:</strong> {{ $milestone->project?->creator?->name }}</p>

            <p><strong>Designation:</strong>
                {{ $milestone->project?->creator->hasRole('admin') ? 'Admin' : 'User' }}
            </p>

            {{-- <p><strong>Signature:</strong> [Insert signature]</p> --}}

            <p><strong>Approval Date:</strong>
                {{ $milestone->project?->updated_at->format('Y-m-d H:i:s') }}
            </p>
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
