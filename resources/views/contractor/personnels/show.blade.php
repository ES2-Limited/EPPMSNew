@extends('layouts.admin')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
            <h6 class="mb-0 fs-4">Contractor Personnel Details</h6>

            <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                <a href="{{ route('contractor-personnels.index') }}" style="color: white">
                    All Contractor Personnels</a>
            </span>
        </div>
        <div class="row">
            <div class="col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Basic Information</h4>
                    </div>
                    <div class="card-body">

                        <p><strong>Name:</strong>{{ $contractor_personnel->user?->first_name .' '. $contractor_personnel->user?->last_name }}</p>
                        <p><strong>Email:</strong> {{ $contractor_personnel->user?->email }}</p>
                        <p><strong>Phone:</strong> {{ $contractor_personnel->user?->phone }}</p>
                        <p><strong>Joined On:</strong> {{ $contractor_personnel->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Contractor Details</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $contractor = $contractor_personnel->contractor?->user;
                        @endphp
                        <p><strong>Name:</strong> {{ $contractor?->name }}</p>
                        <p><strong>Email:</strong> {{ $contractor?->email }}</p>
                        <p><strong>Phone:</strong> {{ $contractor_personnel->contractor?->phone }}</p>
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
