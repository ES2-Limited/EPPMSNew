<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Milestones Report</title>
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
    @php
        $milestone = $milestones->first();
    @endphp
    <div class="container text-center my-4">
        <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">

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
                    Back to Project Milestones</a>
            </span>
        </div>


        <img src="{{ asset('/assets/images/logos/ncaalogo.png') }}" alt="Logo" class="company-logo">
        <h3 class="mt-3">Nigerian Civil Aviation Authority HQ, Abuja</h3>
        <p class="lead">Project Milestones Report</p>
    </div>

    <div class="container">
        <div>
            <h5 class="my-4">Milestones Details</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Project Name:</strong> {{ $milestone->project?->name }}</p>
                    <p><strong>Project Cost:</strong> ₦{{ number_format($milestone->project?->cost, 2) }}</p>
                    <p><strong>Project Progress:</strong> {{ $milestone->project?->get_progress() }}%</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Milestone Amount:</strong> ₦{{ number_format($milestone->amount, 2) }}</p>
                    <p><strong>Description:</strong> {{ $milestone->description }}</p>
                    <p><strong>Created:</strong> {{ $milestone->created_at->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>
        </div>

        <div>
            <h5 class="my-4">Project Milestones</h5>
            <div id="tableView">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Cost</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Total Tasks</th>
                        </tr>
                    </thead>
                    <tbody id="itemTable">
                        @forelse ($milestones as $milestone)
                            <tr>
                                <td>{{ $milestone->name }}</td>
                                <td>{{ $milestone->amount }}</td>
                                <td>{{ $milestone->get_progress() }}%</td>
                                <td>{{ Str::ucfirst($milestone->status?->value) }}</td>
                                <td>
                                    {{ $milestone->tasks?->count() }} &nbsp; &nbsp; &nbsp;
                                </td>
                            </tr>
                        @empty
                            <tr colspan="3">
                                No project milestones created!!!
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="graphView" class="d-none">
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="d-flex align-items-center">
                            <canvas id="milestoneChart"></canvas>
                        </div>
                    </div>
                </div>
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

    const milestones = @json($milestones);
    let milestoneChartInstance = null;

    function drawChart() {
        var ctx = document.getElementById('milestoneChart').getContext('2d');

        if (milestoneChartInstance !== null) {
            milestoneChartInstance.destroy();
        }

        var labels = milestones.map(m => m.name);
        var dates = milestones.map(m => new Date(m.deadline));
        var years = dates.map(d => d.getFullYear());

        var minYear = Math.min(...years);
        var maxYear = Math.max(...years);

        milestoneChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Milestone Due Dates',
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
                                let milestoneIndex = tooltipItem.dataIndex;
                                let milestone = milestones[milestoneIndex];
                                return `milestone: ${milestone.name}\nDate: ${milestone.deadline}`;
                            }
                        }
                    }
                },
                onClick: (event, elements) => {
                    if (elements.length > 0) {
                        let index = elements[0].index;
                        let milestone = milestones[index];
                        alert(`milestone: ${milestone.name}\nDate: ${milestone.deadline}`);
                    }
                }
            }
        });
    }
</script>

</html>
