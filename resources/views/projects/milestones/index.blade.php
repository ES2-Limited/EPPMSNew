@extends('layouts.admin')
@section('custom-css')
    <style>
        .three-dots {
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            background: none;
            padding: 0;
        }
    </style>
@endsection
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            @include('custom.project-counter')
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">{{ $project->name }} Project Milestones</h6>
                <div>
                    @if ($milestones->count() > 0)
                        <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                            <a href="{{ route('milestones.print', ['ulid' => $project->ulid]) }}" style="color:white;">Print
                                Report</a>
                        </span>
                    @endif

                    <span class="badge text-bg-success rounded-4 px-3 py-1 lh-sm">
                        <a href="{{ route('projects.show', ['ulid' => $project->ulid]) }}" style="color: white">
                            Back to Project</a>
                    </span>

                    @role('admin')
                        <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                            <a href="{{ route('project.milestones.create', ['project_ulid' => $project->ulid]) }}"
                                style="color: white">
                                Add Project Milestone</a>
                        </span>
                    @endrole
                </div>
            </div>

            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Milestone</th>
                                    <th>Budget / Amount</th>
                                    <th style="min-width: 160px;">Progress</th>
                                    <th>Status</th>
                                    <th>Tasks Count</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="itemTable">
                                @forelse ($milestones as $milestone)
                                    @php
                                        $progress = $milestone->get_progress();
                                        $value = Str::ucfirst($milestone->status?->value ?? 'Pending');
                                        if ($progress < 30) {
                                            $progColor = 'bg-danger';
                                        } elseif ($progress < 70) {
                                            $progColor = 'bg-warning';
                                        } else {
                                            $progColor = 'bg-success';
                                        }
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $milestone->name }}</div>
                                            @if($milestone->deadline)
                                                <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> Due {{ \Carbon\Carbon::parse($milestone->deadline)->format('M d, Y') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark">₦{{ number_format($milestone->amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-grow-1" style="height: 6px;">
                                                    <div class="progress-bar {{ $progColor }}" role="progressbar"
                                                        style="width: {{ $progress }}%"
                                                        aria-valuenow="{{ $progress }}" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <span class="small fw-semibold text-muted">{{ $progress }}%</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $value === 'Done' ? 'text-bg-success' : ($value === 'Pending' ? 'text-bg-warning' : 'text-bg-primary') }} rounded-pill">
                                                {{ $value }}
                                            </span>
                                            @if ($value === 'Done')
                                                <a href="{{ url('admin/report/milestone/' . $milestone->ulid) }}"
                                                    class="btn btn-sm btn-outline-info rounded-pill py-0 px-2 ms-1" title="Print Report">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge text-bg-secondary rounded-pill">{{ $milestone->tasks?->count() ?? 0 }} Tasks</span>
                                                @if ($milestone->tasks?->count() > 0)
                                                    <a href="{{ route('milestone.tasks', ['milestone_ulid' => $milestone->id]) }}"
                                                        class="btn btn-sm btn-outline-primary rounded-pill py-1 px-2">
                                                        View Tasks
                                                    </a>
                                                @endif
                                                @hasanyrole('admin|personnel')
                                                    <a href="{{ route('milestone.tasks.create', ['milestone_ulid' => $milestone->id]) }}"
                                                        class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2">
                                                        + Task
                                                    </a>
                                                @endrole
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light rounded-circle shadow-none p-2" type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false" style="width: 32px; height: 32px;">
                                                    <i class="fas fa-ellipsis-v text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('project.milestones.show', ['milestone_ulid' => $milestone->ulid]) }}">
                                                            <i class="far fa-eye me-2 text-primary"></i> View Details
                                                        </a>
                                                    </li>
                                                    @hasanyrole('admin|personnel')
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('project.milestones.edit', ['milestone_ulid' => $milestone->ulid]) }}">
                                                                <i class="far fa-edit me-2 text-warning"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('project.milestones.delete', ['milestone_ulid' => $milestone->ulid]) }}"
                                                                method="POST" onsubmit="return deleteConfirmation()">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="far fa-trash-alt me-2"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endrole
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle me-1"></i> No project milestones registered yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                {{ $milestones->links() }}
            </div>
            </div>
        </div>
    </div>

    @include('custom.customizer')
    </div>
    </div>
    <script>
        function deleteConfirmation() {
            return confirm('Are you sure you want to delete this item?');
        }
    </script>
@endsection
