@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Personnel Registration
                </h6>
            </div>
            @include('custom.subhead')
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <div>
                    <h6 class="mb-0 fs-4">Enter Information to create a Personnel account</h6>
                </div>
                <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                    <a href="{{ route('personnels.index') }}" style="color:white;">Go back</a>
                </span>
            </div>
            <div class="col-12">
                <div class="card">
                    @include('custom.form-response')
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('personnels.store') }}"
                        onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Saving...';">
                        @csrf
                        <div>
                            <div class="card-body">
                                <h4 class="card-title">Personnel Credentials</h4>
                                <div class="row pt-3">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">First Name</label>
                                            <input type="text" id="firstName" class="form-control form-control-danger"
                                                placeholder="First Name" name="first_name" value="{{ old('first_name') }}"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" id="lastName" class="form-control form-control-danger"
                                                placeholder="Last Name" name="last_name" value="{{ old('last_name') }}"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Other</label>
                                            <input type="text" id="other" class="form-control form-control-danger"
                                                placeholder="Other" name="other_name" value="{{ old('other_name') }}" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" id="email" class="form-control form-control-danger"
                                                placeholder="Johndoe@gmail.com" name="email" value="{{ old('email') }}"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" id="phoneNumber" class="form-control form-control-danger"
                                                placeholder="Phone Number" name="phone" value="{{ old('phone') }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Designation</label>
                                            <input type="text" id="designation" class="form-control form-control-danger"
                                                placeholder="Designation" name="designation"
                                                value="{{ old('designation') }}" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Personnel Role</label>
                                            <select class="form-select" id="role" name="role_id">
                                                <option value="" {{ old('role_id') ? '' : 'selected' }}>
                                                    Choose
                                                    Role</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                        {{ ucfirst(str_replace(['_', ' '], ' ', $role->name)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="directorate_name" class="form-label fw-bold">Directorate
                                                Name</label>
                                            <select class="form-select" id="directorate" name="directorate_id" required>
                                                <option selected>Choose Directorate</option>
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


                                    <div class="col-md-4" id="hide-department" style="display: block;">
                                        <div class="mb-3">
                                            <label class="form-label">Department</label>
                                            <select class="form-select" id="department" name="department_id">
                                                <option value="" {{ old('department_id') ? '' : 'selected' }}>Choose
                                                    Department</option>
                                            </select>
                                            <span id="department-error"
                                                style="color: red; font-size: 12px; display:none;">No departments available
                                                for this directorate</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="office_location" class="form-label">Office Location</label>
                                        <select id="office_location" name="office_id" class="form-select" required>
                                            <option value="" {{ old('office_id') ? '' : 'selected' }}>Select an
                                                office</option>
                                            @foreach ($offices as $office)
                                                <option value="{{ $office->id }}"
                                                    {{ old('office_id') == $office->id ? 'selected' : '' }}>
                                                    {{ $office->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="password" class="form-label">{{ __('Password') }}</label>
                                        <div class="input-group mb-3">
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="new-password">
                                            <span class="input-group-text" id="togglePassword">
                                                <i id="togglePasswordIcon" class="fas fa-eye"></i>
                                            </span>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
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
                <!-- end Person Info -->
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

            document.getElementById('togglePassword').addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const toggleIcon = document.getElementById('togglePasswordIcon');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                }
            });
        });
    </script>

@endsection
