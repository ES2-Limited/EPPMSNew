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

                    <div class="project-details mb-4">
                        <div class="project-details-header">
                            <h4 class="flex-grow-1">
                                {{ $project->name }}
                            </h4>
                        </div>


                        <div class="d-flex align-items-center gap-6 mb-2 pb-3 justify-content-between">
                            <h6 class="mb-0 fs-4 ">Project details</h6>

                            <div>
                                @hasanyrole('admin|personnel')
                                    @if ($project->get_progress() == 100)
                                        <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                                            <a href="{{ url('admin/report/project/' . $project->ulid) }}"
                                                style="color:white;">Project
                                                Report</a>
                                        </span>
                                    @endif

                                    <span class="badge text-bg-success rounded-4 px-3 py-1 lh-sm">
                                        <a href="{{ route('projects.index') }}" style="color:white;">All Projects</a>
                                    </span>
                                @endrole
                            </div>
                        </div>
                        <div class="project-detail-item text-dark-custom">
                            <div class="project-detail-item-title">
                                <i class="fas fa-sync-alt"></i> <span>Status:</span>
                            </div>
                            <span class="status-badge"
                                style="background-color: #fcffb8">{{ Str::ucfirst($project->status?->value) }}</span>
                        </div>

                        <div class="project-detail-item">
                            <div class="project-detail-item-title">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Priority</span>
                            </div>
                            <span class="ms-2">{{ $project->priority ?? 'None' }}</span>
                        </div>


                        <div class="project-detail-item">
                            <div class="project-detail-item-title">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Total Paid</span>
                            </div>
                            <span class="ms-2">{{ number_format($project->total_paid, 2) }}</span>
                        </div>


                        <div class="project-detail-item">
                            <div class="project-detail-item-title">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Total Left</span>
                            </div>
                            <span class="ms-2">{{ number_format($project->total_left, 2) }}</span>
                        </div>



                        <div class="project-detail-item">
                            <div class="project-detail-item-title">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Location</span>
                            </div>
                            <span class="ms-2">{{ $project->office?->name }}</span>
                        </div>

                        <div class="project-detail-item">
                            <div class="project-detail-item-title">
                                <i class="fas fa-briefcase"></i>
                                <span>Directorate</span>
                            </div>
                            <span class="ms-2">{{ $project->directorate?->name ??  $project->department?->directorate?->name }}</span>
                        </div>

                        <div class="project-detail-item">
                            <div class="project-detail-item-title">
                                <i class="fas fa-user-check"></i>
                                <span>Contractor</span>
                            </div>
                            <span class="ms-2">{{ $project->contractor?->user?->name }}</span>
                        </div>

                        <div class="project-detail-item">
                            <div class="project-detail-item-title">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Award date</span>
                            </div>
                            <span class="ms-2">{{ $project->award_date }}</span>
                        </div>

                        <div class="project-detail-item">
                            <div class="project-detail-item-title">
                                <i class="fas fa-clock"></i>
                                <span>Duration</span>
                            </div>
                            <span class="ms-2">{{ $project->duration }} -
                                {{ $project->duration_period }}</span>
                        </div>

                        <div class="project-detail-item">
                            <div class="project-detail-item-title">
                                <i class="fas fa-credit-card"></i>
                                <span>Cost</span>
                            </div>
                            <span class="ms-2">{{ number_format($project->cost, 2) }}</span>
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
