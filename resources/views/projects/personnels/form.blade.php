@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                 <h6 class="mb-4 fs-4">{{ $project->name }} - Project Personnel</h6>
                <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                    <a href="{{ url()->previous() }}" style="color:white;">Go back</a>
                </span>
            </div>
            @php
                $isEdit = isset($projectPersonnel);
                $formAction = $isEdit
                    ? route('project.personnels.update', $projectPersonnel->ulid)
                    : route('project.personnels.store', ['project_ulid' => $project->ulid]);
                $formMethod = $isEdit ? 'PUT' : 'POST';
            @endphp

            <form method="POST" action="{{ $formAction }}"
                onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = '{{ $isEdit ? 'Updating...' : 'Saving...' }}';">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <input type="hidden" name="project_ulid" value="{{ $project->ulid }}" />

                <div class="card-body">
                    <h4 class="mb-3 fs-4">{{ $isEdit ? 'Edit' : 'Add' }} Project Personnel</h4>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Personnel Directorate</label>
                                <select class="form-select" id="directorate" name="directorate_id" required>
                                    <option value="" disabled
                                        {{ old('directorate_id', $isEdit ? $projectPersonnel->department?->directorate_id : '') ? '' : 'selected' }}>
                                        Choose directorate</option>
                                    @foreach ($directorates as $directorate)
                                        <option value="{{ $directorate->id }}"
                                            {{ old('directorate_id', $isEdit ? $projectPersonnel->directorate_id : '') == $directorate->id ? 'selected' : '' }}>
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
                                    <option value="" disabled selected>department</option>
                                </select>
                                @error('department_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <span id="department-error" class="text-danger small" style="display:none;">No departments
                                    available for this directorate</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Project Personnel</label>
                                <select class="form-select" id="personnel" name="personnel_id">
                                    <option selected>Personnel</option>
                                </select>
                                @error('personnel_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @include('custom.role', [
                            'roles' => $roles,
                            'selectedRole' => old('role_id', $isEdit ? $projectPersonnel->role_id : ''),
                        ])
                    </div>
                </div>

                <div class="form-actions">
                    <div class="card-body border-top">
                        <button type="submit" class="btn btn-primary" id="submit-button">
                            {{ $isEdit ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </div>
            </form>

            <script>
                $(document).ready(function() {
                    let selectedDirectorate =
                        '{{ old('directorate_id', $isEdit ? $projectPersonnel->department?->directorate_id : '') }}';
                    let selectedDepartment = '{{ old('department_id', $isEdit ? $projectPersonnel->department_id : '') }}';
                    let selectedPersonnel = '{{ old('personnel_id', $isEdit ? $projectPersonnel->personnel_id : '') }}';

                    function loadDepartments(directorateId) {
                        $('#department').children('option:not(:first)').remove();
                        $.ajax({
                            type: "GET",
                            url: "/get-departments",
                            data: {
                                directorate_id: directorateId
                            }
                        }).done(function(data) {
                            if (data.length === 0) {
                                $('#department-error').show();
                                $("#department").prop("disabled", true);
                            } else {
                                $('#department-error').hide();
                                $("#department").prop("disabled", false);
                                $.each(data, function(index, item) {
                                    $('#department').append(
                                        `<option value="${item.id}" ${item.id == selectedDepartment ? 'selected' : ''}>${item.name}</option>`
                                    );
                                });
                            }
                        });
                    }

                    function loadPersonnels(departmentId) {
                        $('#personnel').children('option:not(:first)').remove();
                        $.ajax({
                            type: "GET",
                            url: "/get-department-personnels",
                            data: {
                                department_id: departmentId
                            }
                        }).done(function(data) {
                            $.each(data, function(index, item) {
                                $('#personnel').append(
                                    `<option value="${item.id}" ${item.id == selectedPersonnel ? 'selected' : ''}>${item.user.first_name} ${item.user.last_name}</option>`
                                );
                            });
                        });
                    }

                    if (selectedDirectorate) {
                        loadDepartments(selectedDirectorate);
                    }

                    if (selectedDepartment) {
                        loadPersonnels(selectedDepartment);
                    }

                    $("#directorate").change(function() {
                        loadDepartments($(this).val());
                    });

                    $("#department").change(function() {
                        loadPersonnels($(this).val());
                    });
                });
            </script>
        </div>
    </div>
@endsection
