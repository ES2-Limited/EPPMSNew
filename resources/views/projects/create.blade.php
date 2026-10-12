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
                <div class="card">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card-body">
                        <h4 class="card-title mb-0">Project </h4>
                        <h6 class="card-subtitle mb-3"></h6>
                        <h3>Project Details</h3>
                        <section>
                            <form action="{{ route('projects.store') }}" class="mt-5" method="post"
                                enctype="multipart/form-data"
                                onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Saving...';">
                                @csrf
                                <div>
                                    <div class="mb-3">
                                        <label class="form-label" for="Project">Project Name</label>
                                        <input id="name" name="name" type="text" placeholder="Project Name"
                                            class=" form-control mb-3" value="{{ old('name') }}" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="Project">Project Description</label>
                                        <textarea id="description" name="description" placeholder="Project Description" class="form-control" rows="3"
                                            placeholder="Message">{{ old('description') }} </textarea>
                                    </div>

                                    <div class="row pt-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Project Directorate</label>
                                                <select class="form-select" id="directorate" name="directorate_id" required>
                                                    <option value="" disabled
                                                        {{ old('directorate_id') ? '' : 'selected' }}>Choose directorate
                                                    </option>
                                                    @foreach ($directorates as $directorate)
                                                        <option value="{{ $directorate->id }}"
                                                            {{ old('directorate_id') == $directorate->id ? 'selected' : '' }}>
                                                            {{ $directorate->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('directorate_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Project Department</label>
                                                <select class="form-select" id="department" name="department_id">
                                                    <option value="" disabled
                                                        {{ old('department_id') ? '' : 'selected' }}>
                                                        department</option>
                                                </select>
                                                @error('department_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <span id="department-error"
                                                    style="color: red; font-size: 12px; display:none;">No departments
                                                    available
                                                    for this directorate</span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row pt-3">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Project Type</label>
                                                <select class="form-select" id="project_type" name="project_type_id"
                                                    required>
                                                    <option value="" disabled
                                                        {{ old('project_type_id') ? '' : 'selected' }}>Choose Project Type
                                                    </option>
                                                    @foreach ($project_types as $project_type)
                                                        <option value="{{ $project_type->id }}"
                                                            {{ old('project_type_id') == $project_type->id ? 'selected' : '' }}>
                                                            {{ $project_type->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('project_type_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Project Contractor</label>
                                                <select class="form-select" id="contractor" name="contractor_id" required>
                                                    <option value="" disabled
                                                        {{ old('contractor_id') ? '' : 'selected' }}>Choose Contractor
                                                    </option>
                                                    @foreach ($contractors as $contractor)
                                                        <option value="{{ $contractor->id }}"
                                                            {{ old('contractor_id') == $contractor->id ? 'selected' : '' }}>
                                                            {{ $contractor->user?->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('contractor_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Project Consultant</label>
                                                <select class="form-select" id="consultant" name="consultant_id">
                                                    <option value="" disabled
                                                        {{ old('consultant_id') ? '' : 'selected' }}>Choose consultant
                                                    </option>
                                                    @foreach ($consultants as $consultant)
                                                        <option value="{{ $consultant->id }}"
                                                            {{ old('consultant_id') == $consultant->id ? 'selected' : '' }}>
                                                            {{ $consultant->user?->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('consultant_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row pt-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="Project">Project Cost</label>
                                                <input id="Projectcost" name="cost" type="number" placeholder="0.00"
                                                    class=" form-control mb-3" value="{{ old('cost') }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="Project">Project Location</label>
                                                <select class="form-select" id="office" name="office_id" required>
                                                    <option value="" disabled
                                                        {{ old('office_id') ? '' : 'selected' }}>--SELECT Office office--
                                                    </option>
                                                    @foreach ($offices as $office)
                                                        <option value="{{ $office->id }}"
                                                            {{ old('office_id') == $office->id ? 'selected' : '' }}>
                                                            {{ $office->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('office_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row pt-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="awardDate">Award Date</label>
                                                <input type="date" class="form-control" id="awardDate"
                                                    name="award_date" value="{{ old('award_date') }}" required />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="row pt-3">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Project Duration</label>
                                                        <input id="count" name="duration" type="number"
                                                            placeholder="0" class="form-control mb-3" min="0"
                                                            value="{{ old('duration') }}" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="Duration">Duration By</label>
                                                        <select class="form-select" id="duration" name="duration_period"
                                                            required>
                                                            <option value="days"
                                                                {{ old('duration_period') == 'days' ? 'selected' : '' }}>
                                                                Days</option>
                                                            <option value="weeks"
                                                                {{ old('duration_period') == 'weeks' ? 'selected' : '' }}>
                                                                Weeks</option>
                                                            <option value="months"
                                                                {{ old('duration_period') == 'months' ? 'selected' : '' }}>
                                                                Months</option>
                                                            <option value="years"
                                                                {{ old('duration_period') == 'years' ? 'selected' : '' }}>
                                                                Years</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row pt-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Upload Award Letter</label>
                                            <input type="file" class="form-control" name="award_letter"
                                                accept=".jpeg,.jpg,.png,.gif,.pdf" />
                                        </div>
                                    </div>
                                    <div class="row pt-3">
                                        <div class="form-actions">
                                            <button type="submit" id="submit-button" class="btn btn-primary"
                                                name=""> Create </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </section>
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
