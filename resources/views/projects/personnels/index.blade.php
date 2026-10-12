@extends('layouts.admin')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            @hasanyrole('admin')
                @include('custom.project-counter')
            @endrole

            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">{{ $project->name }} Project Personnels</h6>

                <div>
                    @if ($project_personnels->count() > 0)
                        <span class="badge text-bg-secondary rounded-4 px-3 py-1 lh-sm">
                            <a href="{{ route('records.print', ['ulid' => $project->ulid]) }}" style="color:white;">Print
                                Report</a>
                        </span>
                    @endif

                    <span class="badge text-bg-success rounded-4 px-3 py-1 lh-sm">
                        <a href="{{ route('projects.show', ['ulid' => $project->ulid]) }}" style="color: white">
                            View Project</a>
                    </span>
                    <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                        <a href="{{ route('project.personnels.create', ['project_ulid' => $project->ulid]) }}"
                            style="color: white">
                            Add Project Personnel</a>
                    </span>
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
                <table class="table">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Project Personnel</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="itemTable">
                        @forelse ($project_personnels as $personnel)
                            <tr>
                                <td>{{ $personnel->project?->name }}</td>
                                <td>{{ $personnel->personnel?->user?->first_name . ' ' . $personnel->personnel?->user?->last_name }}
                                </td>
                                <td>
                                    @include('custom.action', [
                                        'url' => 'admin/project-personnels/' . $personnel->ulid,
                                    ])
                                </td>
                            </tr>
                        @empty
                            <tr colspan="3">
                                No contractor personnels registered!!!
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $project_personnels->links() }}
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

    </script>
@endsection
