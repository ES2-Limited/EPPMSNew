@extends('reports.layout')
@section('title', 'Organization Report')
@section('content')
    {{-- <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-end no-print">
        <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-md no-print">
            <a href="{{ route('organizations.index') }}" style="color: white; text-decoration:none;">
                Back to organization</a>
        </span>
    </div> --}}

    <div class="text-center letterhead">
        <img src="{{ asset('/assets/images/logos/ncaalogo.png') }}" alt="Logo" width="100">
        <h2 class="mt-2">{{ $organization->name }}</h2>
        <p class="mb-0"> {{ $organization->address1 }}</p>
        <p class="info"><strong>Created On:</strong> {{ $organization->created_at->diffForHumans() }}</p>
        <p>Email: {{ $organization->email }} | Phone: {{ $organization->contact_no ?? 'No contact NO.' }} </p>
    </div>

    <div class="mb-4">
        <p class="info"><strong>Org. Name:</strong> {{ $organization->name }}</p>
        <p class="info"><strong>Org. Email:</strong> {{ $organization->email }}</p>
        <p class="info"><strong>Org. Website:</strong> {{ $organization->website }}</p>
        <p><strong>Print Date:</strong> <span id="currentDate"></span></p>
    </div>

    <div class="footer">
        <p>{{ $organization->name }} | {{ $organization->website }}</p>
    </div>
@endsection
