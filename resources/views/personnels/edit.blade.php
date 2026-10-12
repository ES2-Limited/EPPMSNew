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
                    <h6 class="mb-0 fs-4">Personnel Information</h6>
                    <p class="mb-0 fs-4">Update the Personnel Information here</p>
                </div>
                <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                    <a href="{{ route('personnels.index') }}" style="color:white;">Go back</a>
                </span>
            </div>

            <div class="col-12">
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
                <div class="card">
                    <form method="POST" action="{{ route('personnels.update', $personnel->ulid) }}"
                        onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Updating...';">
                        @csrf
                        @method('PUT')
                        <div>
                            <div class="card-body">
                                <h4 class="card-title">Personnel Credentials</h4>
                                <div class="row pt-3">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">First Name</label>
                                            <input type="text" id="firstName" class="form-control form-control-danger"
                                                value="{{ old('first_name', $personnel->user?->first_name) }}"
                                                placeholder="First Name" name="first_name" required />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" id="lastName" class="form-control form-control-danger"
                                                placeholder="Last Name" name="last_name"
                                                value="{{ old('last_name', $personnel->user?->last_name) }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Other</label>
                                            <input type="text" id="other" class="form-control form-control-danger"
                                                placeholder="Other" name="other_name"
                                                value="{{ old('other_name', $personnel->user?->other_name) }}" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" id="email" class="form-control form-control-danger"
                                                placeholder="Johndoe@gmail.com"
                                                value="{{ old('email', $personnel->user?->email) }}" name="email" required
                                                disabled />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" id="phoneNumber" class="form-control form-control-danger"
                                                placeholder="Phone Number" name="phone"
                                                value="{{ old('phone', $personnel->phone) }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Designation</label>
                                            <input type="text" id="designation" class="form-control form-control-danger"
                                                placeholder="Designation" name="designation"
                                                value="{{ old('designation', $personnel->designation) }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Department</label>
                                            <select class="form-select" id="department" name="department_id">
                                                <option selected>Choose Department</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ $personnel->department_id == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4">
                                    <div class="mb-3">
                                <input type="hidden" value="{{ $personnel->unit_id }}" id="unitId">
                                        <label class="form-label">Unit</label>
                                        <select class="form-select" id="unit" name="unit_id">
                                            <option selected>Unit</option>
                                        </select>
                                    </div>
                                </div> --}}
                                    <div class="col-md-4">
                                        <label for="office_location" class="form-label">Office Location</label>
                                        <select id="office_location" name="office_id" class="form-select">
                                            <option value="" disabled selected>Select an office</option>
                                            @foreach ($offices as $office)
                                                <option value="{{ $office->id }}"
                                                    {{ $personnel->office_id == $office->id ? 'selected' : '' }}>
                                                    {{ $office->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                </div>
                <!-- end Person Info -->
            </div>
        </div>
    </div>

    @include('custom.customizer')
    </div>
    </div>
    <script>
        // $(document).ready(function() {
        //     $('#department').on('change', function() {
        //         let department = $("#department").val();
        //         fetchUnits(department);
        //     });

        //     const selectedDepartment = $('#department').val();
        //     if (selectedDepartment) {
        //         fetchUnits(selectedDepartment);
        //     }

        //     function fetchUnits(department) {
        //         const unitId = $('#unitId').val();
        //         $('#unit').children('option:not(:first)').remove();
        //         $.ajax({
        //             type: "GET",
        //             url: "/get-units",
        //             data: {
        //                 department_id: department
        //             }
        //         }).done(function(data) {
        //             $.each(data, function(index, item) {
        //                 $("#unit").append(`
    //                  <option value="${item.id}" ${unitId == item.id ? 'selected' : '' }>${item.name}</option>`);
        //             });
        //         });
        //     }
        // });
    </script>

@endsection
