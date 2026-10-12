@extends('layouts.admin')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Office Registration
                </h6>

            </div>

            @include('custom.subhead')

            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('offices.index') }}" class="text-decoration-none me-3 text-primary">
                    <iconify-icon icon="solar:alt-arrow-left-bold" class="fs-2"></iconify-icon>
                    <span>Offices Report</span>
                </a>
            </div>
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <div>
                    <h6 class="mb-0 fs-4">Create an Office Information here</h6>
                </div>
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
                    <form method="POST" action="{{ route('offices.store') }}"
                        onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Saving...';">
                        @csrf
                        <div>
                            <div class="card-body">
                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" id="name" class="form-control form-control-danger"
                                                placeholder="Office Name" name="name" value="{{ old('name') }}" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Type of office Location</label>
                                            <select class="form-select" id="office_type" name="office_type_id" required>
                                                <option value="" {{ old('office_type_id') ? '' : 'selected' }}>
                                                    --SELECT Office Type--</option>
                                                @foreach ($officeTypes as $type)
                                                    <option value="{{ $type->id }}"
                                                        {{ old('office_type_id') == $type->id ? 'selected' : '' }}>
                                                        {{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mb-3" id="conditionalDropdownDiv" style="display: none;">
                                            <label for="conditionalDropdown" class="form-label">Outstation Region</label>
                                            <select id="conditionalDropdown" class="form-select" name="office_region_id">
                                                <option value="">-- Choose an Option --</option>
                                                @foreach ($regions as $region)
                                                    <option value="{{ $region->id }}"
                                                        {{ old('office_region_id') == $region->id ? 'selected' : '' }}>
                                                        {{ $region->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" id="address" class="form-control form-control-danger"
                                            placeholder="Address" name="address" value="{{ old('address') }}" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">State</label>
                                            <select class="form-select" id="state" name="state_id" require>
                                                <option value="" {{ old('state_id') ? '' : 'selected' }}>--SELECT
                                                    Office State--</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}"
                                                        {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                                        {{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">L.G.A.</label>
                                            <select class="form-select" id="lgas" name="lga_id">
                                                <option value="">--SELECT--</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" id="email" class="form-control form-control-danger"
                                                placeholder="ncaa@gov.ng" name="email" value="{{ old('email') }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" id="phoneNumber" class="form-control form-control-danger"
                                                placeholder="Phone Number" name="phone" value="{{ old('phone') }}" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col text-end">
                                        <button type="submit" class="btn btn-primary" id="submit-button">
                                            Save
                                        </button>
                                    </div>
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
            $("#state").change(() => {
                let state = $("#state").val();
                $('#lgas').children('option:not(:first)').remove();
                $.ajax({
                    type: "GET",
                    url: "/lgas",
                    data: {
                        state_id: state
                    }
                }).done(function(data) {
                    $.each(data, function(index, item) {
                        $("#lgas").append(`
                     <option value="${item.id}">${item.name}</option>
                     `);
                    });
                });
            });
        });

        document.getElementById("office_type").addEventListener("change", function() {
            let dropdownDiv = document.getElementById("conditionalDropdownDiv");
            let conditionalDropdown = document.getElementById("conditionalDropdown");

            if (this.value === "3") {
                dropdownDiv.style.display = "block";
                conditionalDropdown.setAttribute("required", "required");
            } else {
                dropdownDiv.style.display = "none";
                conditionalDropdown.removeAttribute("required");
                conditionalDropdown.value = "";
            }
        });
    </script>

@endsection
