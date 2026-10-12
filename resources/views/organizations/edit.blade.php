@extends('layouts.admin')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
            <h6 class="mb-0 fs-4">Organization Registration
            </h6>

        </div>

        @include('custom.subhead')

        <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
            <div>
                <h6 class="mb-0 fs-4">Organization Information</h6>
                <p class="mb-0 fs-4">Update the Organization Information here</p>
            </div>
        </div>

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
                <form method="POST" action="{{ route('organizations.update', $organization->ulid) }}" enctype="multipart/form-data" onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Updating...';">
                    @csrf
                    @method('PATCH')

                    <div>
                        <div class="card-body">
                            <h4 class="card-title">Organization Credentials</h4>
                            <div class="row pt-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $organization->name) }}" placeholder="Name of Organization" />
                                    </div>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-md-6">
                                    <div class="mb-3 ">
                                        <label class="form-label">Website</label>
                                        <input type="text" id="Website" name="website" value="{{ old('website', $organization->website) }}" class="form-control @error('website') is-invalid @enderror" placeholder="www.organization.com" />
                                    </div>
                                    @error('website')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-md-6">
                                    <div class="mb-3 ">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" id="email" value="{{ old('email', $organization->email) }}" class="form-control" placeholder="ncaa@gov.ng" />
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
                                    {{ __('Update') }}
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
