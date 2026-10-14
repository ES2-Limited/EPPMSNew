@extends('layouts.admin')
@section('custom-css')
    <style>
        .fixed-size {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }

        p span {
            background-color: transparent !important;
        }

        .project-details-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .status-badge {
            background-color: #f0f0f0;
            color: #333;
            padding: 10px;
            border-radius: 10px;
            font-size: 0.8rem;
        }

        .project-detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            justify-content: space-between;
            color: #121212 !important;
        }

        .project-detail-item i,
        .project-detail-item-title i {
            margin-right: 10px;
        }

        .project-detail-item-title {
            display: flex;
            align-items: center;
        }

        .team-avatars img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: -10px;
            border: 2px solid white;
        }

        .add-member {
            padding: 10px 15px;
            border-radius: 5px;
            border: #b7bbff 1.5px solid;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border: #e5e6e8 1px solid;
            border-radius: 5px;
            margin-bottom: 10px;
            width: 42%;
        }

        .project-gallery img {
            max-width: 100%;
            border-radius: 8px;
        }

        .time-box {
            background-color: #fde8e8;
            color: black;
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .time-box svg {
            width: 16px;
            height: 16px;
        }

        .add-image {
            color: #1d3ebf;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .add-image svg {
            width: 16px;
            height: 16px;
        }

        .custom-tabs {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            position: relative;
        }

        .custom-tabs .nav-link {
            color: black;
            font-weight: 500;
            padding: 10px 20px;
            border: none;
        }

        .custom-tabs .nav-link.active {
            border-bottom: 2px solid black;
            font-weight: bold;
        }

        .custom-tabs::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #ddd;
        }

        .comment-box {
            background-color: #f7f7f7;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .comment-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .comment-user {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .comment-user img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        .publish-btn {
            background-color: #b7bbff;
            color: black;
        }

        .publish-btn:hover {
            background-color: #6c63ff;
            color: black;
        }

        .vertical-hr {
            width: 1px;
            height: 100vh;
            border: none;
            background-color: #f2f2f2;
        }

        @media (max-width: 768px) {

            .vertical-hr,
            .member {
                display: none;
            }

            .attachment-item {
                width: 100%;
            }

            .project-details-header {
                text-align: center;
                color: #121212 !important;
                font-weight: 900;
            }

            .gallery-images,
            .downloadfile {
                flex-direction: column;
            }

            .custom-tabs .nav-link {
                padding: 10px 10px;
            }
        }

        .text-dark-custom {
            color: #121212 !important;
        }
    </style>
