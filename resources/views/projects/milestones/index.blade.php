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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Tasks</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="itemTable">
                        @forelse ($milestones as $milestone)
                            <tr>
                                <td>{{ $milestone->name }}</td>
                                <td>{{ $milestone->amount }}</td>
                                <td>{{ $milestone->get_progress() }}%</td>
                                @php
                                    $value = Str::ucfirst($milestone->status?->value);
                                @endphp
                                <td>
                                    <span
                                        class="badge
                                        @if ($value === 'done') bg-primary
                                        @elseif($value === 'pending') bg-warning
                                        @else bg-secondary @endif ">
                                        {{ ucfirst($value) }}
                                    </span>
                                    <span class="badge">
                                        @if ($value === 'Done')
                                            <a href="{{ url('admin/report/milestone/' . $milestone->ulid) }}"
                                                class="btn btn-info btn-sm">Print</a>
                                        @endif
                                    </span>


                                </td>
                                <td>
                                    {{ $milestone->tasks?->count() }} &nbsp; &nbsp; &nbsp;
                                    @if ($milestone->tasks?->count() > 0)
                                        <a href="{{ route('milestone.tasks', ['milestone_ulid' => $milestone->id]) }}"
                                            class="btn btn-success btn-sm">View Task</a>
                                    @endif
                                    @hasanyrole('admin|personnel')
                                        <a href="{{ route('milestone.tasks.create', ['milestone_ulid' => $milestone->id]) }}"
                                            class="btn btn-warning btn-sm">Add Task</a>
                                    @endrole

                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="three-dots btn" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            &#8942;
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item btn btn-info btn-sm"
                                                    href="{{ route('project.milestones.show', ['milestone_ulid' => $milestone->ulid]) }}">View</a>
                                            </li>
                                            @hasanyrole('admin|personnel')
                                                <li><a class="dropdown-item btn btn-info btn-sm"
                                                        href="{{ route('project.milestones.edit', ['milestone_ulid' => $milestone->ulid]) }}">Edit</a>
                                                </li>
                                                <li>
                                                    <form
                                                        action="{{ route('project.milestones.delete', ['milestone_ulid' => $milestone->ulid]) }}"
                                                        method="POST" style="display:inline;"
                                                        onsubmit="return deleteConfirmation()">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="dropdown-item text-danger btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </li>
                                            @endrole
                                        </ul>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr colspan="3">
                                No project milestones registered!!!
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
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
