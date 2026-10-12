@extends('layouts.admin')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">{{ $projectPersonnel->project?->name }} Personnel Details</h6>

                <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                    <a href="{{ url()->previous() }}" style="color:white;">Go back</a>
                </span>
            </div>

            <div class="row">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Personnel Details</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Full Name:</strong>
                                {{ $projectPersonnel->personnel?->user?->fullname }}
                            </li>
                            <li class="list-group-item">
                                <strong>Department Name:</strong> {{ $projectPersonnel->department?->name }}
                            </li>
                        </ul>

                        @role('admin')
                            <div class="mt-4 text-center">
                                <a href="{{ route('project.personnels.edit', $projectPersonnel->ulid) }}" class="btn btn-primary">Edit
                                    personnel Details</a>
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
