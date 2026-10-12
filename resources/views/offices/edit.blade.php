@extends('layouts.admin')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Edit Office Details
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
                    <h6 class="mb-0 fs-4">Update Office Information</h6>
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
                    <form action="{{ route('offices.update', $office->ulid) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div>
                            <div class="card-body">
                                <div class="row pt-3">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" id="name" class="form-control form-control-danger"
                                                value="{{ old('name', $office->name) }}" placeholder="office name"
                                                name="name" />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Type of office Location</label>
                                            <select class="form-select" id="office_type" name="office_type_id" require>
                                                <option value="">--SELECT Office Type--</option>
                                                @foreach ($officeTypes as $type)
                                                    <option value="{{ $type->id }}"
                                                        {{ $office->office_type_id == $type->id ? 'selected' : '' }}>
                                                        {{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" id="address" class="form-control form-control-danger"
                                            placeholder="Address" value="{{ old('address', $office->address) }}"
                                            name="address" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">State</label>
                                            <select class="form-select" id="state" name="state_id" require>
                                                <option value="">--SELECT Office State--</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}"
                                                        {{ $office->lga?->state?->id == $state->id ? 'selected' : '' }}>
                                                        {{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{ $office->lga_id }}" id="lgaId">
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
                                                value="{{ old('email', $office->email) }}" placeholder="ncaa@gov.ng"
                                                name="email" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" id="phoneNumber" class="form-control form-control-danger"
                                                placeholder="Phone Number" value="{{ old('phone', $office->phone) }}"
                                                name="phone" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="card-body border-top">
                                    <button type="submit" class="btn btn-primary">
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
        $(document).ready(function() {

            $('#state').on('change', function() {
                const stateId = $(this).val();
                fetchLgas(stateId);
            });

            const selectedState = $('#state').val();
            if (selectedState) {
                fetchLgas(selectedState);
            }

            function fetchLgas(stateId) {
                const lgaId = $('#lgaId').val();
                $('#lgas').children('option:not(:first)').remove();
                $.ajax({
                    type: "GET",
                    url: "/lgas",
                    data: {
                        state_id: stateId
                    }
                }).done(function(data) {
                    $.each(data, function(index, item) {
                        $("#lgas").append(`
                 <option value="${item.id}" ${lgaId == item.id ? 'selected' : '' }>${item.name}</option>
                 `);
                    });
                });
            }
        });
    </script>
@endsection
