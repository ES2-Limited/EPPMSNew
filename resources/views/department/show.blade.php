@extends('layouts.admin')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">

        @include('custom.subhead')

        <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
            <h6 class="mb-0 fs-4">Department Details</h6>

            <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                <a href="{{ route('departments.index') }}" style="color:white;">Go back</a>
            </span>
        </div>

        <div class="row">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Department Details</h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Directorate Name:</strong> {{ $department->directorate?->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Department Name:</strong> {{ $department->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Function:</strong> {{ $department->function }}
                        </li>
                        <li class="list-group-item">
                            <strong>Created:</strong> {{ $department->created_at->diffForHumans() }}
                        </li>
                    </ul>
                    @role('admin')
                    <div class="mt-4 text-center">
                        <a href="{{ route('departments.edit', $department->ulid) }}" class="btn btn-primary">Edit Department Details</a>
                    </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</div>
@include('custom.customizer')
</div>
</div>
@endsection
