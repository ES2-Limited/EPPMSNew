@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4"> {{ $project->name }} Project Personnels Page
                </h6>
            </div>
            <div class="col-12">
                <div class="card">
                    <form method="POST" action="{{ route('project.personnels.store', ['project_ulid' => $project->ulid]) }}"
                        onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Saving...';">
                        @csrf
                        <div>
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
                                <h4 class="mb-3 fs-4">Add Project Personnels</h4>
                                <input id="ulid" name="project_ulid" type="hidden"
                                    value="{{ $project->ulid ?? '' }}" />


                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Personnel Directorate</label>
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

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Personnel Department</label>
                                            <select class="form-select" id="department" name="department_id" required>
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


                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Project Personnel</label>
                                            <select class="form-select" id="personnel" name="personnel_id">
                                                <option selected>Personnel</option>
                                            </select>
                                        </div>
                                    </div>

                                    @include('custom.role', ['roles' => $roles])

                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="card-body border-top">
                                <button type="submit" class="btn btn-primary" id="submit-button">
                                    Save
                                </button>
                            </div>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    @include('custom.customizer')
    </div>
    </div>
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
                $('#personnel').children('option:not(:first)').remove();
                $.ajax({
                    type: "GET",
                    url: "/get-department-personnels",
                    data: {
                        department_id: department
                    }
                }).done(function(data) {
                    $.each(data, function(index, item) {
                        $("#personnel").append(`
                     <option value="${item.id}">${item.user.first_name} ${item.user.last_name}</option>
                     `);
                    });
                });
            });
        });
    </script>
@endsection
