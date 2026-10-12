@extends('layouts.admin')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
            <h6 class="mb-0 fs-4">Firm Registration
            </h6>

            <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                <a href="{{ route('contractors.index') }}" style="color:white;">Go back</a>
            </span>
        </div>
        @include('custom.card-counter')

        <div class="col-12">
            <!-- form begin -->
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
                <form method="POST" action="{{ route('contractors.store') }}" enctype="multipart/form-data" onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Saving...';">
                    @csrf
                    <div>
                        <div class="card-body">
                            <h4 class="card-title">Firm Credentials</h4>
                            <div class="row pt-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" id="Name" class="form-control" placeholder="Name of Firm" name="name"  value="{{ old('name') }}" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Firm Type</label>
                                        <select class="form-select" id="firm" name="firm_type_id" required>
                                            <option value="" {{ old('firm_type_id') ? '' : 'selected' }}>--SELECT
                                                 Firm Type--</option>
                                            @foreach ($firms as $firm)
                                                <option value="{{ $firm->id }}"
                                                    {{ old('firm_type_id') == $firm->id ? 'selected' : '' }}>
                                                    {{ $firm->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row pt-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" id="email" class="form-control form-control-danger" placeholder="ncaa@gov.ng" name="email" value="{{ old('email') }}"  />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" id="phoneNumber" class="form-control form-control-danger" placeholder="Phone Number" name="phone" value="{{ old('phone') }}"  />
                                    </div>
                                </div>
                            </div>

                            <div class="row pt-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="col-md-4 col-form-label text-md-start">{{ __('Password') }}</label>
                                        <div class="col-md-12">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Website</label>
                                        <input type="text" id="Website" class="form-control form-control-danger" placeholder="www.contractor.com" name="website" value="{{ old('website') }}" />
                                    </div>
                                </div>
                            </div>


                            <div class="mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Logo</label>
                                    <input type="file" class="form-control" name="logo" />
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="card-body border-top">
                                <button type="submit" class="btn btn-primary" id="submit-button">
                                    Save
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
@endsection
