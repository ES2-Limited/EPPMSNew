@extends('layouts.admin')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">

        @include('custom.subhead')

        <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
            <h6 class="mb-0 fs-4">Directorate Details</h6>

            <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                <a href="{{ route('directorates.index') }}" style="color:white;">Go back</a>
            </span>
        </div>

        <div class="row">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Directorate Details</h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Directorate Name:</strong> {{ $directorate->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Function:</strong> {{ $directorate->function }}
                        </li>
                        {{-- <li class="list-group-item">
                            <strong>Created By:</strong> {{ $directorate->creator?->name }}
                        </li> --}}
                        <li class="list-group-item">
                            <strong>Created:</strong> {{ $directorate->created_at->diffForHumans() }}
                        </li>
                    </ul>
                    <div class="mt-4 text-center">
                        <a href="{{ route('directorates.edit', $directorate->ulid) }}" class="btn btn-primary">Edit Directorate Details</a>
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
