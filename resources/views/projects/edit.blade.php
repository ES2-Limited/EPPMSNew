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
                            @php
                                $enableDirectorates = (bool)setting('enable_directorates', true);
                                $enableDepartments = (bool)setting('enable_departments', true);
                                $enableUnits = (bool)setting('enable_units', true);
                                $primaryTier = setting('project_primary_tier', 'directorate');
                            @endphp

                            @if($enableDirectorates || $enableDepartments || $enableUnits)
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="fw-bold text-dark mb-1"><i class="ti ti-building me-2 text-primary"></i>Organizational Allocation</h5>
                                <p class="text-muted small">Update the governing organizational level responsible for this project.</p>

                                <div class="row g-3">
                                    @if($enableDirectorates)
                                        <div class="{{ $enableDepartments && $enableUnits ? 'col-md-4' : ($enableDepartments || $enableUnits ? 'col-md-6' : 'col-md-12') }}">
                                            <label class="form-label fw-semibold" for="directorate">
                                                Project Directorate 
                                                @if($primaryTier === 'directorate') <span class="text-danger">*</span> @endif
                                            </label>
                                            <select class="form-select" id="directorate" name="directorate_id" {{ $primaryTier === 'directorate' ? 'required' : '' }}>
                                                <option value="">Choose directorate</option>
                                                @foreach ($directorates as $directorate)
                                                    <option value="{{ $directorate->id }}"
                                                        {{ ($project->directorate_id == $directorate->id || $project->department?->directorate_id == $directorate->id || old('directorate_id') == $directorate->id) ? 'selected' : '' }}>
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
                                                <option value="">Choose Department</option>
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
                                    @endif

                                    @if($enableUnits)
                                        <div class="{{ $enableDirectorates && $enableDepartments ? 'col-md-4' : ($enableDirectorates || $enableDepartments ? 'col-md-6' : 'col-md-12') }}">
                                            <label class="form-label fw-semibold" for="unit">
                                                Project Unit
                                                @if($primaryTier === 'unit') <span class="text-danger">*</span> @endif
                                            </label>
                                            <select class="form-select" id="unit" name="unit_id" {{ $primaryTier === 'unit' ? 'required' : '' }}>
                                                <option value="">Choose Unit</option>
                                                @if(isset($units))
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}"
                                                            {{ (old('unit_id', $project->unit_id) == $unit->id) ? 'selected' : '' }}>
                                                            {{ $unit->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
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
                                <p class="text-muted small">Update project typology, priority level, primary contractor, and technical consultant.</p>

                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold" for="project_type">Project Type <span class="text-danger">*</span></label>
                                        <select class="form-select" id="project_type" name="project_type_id" required>
                                            <option value="" disabled>Choose Project Type</option>
                                            @if(isset($project_types))
                                                @foreach ($project_types as $project_type)
                                                    <option value="{{ $project_type->id }}"
                                                        {{ (old('project_type_id', $project->project_type_id) == $project_type->id) ? 'selected' : '' }}>
                                                        {{ $project_type->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('project_type_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold" for="priority">Priority Level <span class="text-danger">*</span></label>
                                        <select class="form-select" id="priority" name="priority" required>
                                            @php $currentPriority = old('priority', $project->priority ?? 'Medium'); @endphp
                                            <option value="Normal" {{ $currentPriority == 'Normal' ? 'selected' : '' }}>Normal / Standard</option>
                                            <option value="Low" {{ $currentPriority == 'Low' ? 'selected' : '' }}>Low Priority</option>
                                            <option value="Medium" {{ $currentPriority == 'Medium' ? 'selected' : '' }}>Medium Priority</option>
                                            <option value="High" {{ $currentPriority == 'High' ? 'selected' : '' }}>High Priority</option>
                                            <option value="Critical" {{ $currentPriority == 'Critical' ? 'selected' : '' }}>Critical / Urgent</option>
                                        </select>
                                        @error('priority')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
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

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold" for="consultant">Project Consultant</label>
                                        <select class="form-select" id="consultant" name="consultant_id">
                                            <option value="" {{ old('consultant_id', $project->consultant_id) ? '' : 'selected' }}>Choose consultant</option>
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
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold" for="fiscal_year">Fiscal / Budget Year</label>
                                        <select class="form-select" id="fiscal_year" name="fiscal_year_id">
                                            <option value="">-- Choose Fiscal Year --</option>
                                            @if(isset($fiscal_years))
                                                @foreach ($fiscal_years as $fy)
                                                    <option value="{{ $fy->id }}"
                                                        {{ (old('fiscal_year_id', $project->fiscal_year_id) == $fy->id) ? 'selected' : '' }}>
                                                        {{ $fy->year }} {{ $fy->description ? "({$fy->description})" : '' }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('fiscal_year_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold" for="funding_source">Funding Source</label>
                                        <select class="form-select" id="funding_source" name="funding_source_id">
                                            <option value="">-- Choose Funding Source --</option>
                                            @if(isset($funding_sources))
                                                @foreach ($funding_sources as $fs)
                                                    <option value="{{ $fs->id }}"
                                                        {{ (old('funding_source_id', $project->funding_source_id) == $fs->id) ? 'selected' : '' }}>
                                                        {{ $fs->name }} {{ $fs->code ? "({$fs->code})" : '' }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('funding_source_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold" for="budget_code">Budget Code / Subhead</label>
                                        <input type="text" class="form-control" id="budget_code" name="budget_code" 
                                               placeholder="e.g. NCAA-CAP-2025-0842" value="{{ old('budget_code', $project->budget_code) }}" />
                                        @error('budget_code')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="Projectcost">Contract Cost ({{ setting('currency_code', 'NGN') }}) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-muted fw-bold">{{ setting('currency_symbol', '₦') }}</span>
                                            <input id="Projectcost" name="cost" type="number" step="0.01" placeholder="0.00"
                                                class="form-control" value="{{ old('cost', $project->cost) }}" required />
                                        </div>
                                        @error('cost')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
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

                            @if(isset($custom_fields) && $custom_fields->count() > 0)
                                <!-- Section 5: Custom Configurable Attributes -->
                                <div class="mb-4 pb-3 border-bottom">
                                    <h5 class="fw-bold text-dark mb-1"><i class="ti ti-adjustments-alt me-2 text-primary"></i>Custom Attributes & Compliance</h5>
                                    <p class="text-muted small">Additional configurable parameters specific to this project.</p>

                                    <div class="row g-3">
                                        @foreach($custom_fields as $field)
                                            @php
                                                $val = old('custom_attributes.' . $field->slug, $project->custom_attributes[$field->slug] ?? '');
                                            @endphp
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold" for="cf_{{ $field->slug }}">
                                                    {{ $field->name }}
                                                    @if($field->is_required)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>

                                                @if($field->field_type === 'text')
                                                    <input type="text" class="form-control" id="cf_{{ $field->slug }}" 
                                                        name="custom_attributes[{{ $field->slug }}]" 
                                                        value="{{ $val }}"
                                                        {{ $field->is_required ? 'required' : '' }}>
                                                @elseif($field->field_type === 'number')
                                                    <input type="number" step="any" class="form-control" id="cf_{{ $field->slug }}" 
                                                        name="custom_attributes[{{ $field->slug }}]" 
                                                        value="{{ $val }}"
                                                        {{ $field->is_required ? 'required' : '' }}>
                                                @elseif($field->field_type === 'date')
                                                    <input type="date" class="form-control" id="cf_{{ $field->slug }}" 
                                                        name="custom_attributes[{{ $field->slug }}]" 
                                                        value="{{ $val }}"
                                                        {{ $field->is_required ? 'required' : '' }}>
                                                @elseif($field->field_type === 'select')
                                                    <select class="form-select" id="cf_{{ $field->slug }}" 
                                                        name="custom_attributes[{{ $field->slug }}]"
                                                        {{ $field->is_required ? 'required' : '' }}>
                                                        <option value="">-- Select {{ $field->name }} --</option>
                                                        @foreach(explode(',', $field->options ?? '') as $opt)
                                                            @php $opt = trim($opt); @endphp
                                                            @if(!empty($opt))
                                                                <option value="{{ $opt }}" {{ $val == $opt ? 'selected' : '' }}>
                                                                    {{ $opt }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @elseif($field->field_type === 'textarea')
                                                    <textarea class="form-control" rows="3" id="cf_{{ $field->slug }}" 
                                                        name="custom_attributes[{{ $field->slug }}]"
                                                        {{ $field->is_required ? 'required' : '' }}>{{ $val }}</textarea>
                                                @endif

                                                @error('custom_attributes.' . $field->slug)
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

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

            $('#department').on('change', function() {
                let department = $("#department").val();
                fetchUnits(department);
            });

            function fetchUnits(department) {
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
