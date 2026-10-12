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
                            <form action="{{ route('projects.update', $project->ulid) }}" class="mt-5" method="post"
                                enctype="multipart/form-data"
                                onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Saving...';">
                                @csrf
                                @method('PUT')

                                <div>
                                    <div class="mb-3">
                                        <label class="form-label" for="Project">Project Name</label>
                                        <input id="name" name="name" type="text" placeholder="Project Name"
                                            class=" form-control mb-3" value="{{ old('name', $project->name) }}" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="Project">Project Description</label>
                                        <textarea id="description" name="description" placeholder="Project Description" class="form-control" rows="3"
                                            placeholder="Message">{{ old('description', $project->description) }} </textarea>
                                    </div>

                                    <div class="row pt-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Directorate Name</label>
                                                <select class="form-select" id="directorate" name="directorate_id" required>
                                                    <option selected>Choose directorate</option>
                                                    @foreach ($directorates as $directorate)
                                                        <option value="{{ $directorate->id }}"
                                                            {{ $project->department?->directorate?->id == $directorate->id ? 'selected' : '' }}>
                                                            {{ $directorate->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Project Department</label>
                                                <select class="form-select" id="department" name="department_id" required>
                                                    <option value="" disabled
                                                        {{ old('department_id') ? '' : 'selected' }}>Choose Department
                                                    </option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}"
                                                            {{ $project->department_id == $department->id ? 'selected' : '' }}>
                                                            {{ $department->name }}</option>
                                                    @endforeach
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
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Project Contractor</label>
                                                <select class="form-select" id="contractor" name="contractor_id" required>
                                                    <option value="" disabled
                                                        {{ old('contractor_id') ? '' : 'selected' }}>Choose Contractor
                                                    </option>
                                                    @foreach ($contractors as $contractor)
                                                        <option value="{{ $contractor->id }}"
                                                            {{ $project->contractor_id == $contractor->id ? 'selected' : '' }}>
                                                            {{ $contractor->user?->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('contractor_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Project Consultant</label>
                                                <select class="form-select" id="consultant" name="consultant_id">
                                                    <option value="" disabled
                                                        {{ old('consultant_id') ? '' : 'selected' }}>Choose consultant
                                                    </option>
                                                    @foreach ($consultants as $consultant)
                                                        <option value="{{ $consultant->id }}"
                                                            {{ $project->consultant_id == $consultant->id ? 'selected' : '' }}>
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
                                                    class=" form-control mb-3" value="{{ old('cost', $project->cost) }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="Project">Project Location</label>
                                                <select class="form-select" id="office" name="office_id" required>
                                                    <option value="" disabled
                                                        {{ old('office_id') ? '' : 'selected' }}>--SELECT Office office--
                                                    </option>
                                                    {{ $project->office_id }}
                                                    @foreach ($offices as $office)
                                                        <option value="{{ $office->id }}"
                                                            {{ $project->office_id == $office->id ? 'selected' : '' }}>
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
                                                <input type="date" class="form-control" id="awardDate" name="award_date"
                                                    value="{{ old('award_date', $project->award_date) }}" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="row pt-3">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Project Duration</label>
                                                        <input id="count" name="duration" type="number"
                                                            placeholder="0" class="form-control mb-3" min="0"
                                                            value="{{ old('duration', $project->duration) }}" required />
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="Duration">Duration By</label>
                                                        <select class="form-select" id="duration" name="duration_period"
                                                            required>
                                                            <option value="days"
                                                                {{ $project->duration_period == 'days' ? 'selected' : '' }}>
                                                                Days</option>
                                                            <option value="weeks"
                                                                {{ $project->duration_period == 'weeks' ? 'selected' : '' }}>
                                                                Weeks</option>
                                                            <option value="months"
                                                                {{ $project->duration_period == 'months' ? 'selected' : '' }}>
                                                                Months</option>
                                                            <option value="years"
                                                                {{ $project->duration_period == 'years' ? 'selected' : '' }}>
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
                                        <div class="col-md-3">

                                            @if ($project->award_letter != '')
                                                @php
                                                    $filePath = asset('projects/' . $project->award_letter);
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
                                    <div class="form-actions">
                                        <div class="card-body border-top">
                                            <button type="submit" class="btn btn-primary" id="submit-button">
                                                Update
                                            </button>
                                            <button type="reset" class="btn bg-danger-subtle text-danger ms-6">
                                                Cancel
                                            </button>
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
