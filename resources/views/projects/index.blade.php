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



            <div class="row">
                @forelse ($projects as $project)
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-md-6 col-lg-12">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center gap-6 mb-6 pb-3  justify-content-between">
                                            <p class="mb-0 fw-medium">Created {{ $project->created_at->diffForHumans() }}
                                            </p>

                                            <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                                                <a href="{{ route('projects.show', ['ulid' => $project->ulid]) }}"
                                                    style="color:white;"> View</a>
                                            </span>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between mb-6">
                                            <h6 class="mb-0 fw-medium">{{ $project->name }}</h6>
                                            <h6 class="mb-0 fw-medium">{{ $project->get_progress() }}%</h6>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between mb-6">
                                            <h6 class="mb-0 fw-medium">Status</h6>
                                            <h6 class="mb-0 fw-medium">{{ Str::ucfirst($project->status?->value) }} </h6>
                                        </div>

                                        @php
                                            $progress = $project->get_progress();
                                            if ($progress < 30) {
                                                $progressClass = 'bg-danger';
                                            } elseif ($progress < 60) {
                                                $progressClass = 'bg-warning';
                                            } else {
                                                $progressClass = 'bg-success';
                                            }
                                        @endphp

                                        <div class="progress mb-2" style="height: 20px; max-width: 600px; margin: 0 auto;">
                                            <div class="progress-bar {{ $progressClass }}" role="progressbar"
                                                style="width: {{ $project->get_progress() }}%;"
                                                aria-valuenow="{{ $project->get_progress() }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                                {{ $project->get_progress() }}%
                                            </div>
                                        </div>


                                        <div class="d-flex align-items-center gap-6 mb-6 pb-3  justify-content-between">
                                            <h6 class="mb-0 fs-4"> Milestone </h6>
                                            <p class="mb-0 fw-medium"> <a href="#" style="color:green;">
                                                    {{ $project->milestones?->count() > 0 ? 'Added' : 'Not Added' }}</a>
                                            </p>
                                        </div>

                                        <div class="d-flex align-items-center gap-6 mb-6 pb-3  justify-content-between">
                                            <h6 class="mb-0 fs-4"> Task </h6>
                                            <p class="mb-0 fw-medium"> <a href="#" style="color:red;">
                                                    {{ $project->milestones->contains(function ($milestone) {return !empty($milestone['tasks']);})? 'Added': 'Not Added' }}</a>
                                            </p>
                                        </div>

                                        @hasanyrole('admin|personnel')
                                            <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                                                <a href="{{ route('projects.edit', ['ulid' => $project->ulid]) }}"
                                                    style="color:white;"> Edit</a>
                                            </span>
                                        @endrole

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-lg-3">
                        <div class="row">
                            <div class="col-md-6 col-lg-12">
                                <div class="card  overflow-hidden shadow-none">
                                    <div class="card-body p-3">
                                        No projects created!
                                    </div>
                                </div>
                            </div>
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
