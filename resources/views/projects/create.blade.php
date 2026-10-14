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
                            <span class="text-uppercase fw-bold text-primary" style="font-size: 0.75rem; letter-spacing: 0.05em;">Projects</span>
                            <h4 class="fw-bold mb-0 text-dark">Register New Project</h4>
                        </div>
                        <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ti ti-arrow-left me-1"></i> Back to Projects
                        </a>
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
                        <form action="{{ route('projects.store') }}" method="post"
                            enctype="multipart/form-data"
                            onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Saving...';">
                            @csrf

                            <!-- Section 1: Overview -->
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold text-dark mb-1"><i class="ti ti-file-description me-2 text-primary"></i>General Information</h5>
                                <p class="text-muted small">Provide the title, summary, and scope of this capital initiative.</p>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="name">Project Name <span class="text-danger">*</span></label>
                                    <input id="name" name="name" type="text" placeholder="e.g. Modernization of Primary Radar Infrastructure"
                                        class="form-control" value="{{ old('name') }}" required />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="description">Project Description</label>
                                    <textarea id="description" name="description" placeholder="Provide background, deliverables, and intended outcomes..." class="form-control" rows="4">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <!-- Section 2: Department & Structure -->
                            @php
                                $enableDirectorates = (bool)setting('enable_directorates', true);
                                $enableDepartments = (bool)setting('enable_departments', true);
                                $enableUnits = (bool)setting('enable_units', true);
                                $primaryTier = setting('project_primary_tier', 'directorate');
                            @endphp

                            @if($enableDirectorates || $enableDepartments || $enableUnits)
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold text-dark mb-1"><i class="ti ti-building me-2 text-primary"></i>Organizational Allocation</h5>
                                <p class="text-muted small">Select the governing structural level responsible for project ownership.</p>

                                <div class="row g-3">
                                    @if($enableDirectorates)
                                        <div class="{{ $enableDepartments && $enableUnits ? 'col-md-4' : ($enableDepartments || $enableUnits ? 'col-md-6' : 'col-md-12') }}">
                                            <label class="form-label fw-semibold" for="directorate">
                                                Project Directorate 
                                                @if($primaryTier === 'directorate') <span class="text-danger">*</span> @endif
                                            </label>
                                            <select class="form-select" id="directorate" name="directorate_id" {{ $primaryTier === 'directorate' ? 'required' : '' }}>
                                                <option value="" {{ old('directorate_id') ? '' : 'selected' }}>Choose directorate</option>
                                                @foreach ($directorates as $directorate)
                                                    <option value="{{ $directorate->id }}" {{ old('directorate_id') == $directorate->id ? 'selected' : '' }}>
                                                        {{ $directorate->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('directorate_id')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif

                                    @if($enableDepartments)
                                        <div class="{{ $enableDirectorates && $enableUnits ? 'col-md-4' : ($enableDirectorates || $enableUnits ? 'col-md-6' : 'col-md-12') }}">
                                            <label class="form-label fw-semibold" for="department">
                                                Project Department
                                                @if($primaryTier === 'department') <span class="text-danger">*</span> @endif
                                            </label>
                                            <select class="form-select" id="department" name="department_id" {{ $primaryTier === 'department' ? 'required' : '' }}>
                                                <option value="" {{ old('department_id') ? '' : 'selected' }}>Choose department</option>
                                                @if(isset($departments))
                                                    @foreach($departments as $dept)
                                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('department_id')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                            <span id="department-error" class="text-danger small" style="display:none;">
                                                No departments available for this directorate
                                            </span>
                                        </div>
                                    @endif

                                    @if($enableUnits)
                                        <div class="{{ $enableDirectorates && $enableDepartments ? 'col-md-4' : ($enableDirectorates || $enableDepartments ? 'col-md-6' : 'col-md-12') }}">
                                            <label class="form-label fw-semibold" for="unit">
                                                Project Unit
                                                @if($primaryTier === 'unit') <span class="text-danger">*</span> @endif
                                            </label>
                                            <select class="form-select" id="unit" name="unit_id" {{ $primaryTier === 'unit' ? 'required' : '' }}>
                                                <option value="" {{ old('unit_id') ? '' : 'selected' }}>Choose unit</option>
                                            </select>
                                            @error('unit_id')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                            <span id="unit-error" class="text-danger small" style="display:none;">
                                                No units available for this department
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Section 3: Classification & Stakeholders -->
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold text-dark mb-1"><i class="ti ti-users me-2 text-primary"></i>Classification & Partners</h5>
                                <p class="text-muted small">Specify project typology, primary contractor, and technical consultant.</p>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold" for="project_type">Project Type <span class="text-danger">*</span></label>
                                        <select class="form-select" id="project_type" name="project_type_id" required>
                                            <option value="" disabled {{ old('project_type_id') ? '' : 'selected' }}>Choose Project Type</option>
                                            @foreach ($project_types as $project_type)
                                                <option value="{{ $project_type->id }}" {{ old('project_type_id') == $project_type->id ? 'selected' : '' }}>
                                                    {{ $project_type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('project_type_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold" for="contractor">Project Contractor <span class="text-danger">*</span></label>
                                        <select class="form-select" id="contractor" name="contractor_id" required>
                                            <option value="" disabled {{ old('contractor_id') ? '' : 'selected' }}>Choose Contractor</option>
                                            @foreach ($contractors as $contractor)
                                                <option value="{{ $contractor->id }}" {{ old('contractor_id') == $contractor->id ? 'selected' : '' }}>
                                                    {{ $contractor->user?->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('contractor_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold" for="consultant">Project Consultant</label>
                                        <select class="form-select" id="consultant" name="consultant_id">
                                            <option value="" disabled {{ old('consultant_id') ? '' : 'selected' }}>Choose consultant</option>
                                            @foreach ($consultants as $consultant)
                                                <option value="{{ $consultant->id }}" {{ old('consultant_id') == $consultant->id ? 'selected' : '' }}>
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

                            <!-- Section 4: Financials & Timeline -->
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold text-dark mb-1"><i class="ti ti-coin me-2 text-primary"></i>Financials, Location & Timeline</h5>
                                <p class="text-muted small">Specify contracted cost, operational office location, award date, and delivery timeframe.</p>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="Projectcost">Contract Cost (NGN)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-muted fw-bold">₦</span>
                                            <input id="Projectcost" name="cost" type="number" step="0.01" placeholder="0.00"
                                                class="form-control" value="{{ old('cost') }}" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="office">Project Location / Office <span class="text-danger">*</span></label>
                                        <select class="form-select" id="office" name="office_id" required>
                                            <option value="" disabled {{ old('office_id') ? '' : 'selected' }}>-- SELECT Office --</option>
                                            @foreach ($offices as $office)
                                                <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
                                                    {{ $office->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('office_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="awardDate">Award Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="awardDate" name="award_date" value="{{ old('award_date') }}" required />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Project Duration <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input id="count" name="duration" type="number" placeholder="0" class="form-control" min="0" value="{{ old('duration') }}" required />
                                            <select class="form-select" id="duration" name="duration_period" style="max-width: 130px;" required>
                                                <option value="days" {{ old('duration_period') == 'days' ? 'selected' : '' }}>Days</option>
                                                <option value="weeks" {{ old('duration_period') == 'weeks' ? 'selected' : '' }}>Weeks</option>
                                                <option value="months" {{ old('duration_period') == 'months' ? 'selected' : '' }}>Months</option>
                                                <option value="years" {{ old('duration_period') == 'years' ? 'selected' : '' }}>Years</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <label class="form-label fw-semibold">Upload Award Letter</label>
                                        <input type="file" class="form-control" name="award_letter" accept=".jpeg,.jpg,.png,.gif,.pdf" />
                                        <small class="text-muted">Accepted formats: PDF, JPEG, PNG</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3 pt-2">
                                <button type="submit" id="submit-button" class="btn btn-primary px-4 py-2">
                                    <i class="ti ti-check me-1"></i> Create Project
                                </button>
                                <a href="{{ route('projects.index') }}" class="btn btn-light px-4 py-2 text-muted">
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
            $("#directorate").change(() => {
                let directorate = $("#directorate").val();
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
                        hide_department_errror.style.display = 'none';
                        $("#department").prop("disabled", false);
                        $.each(data, function(index, item) {
                            $("#department").append(
                                `<option value="${item.id}">${item.name}</option>`);
                        });
                    }
                }).fail(function() {
                    $("#department").append(`<option value="">Error loading departments</option>`);
                    $("#department").prop("disabled", true);
                });
            });

            $("#department").change(() => {
                let department = $("#department").val();
                const unit_error = document.getElementById('unit-error');
                $('#unit').children('option:not(:first)').remove();
                if (!department) return;

                $.ajax({
                    type: "GET",
                    url: "/get-units",
                    data: {
                        department_id: department
                    }
                }).done(function(data) {
                    if (!data || data.length === 0) {
                        if (unit_error) unit_error.style.display = 'inline-block';
                    } else {
                        if (unit_error) unit_error.style.display = 'none';
                        $.each(data, function(index, item) {
                            $("#unit").append(`<option value="${item.id}">${item.name}</option>`);
                        });
                    }
                });
            });


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
