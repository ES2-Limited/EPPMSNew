@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            @include('custom.subhead')
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Personnel Details</h6>

                <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                    <a href="{{ route('personnels.index') }}" style="color:white;">Go back</a>
                </span>
            </div>

            <div class="row">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Personnel Details</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Full Name:</strong>
                                {{ $personnel->user?->first_name . ' ' . $personnel->user?->last_name }}
                            </li>
                            <li class="list-group-item">
                                <strong>Department Name:</strong> {{ $personnel->unit?->department?->name }}
                            </li>
                            <li class="list-group-item">
                                <strong>Unit Name:</strong> {{ $personnel->unit?->name }}
                            </li>
                            <li class="list-group-item">
                                <strong>Created:</strong> {{ $personnel->created_at->diffForHumans() }}
                            </li>
                        </ul>

                        @role('admin')
                            <div class="mt-4 text-center">
                                <a href="{{ route('personnels.edit', $personnel->ulid) }}" class="btn btn-primary">Edit
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
