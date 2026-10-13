@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            @include('custom.project-counter')
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-end justify-content-end mb-4">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <form method="GET" action="{{ route('projects.index') }}" class="d-flex gap-3 align-items-center mb-3">
                        <select name="directorate_id" class="form-select fw-bold" onchange="this.form.submit()">
                            <option value="">Filter by Directorate</option>
                            @foreach ($directorates as $directorate)
                                <option value="{{ $directorate->id }}"
                                    {{ request('directorate_id') == $directorate->id ? 'selected' : '' }}>
                                    {{ $directorate->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="office_id" class="form-select fw-bold" onchange="this.form.submit()">
                            <option value="">Filter by Office</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->id }}"
                                    {{ request('office_id') == $office->id ? 'selected' : '' }}>
                                    {{ $office->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">List of Projects</h6>
                <div>
                    @hasanyrole('admin|personnel')
                        @if ($projects->count() > 0)
                            <span class="badge text-bg-secondary rounded-4 px-3 py-1 lh-sm">
                                <a href="{{ route('project.histogram') }}" style="color:white;">Project Report</a>
                            </span>
                        @endif
                        <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                            <a href="{{ route('projects.create') }}" style="color:white;"> Add a Project</a>
                        </span>
                    @endrole
                </div>
            </div>



            <div class="row g-4 mb-5">
                @forelse ($projects as $project)
                    @php
                        $progress = $project->get_progress();
                        if ($progress < 30) {
                            $progressClass = 'bg-danger';
                            $statusBadgeClass = 'text-bg-danger';
                        } elseif ($progress < 70) {
                            $progressClass = 'bg-warning';
                            $statusBadgeClass = 'text-bg-warning';
                        } else {
                            $progressClass = 'bg-success';
                            $statusBadgeClass = 'text-bg-success';
                        }
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="card project-card h-100 shadow-sm border-0">
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="badge {{ $statusBadgeClass }} rounded-pill px-3 py-1">
                                            {{ Str::ucfirst($project->status?->value ?? 'Pending') }}
                                        </span>
                                        <small class="text-muted d-flex align-items-center gap-1">
                                            <i class="far fa-clock"></i> {{ $project->created_at->diffForHumans() }}
                                        </small>
                                    </div>

                                    <h5 class="fw-bold mb-2 text-dark">{{ $project->name }}</h5>
                                    
                                    <div class="d-flex align-items-center gap-3 mb-3 text-muted small">
                                        @if($project->office)
                                            <span><i class="fas fa-map-marker-alt me-1 text-primary"></i> {{ $project->office->name }}</span>
                                        @endif
                                        @if($project->cost)
                                            <span><i class="fas fa-coins me-1 text-success"></i> ₦{{ number_format($project->cost, 2) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small fw-semibold text-muted">Progress</span>
                                        <span class="small fw-bold text-dark">{{ $progress }}%</span>
                                    </div>
                                    <div class="progress mb-3" style="height: 8px;">
                                        <div class="progress-bar {{ $progressClass }}" role="progressbar"
                                            style="width: {{ $progress }}%;"
                                            aria-valuenow="{{ $progress }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                        <div class="d-flex gap-2">
                                            <span class="badge {{ $project->milestones?->count() > 0 ? 'text-bg-success' : 'text-bg-secondary' }} rounded-pill">
                                                <i class="fas fa-flag me-1"></i> {{ $project->milestones?->count() ?? 0 }} Milestones
                                            </span>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('projects.show', ['ulid' => $project->ulid]) }}"
                                                class="btn btn-sm btn-primary rounded-pill px-3">
                                                View
                                            </a>
                                            @hasanyrole('admin|personnel')
                                                <a href="{{ route('projects.edit', ['ulid' => $project->ulid]) }}"
                                                    class="btn btn-sm btn-outline-secondary rounded-pill px-2" title="Edit Project">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endrole
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info border-0 rounded-3 shadow-sm d-flex align-items-center gap-2" role="alert">
                            <i class="fas fa-info-circle fs-5"></i>
                            <span>No projects found matching the criteria.</span>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @include('custom.customizer')
    </div>
    </div>
@endsection
