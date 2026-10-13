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
                <h6 class="mb-0 fs-4">{{ $milestone->name }} Tasks</h6>

                <div>
                    @if ($tasks->count() > 0)
                        <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                            <a href="{{ url('admin/report/milestone/' . $milestone->ulid) }}" style="color:white;">Print
                                Report</a>
                        </span>
                    @endif


                    <span class="badge text-bg-success rounded-4 px-3 py-1 lh-sm">
                        <a href="{{ route('project.milestones', ['project_ulid' => $milestone->project?->ulid]) }}"
                            style="color: white">
                            Back to Milestone</a>
                    </span>

                    @hasanyrole('admin|personnel')
                        <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                            <a href="{{ route('milestone.tasks.create', ['milestone_ulid' => $milestone->id]) }}"
                                style="color: white">
                                Add Milestone Task</a>
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
                                    <th class="ps-4">Task Name</th>
                                    <th>Cost / Budget</th>
                                    <th>Start Date</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="itemTable">
                                @forelse ($tasks as $task)
                                    @php
                                        $taskStatus = Str::ucfirst($task->status?->value ?? 'Pending');
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $task->name }}</div>
                                            @if($task->due_date)
                                                <small class="text-muted"><i class="far fa-calendar-check me-1"></i> Due {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark">₦{{ number_format($task->cost, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('M d, Y') : '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $taskStatus === 'Done' ? 'text-bg-success' : ($taskStatus === 'Pending' ? 'text-bg-warning' : 'text-bg-primary') }} rounded-pill">
                                                {{ $taskStatus }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light rounded-circle shadow-none p-2" type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false" style="width: 32px; height: 32px;">
                                                    <i class="fas fa-ellipsis-v text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('milestone.tasks.show', ['task_ulid' => $task->ulid]) }}">
                                                            <i class="far fa-eye me-2 text-primary"></i> View
                                                        </a>
                                                    </li>
                                                    @hasanyrole('admin')
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('milestone.tasks.edit', ['task_ulid' => $task->ulid]) }}">
                                                                <i class="far fa-edit me-2 text-warning"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('milestone.tasks.delete', ['task_ulid' => $task->ulid]) }}"
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
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle me-1"></i> No milestone tasks added yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                {{ $tasks->links() }}
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