@endsection
@section('content')
    <div class="body-wrapper">

        <div class="container-fluid" style="font-size: 1.0rem;">

            @include('custom.project-counter')

            <div class="row rounded-2 p-4" style="background-color: white">
                <div class="col-lg-6">

                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ url()->previous() }}" class="text-decoration-none me-3 text-primary">
                            <iconify-icon icon="solar:alt-arrow-left-bold" class="fs-2"></iconify-icon>
                            <span>Back</span>
                        </a>

                    </div>

                    <div class="project-details-hero rounded-3 mb-4 p-4 text-white" style="background: linear-gradient(135deg, #0b1f66 0%, #1e40af 100%);">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                {!! status_badge($project->status) !!}
                                {!! priority_badge($project->priority) !!}
                                @if($project->project_type)
                                    <span class="badge bg-white bg-opacity-25 text-white border border-light border-opacity-25 px-2 py-1">
                                        <i class="fas fa-folder me-1"></i> {{ $project->project_type->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                @hasanyrole('admin|personnel')
                                    @if ($project->get_progress() == 100)
                                        <a href="{{ url('admin/report/project/' . $project->ulid) }}"
                                            class="btn btn-sm btn-light text-primary rounded-pill px-3 fw-semibold">
                                            <i class="fas fa-file-pdf me-1"></i> Report
                                        </a>
                                    @endif
                                    <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                                        All Projects
                                    </a>
                                @endrole
                            </div>
                        </div>

                        <h3 class="fw-bold mb-2 text-white">{{ $project->name }}</h3>
                        <p class="text-white-50 small mb-3">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $project->office?->name ?? 'No location set' }} &bull;
                            <i class="fas fa-calendar-alt ms-2 me-1"></i> Awarded {{ $project->award_date ?? 'N/A' }}
                        </p>

                        <div class="row g-2 mt-2">
                            <div class="col-4">
                                <div class="bg-white bg-opacity-10 rounded-2 p-2 text-center">
                                    <small class="text-white-50 d-block" style="font-size: 0.75rem;">Total Budget</small>
                                    <span class="fw-bold fs-6 text-white">{{ format_currency($project->cost) }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-white bg-opacity-10 rounded-2 p-2 text-center">
                                    <small class="text-white-50 d-block" style="font-size: 0.75rem;">Total Paid</small>
                                    <span class="fw-bold fs-6 text-white">{{ format_currency($project->total_paid) }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-white bg-opacity-10 rounded-2 p-2 text-center">
                                    <small class="text-white-50 d-block" style="font-size: 0.75rem;">Remaining</small>
                                    <span class="fw-bold fs-6 text-white">{{ format_currency($project->total_left) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="project-details mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0 fw-bold fs-5 text-dark">Project Information</h6>
                        </div>

                        <div class="list-group list-group-flush border rounded-3 mb-4 shadow-sm">
                            @if($project->project_type)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted"><i class="fas fa-folder me-2 text-info"></i> Project Type</span>
                                <span class="fw-semibold text-dark">{{ $project->project_type->name }}</span>
                            </div>
                            @endif
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted"><i class="fas fa-building me-2 text-primary"></i> Directorate</span>
                                <span class="fw-semibold text-dark">{{ $project->directorate?->name ?? $project->department?->directorate?->name ?? 'None' }}</span>
                            </div>
                            @if($project->department)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted"><i class="fas fa-sitemap me-2 text-secondary"></i> Department</span>
                                <span class="fw-semibold text-dark">{{ $project->department->name }}</span>
                            </div>
                            @endif
                            @if($project->unit)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted"><i class="fas fa-puzzle-piece me-2 text-info"></i> Unit</span>
                                <span class="fw-semibold text-dark">{{ $project->unit->name }}</span>
                            </div>
                            @endif
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted"><i class="fas fa-user-check me-2 text-success"></i> Contractor Firm</span>
                                <span class="fw-semibold text-dark">{{ $project->contractor?->user?->name ?? 'Unassigned' }}</span>
                            </div>
                            @if($project->fiscal_year)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted"><i class="fas fa-calendar-alt me-2 text-primary"></i> Fiscal Year</span>
                                <span class="fw-semibold text-dark">{{ $project->fiscal_year->year }}</span>
                            </div>
                            @endif
                            @if($project->funding_source)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted"><i class="fas fa-money-bill-wave me-2 text-success"></i> Funding Source</span>
                                <span class="fw-semibold text-dark">{{ $project->funding_source->name }}</span>
                            </div>
                            @endif
                            @if($project->budget_code)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted"><i class="fas fa-barcode me-2 text-secondary"></i> Budget Code</span>
                                <span class="fw-semibold font-monospace text-dark">{{ $project->budget_code }}</span>
                            </div>
                            @endif
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted"><i class="fas fa-hourglass-half me-2 text-warning"></i> Duration</span>
                                <span class="fw-semibold text-dark">{{ $project->duration }} {{ $project->duration_period }}</span>
                            </div>
                            @php
                                $customFieldsList = \App\Models\CustomField::where('model_type', 'Project')->where('is_active', true)->orderBy('order')->get();
                            @endphp
                            @if($customFieldsList->count() > 0 && !empty($project->custom_attributes))
                                @foreach($customFieldsList as $cf)
                                    @if(isset($project->custom_attributes[$cf->slug]) && $project->custom_attributes[$cf->slug] !== '')
                                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                            <span class="text-muted"><i class="fas fa-tag me-2 text-info"></i> {{ $cf->name }}</span>
                                            <span class="fw-semibold text-dark">{{ is_array($project->custom_attributes[$cf->slug]) ? implode(', ', $project->custom_attributes[$cf->slug]) : $project->custom_attributes[$cf->slug] }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div
                            style="
                                    background-color: #f7f7f7;
                                    color: black;
                                    padding: 20px;
                                    border-radius: 10px;
                                  ">
                            <div class="project-detail-item-title">
                                <i class="fas fa-file-alt"></i>
                                <span>Description</span>
                            </div>
                            <p class="mt-2">
                                {{ $project->description }}
                            </p>
                        </div>

                        <div class="mt-3">
                            <div
                                style="
                                      display: flex;
                                      justify-content: space-between;
                                      margin-bottom: 20px;
                                    ">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-paperclip"></i>
                                    <span>Attachments (1)</span>
                                </div>
                            </div>
                            <div class="downloadfile"
                                style="
                                      display: flex;
                                      justify-content: space-between;
                                      align-items: center;
                                    ">
                                <div class="attachment-item">
                                    <i class="fas fa-file-download me-4"></i>
                                    <div style="display: flex; flex-direction: column">
                                        @if ($project->award_letter != '')
                                            @php
                                                $filePath = asset('storage/project_files/' . $project->award_letter);
                                            @endphp

                                            <a href="{{ $filePath }}" target="_blank">
                                                <img src="{{ asset('/assets/images/file-icon.png') }}" alt="File"
                                                    width="100" class="img-fluid">
                                            </a>
                                        @else
                                            <img src="{{ asset('/assets/images/file-icon.png') }}" alt="File"
                                                width="100" class="img-fluid">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div>
                            <div class="d-flex">
                                <hr class="vertical-hr me-4" />
                                <div class="d-flex flex-column w-100">
                                    <div class="container mt-4">
                                        <div class="d-flex justify-content-end align-items-center">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-muted text-dark-custom" style="font-size: 14px">Time
                                                    left</span>
                                                <div class="time-box">
                                                    <i class="fas fa-clock"></i> {{ $project->time_left }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Project Gallery Section -->
                                        <div class="d-flex justify-content-between align-items-center my-4">
                                            <h6 class="m-0">Project gallery</h6>
                                        </div>
                                    </div>

                                    <!-- Project Gallery -->
                                    <div class="project-gallery mb-4">
                                        <div class="gallery-images d-flex justify-content-between gap-2">
                                            <div class="row">
                                                @foreach ($recentImages as $image)
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card shadow-sm">
                                                            <img src="{{ asset('storage/task_images/' . $image->name) }}"
                                                                class="fixed-size img-fluid" alt="Task Image">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tabs -->
                                    <ul class="nav custom-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="milestones-tab" data-bs-toggle="tab"
                                                data-bs-target="#milestones" type="button" role="tab">
                                                Milestones
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="comments-tab" data-bs-toggle="tab"
                                                data-bs-target="#comments" type="button" role="tab">
                                                Comments
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="team-tab" data-bs-toggle="tab"
                                                data-bs-target="#team" type="button" role="tab">
                                                Personnels
                                            </button>
                                        </li>
                                    </ul>

                                    <!-- Tab Content -->
                                    <div class="tab-content mt-3 text-dark-custom" id="myTabContent">
                                        <div class="tab-pane fade" id="milestones" role="tabpanel">
                                            <p><span class="fw-semibold">Milestones Completed:</span> <span
                                                    class="fw-bolder">{{ $project->milestone_completion[0] }}</span></p>
                                            <p><span class="fw-semibold">Milestones Left:</span>
                                                <span class="fw-bolder">{{ $project->milestone_completion[1] }}</span>
                                            </p>
                                            @role('admin|user|contractor|head_of_department|consultant|organization_admin|directorate_admin')
                                                <p>
                                                    <a href="{{ route('project.milestones', ['project_ulid' => $project->ulid]) }}"
                                                        class="btn btn-outline-success flex-grow-1">View Project Milestones</a>
                                                </p>
                                            @endrole
                                        </div>
                                        <div class="tab-pane fade show active" id="comments" role="tabpanel">
                                            <!-- Comment Input -->
                                            <div class="comment-box">
                                                <p><b>💬 Comment</b></p>

                                                <div class="mt-2 text-center">
                                                    @if (session('chat-success'))
                                                        <div class="alert alert-success">
                                                            {{ session('chat-success') }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <form action="{{ route('project.chat.send', $project->ulid) }}"
                                                    method="POST" class="mt-3">
                                                    @csrf

                                                    <textarea class="form-control" placeholder="Enter your comment here"
                                                        style="
                                                border: none;
                                                background-color: transparent;
                                              "
                                                        name="message" required></textarea>
                                                    <div class="d-flex align-items-center mt-2 justify-content-end">
                                                        <i class="fas fa-paperclip"></i>
                                                        <button class="btn publish-btn ms-3">
                                                            Publish
                                                        </button>
                                                </form>
                                            </div>
                                        </div>
                                        <hr />

                                        <!-- Comments List -->
                                        @foreach ($messages as $message)
                                            <div class="comment-section mt-3">
                                                <div class="comment-header">
                                                    <div class="comment-user">
                                                        <img src="{{ asset('assets/images/profile/user-1.jpg') }}"
                                                            alt="User" />
                                                        <b>{{ $message->sender->name }}:</b>
                                                    </div>
                                                    <small>{{ $message->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p>{{ $message->message }}</p>
                                            </div>
                                        @endforeach
                                        <hr />
                                    </div>
                                    <div class="tab-pane fade" id="team" role="tabpanel">
                                        <p><span class="fw-semibold">Project Personnels:</span>
                                            <span
                                                class="fw-bolder">{{ $project->project_personnel?->count() ?? 0 }}</span>
                                        </p>
                                        @role('admin')
                                            <p>
                                                <a href="{{ route('project.personnels', ['project_ulid' => $project->ulid]) }}"
                                                    class="btn btn-outline-primary flex-grow-1">View Project Personnels</a>
                                            </p>
                                        @endrole
                                    </div>
                                </div>
                            </div>
                        </div>
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
