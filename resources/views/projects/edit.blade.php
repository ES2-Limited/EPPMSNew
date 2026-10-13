@extends('layouts.admin')
@section('custom-css')
    <style>
        .loading-icon {
            width: 50px;
            height: 50px;
        }

        #loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
        }
    </style>
@endsection
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: var(--eppms-radius-lg);">
                    <div class="card-header border-bottom bg-white py-3 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-uppercase fw-bold text-primary" style="font-size: 0.75rem; letter-spacing: 0.05em;">Projects / Edit</span>
                            <h4 class="fw-bold mb-0 text-dark">Update Project: {{ $project->name }}</h4>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('projects.show', $project->ulid) }}" class="btn btn-outline-primary btn-sm">
                                <i class="ti ti-eye me-1"></i> View Project
                            </a>
                            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="ti ti-arrow-left me-1"></i> Projects List
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success m-4 mb-0 d-flex align-items-center">
                            <i class="ti ti-check fs-5 me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger m-4 mb-0 d-flex align-items-center">
                            <i class="ti ti-alert-circle fs-5 me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger m-4 mb-0">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('projects.update', $project->ulid) }}" method="post"
                            enctype="multipart/form-data"
                            onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Saving...';">
                            @csrf
                            @method('PUT')

                            <!-- Section 1: Overview -->
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold text-dark mb-1"><i class="ti ti-file-description me-2 text-primary"></i>General Information</h5>
                                <p class="text-muted small">Update the project's official designation and scope.</p>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="name">Project Name <span class="text-danger">*</span></label>
                                    <input id="name" name="name" type="text" placeholder="Project Name"
                                        class="form-control" value="{{ old('name', $project->name) }}" required />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="description">Project Description</label>
                                    <textarea id="description" name="description" placeholder="Project Description" class="form-control" rows="4">{{ old('description', $project->description) }}</textarea>
                                </div>
                            </div>

                            <!-- Section 2: Department & Structure -->
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold text-dark mb-1"><i class="ti ti-building me-2 text-primary"></i>Organizational Allocation</h5>
                                <p class="text-muted small">Select the governing directorate and department.</p>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="directorate">Directorate Name <span class="text-danger">*</span></label>
                                        <select class="form-select" id="directorate" name="directorate_id" required>
                                            <option value="" disabled>Choose directorate</option>
                                            @foreach ($directorates as $directorate)
                                                <option value="{{ $directorate->id }}"
                                                    {{ ($project->department?->directorate?->id == $directorate->id || old('directorate_id') == $directorate->id) ? 'selected' : '' }}>
                                                    {{ $directorate->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="department">Project Department <span class="text-danger">*</span></label>
                                        <select class="form-select" id="department" name="department_id" required>
                                            <option value="" disabled>Choose Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}"
                                                    {{ (old('department_id', $project->department_id) == $department->id) ? 'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('department_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                        <span id="department-error" class="text-danger small" style="display:none;">
                                            No departments available for this directorate
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Partners -->
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold text-dark mb-1"><i class="ti ti-users me-2 text-primary"></i>Contractor & Consultant</h5>
                                <p class="text-muted small">Update assigned project executors and oversight partners.</p>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="contractor">Project Contractor <span class="text-danger">*</span></label>
                                        <select class="form-select" id="contractor" name="contractor_id" required>
                                            <option value="" disabled>Choose Contractor</option>
                                            @foreach ($contractors as $contractor)
                                                <option value="{{ $contractor->id }}"
                                                    {{ (old('contractor_id', $project->contractor_id) == $contractor->id) ? 'selected' : '' }}>
                                                    {{ $contractor->user?->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('contractor_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="consultant">Project Consultant</label>
                                        <select class="form-select" id="consultant" name="consultant_id">
                                            <option value="" disabled {{ old('consultant_id', $project->consultant_id) ? '' : 'selected' }}>Choose consultant</option>
                                            @foreach ($consultants as $consultant)
                                                <option value="{{ $consultant->id }}"
                                                    {{ (old('consultant_id', $project->consultant_id) == $consultant->id) ? 'selected' : '' }}>
                                                    {{ $consultant->user?->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('consultant_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Section 4: Cost & Schedule -->
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold text-dark mb-1"><i class="ti ti-coin me-2 text-primary"></i>Financials, Location & Timeline</h5>
                                <p class="text-muted small">Review and adjust contract valuation, award dates, and milestones.</p>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="Projectcost">Contract Cost (NGN)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-muted fw-bold">₦</span>
                                            <input id="Projectcost" name="cost" type="number" step="0.01" placeholder="0.00"
                                                class="form-control" value="{{ old('cost', $project->cost) }}" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="office">Project Location / Office <span class="text-danger">*</span></label>
                                        <select class="form-select" id="office" name="office_id" required>
                                            <option value="" disabled>-- SELECT Office --</option>
                                            @foreach ($offices as $office)
                                                <option value="{{ $office->id }}"
                                                    {{ (old('office_id', $project->office_id) == $office->id) ? 'selected' : '' }}>
                                                    {{ $office->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('office_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="awardDate">Award Date</label>
                                        <input type="date" class="form-control" id="awardDate" name="award_date"
                                            value="{{ old('award_date', $project->award_date) }}" />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Project Duration <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input id="count" name="duration" type="number" placeholder="0" class="form-control" min="0"
                                                value="{{ old('duration', $project->duration) }}" required />
                                            <select class="form-select" id="duration" name="duration_period" style="max-width: 130px;" required>
                                                <option value="days" {{ old('duration_period', $project->duration_period) == 'days' ? 'selected' : '' }}>Days</option>
                                                <option value="weeks" {{ old('duration_period', $project->duration_period) == 'weeks' ? 'selected' : '' }}>Weeks</option>
                                                <option value="months" {{ old('duration_period', $project->duration_period) == 'months' ? 'selected' : '' }}>Months</option>
                                                <option value="years" {{ old('duration_period', $project->duration_period) == 'years' ? 'selected' : '' }}>Years</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <label class="form-label fw-semibold">Update Award Letter</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="flex-grow-1">
                                                <input type="file" class="form-control" name="award_letter"
                                                    accept=".jpeg,.jpg,.png,.gif,.pdf" />
                                                <small class="text-muted">Leave blank to retain existing award document.</small>
                                            </div>
                                            @if ($project->award_letter)
                                                <a href="{{ asset('projects/' . $project->award_letter) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary d-inline-flex align-items-center">
                                                    <i class="ti ti-file me-1"></i> Current Document
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3 pt-2">
                                <button type="submit" id="submit-button" class="btn btn-primary px-4 py-2">
                                    <i class="ti ti-check me-1"></i> Update Project
                                </button>
                                <a href="{{ route('projects.show', $project->ulid) }}" class="btn btn-light px-4 py-2 text-muted">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                    <div id="loading" style="display:none;">Loading...</div>
                </div>
            </div>
        </div>
    </div>
    </div>

    @include('custom.customizer')
    </div>
    </div>
@section('custom-script')
    <script>
        $(document).ready(function() {
            $('#directorate').on('change', function() {
                let directorate = $("#directorate").val();
                fetchDepartments(directorate);
            });

            const selectedDirectorate = $('#directorate').val();
            if (selectedDirectorate) {
                fetchDepartments(selectedDirectorate);
            }

            function fetchDepartments(directorate) {
                const hide_department_errror = document.getElementById('department-error');
                $('#department').children('option:not(:first)').remove();
                $.ajax({
                    type: "GET",
                    url: "/get-departments",
                    data: {
                        directorate_id: directorate
                    }
                }).done(function(data) {

                    if (data.length === 0) {
                        hide_department_errror.style.display = 'inline-block';
                        $("#department").prop("disabled", true);
                    } else {
                        $("#department").prop("disabled", false);
                        hide_department_errror.style.display = 'none';
                        $.each(data, function(index, item) {
                            $("#department").append(`
                 <option value="${item.id}" ${directorate == item.id ? 'selected' : '' }>${item.name}</option>`);
                        });
                    }
                }).fail(function() {
                    $("#department").append(`<option value="">Error loading departments</option>`);
                    $("#department").prop("disabled", true);
                });
            }


            $("#contractor").change(() => {
                let contractor = $("#contractor").val();
                $('#contract_personnel').children('option:not(:first)').remove();
                $.ajax({
                    type: "GET",
                    url: "/get-contractor-personnels",
                    data: {
                        contractor_id: contractor
                    }
                }).done(function(data) {
                    $.each(data, function(index, item) {
                        $("#contract_personnel").append(`
                     <option value="${item.id}">${item.user?.first_name} ${item.user?.last_name}</option>
                     `);
                    });
                });
            });

        });
    </script>
@endsection
@endsection
