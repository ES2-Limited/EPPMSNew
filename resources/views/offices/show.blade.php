@extends('layouts.admin')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">

        @include('custom.subhead')

        <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
            <h6 class="mb-0 fs-4">Office Details</h6>

            <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                <a href="{{ route('offices.index') }}" style="color:white;">Go back</a>
            </span>
        </div>

        <div class="row">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Office Details</h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Office Name:</strong> {{ $office->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Location:</strong> {{ $office->lga?->state?->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Phone Number:</strong> {{ $office->phone }}
                        </li>
                        <li class="list-group-item">
                            <strong>Email:</strong> {{ $office->email }}
                        </li>
                        {{-- <li class="list-group-item">
                            <strong>Created By:</strong> {{ $office->creator?->name }}
                        </li> --}}
                        <li class="list-group-item">
                            <strong>Created:</strong> {{ $office->created_at->diffForHumans() }}
                        </li>
                    </ul>
                    <div class="mt-4 text-center">
                        <a href="{{ route('offices.edit', $office->ulid) }}" class="btn btn-primary">Edit Office Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('custom.customizer')
</div>
</div>
@endsection
