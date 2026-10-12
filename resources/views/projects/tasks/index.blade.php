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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Cost</th>
                            <th>Start Date</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="itemTable">
                        @forelse ($tasks as $task)
                            <tr>
                                <td>{{ $task->name }}</td>
                                <td>{{ $task->cost }}</td>
                                <td>{{ $task->start_date }}</td>
                                <td>{{ Str::ucfirst($task->status?->value) }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="three-dots btn" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            &#8942;
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ route('milestone.tasks.show', ['task_ulid' => $task->ulid]) }}"
                                                    class="dropdown-item btn btn-info btn-sm">View</a>
                                            </li>
                                            @hasanyrole('admin')
                                                <li> <a href="{{ route('milestone.tasks.edit', ['task_ulid' => $task->ulid]) }}"
                                                        class="dropdown-item btn btn-info btn-sm">Edit</a>
                                                </li>
                                                <li>
                                                    <form
                                                        action="{{ route('milestone.tasks.delete', ['task_ulid' => $task->ulid]) }}"
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
                            <tr colspan="4">
                                No milestone tasks added!!!
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
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
