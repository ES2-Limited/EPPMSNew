@extends('layouts.admin')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Reports Generation Page</h6>
            </div>

            <div class="row">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Progress</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="itemTable">
                        @forelse ($projects as $project)
                            <tr>
                                <td>{{ $project->name }}</td>
                                <td>{{ $project->get_progress() }}%</td>
                                <td>{{ $project->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ url('admin/reports/generate/' . $project->ulid) }}"
                                        class="btn btn-info btn-sm">Generate
                                        Report</a>
                                </td>
                            </tr>
                            {{ $projects->links() }}
                        @empty
                            <tr colspan="3">
                                No projects completed for reporting!!!
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('custom.customizer')
    </div>
    </div>
@endsection
