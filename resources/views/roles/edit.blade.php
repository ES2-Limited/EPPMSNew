@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">

            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('roles.index') }}" class="text-decoration-none me-3">
                    <iconify-icon icon="solar:alt-arrow-left-bold" class="fs-2"></iconify-icon>
                    <span>Back</span>
                </a>
            </div>

            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <div>
                    <h6 class="mb-0 fs-4">Edit  {{ $role->name }} Role</h6>
                </div>
            </div>

            <div class="col-lg-10">
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
                    <form method="POST" action="{{ route('roles.update', $role->id) }}" id="roles"
                        onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Saving...';">
                        @csrf
                        @method('PUT')
                        <div class="">
                            <div class="card-body">
                                <h4 class="card-title">Create Personel Roles</h4>
                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Role Name</label>
                                            <input type="text" id="roleName" class="form-control"
                                                placeholder="Role Name" name="name"
                                                value="{{ old('name', $role->name) }}" required />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="card-body border-top">
                                    <button type="submit" class="btn btn-primary" name="createRole" id="submit-button">
                                        Save </button>
                                    <button type="reset" class="btn bg-danger-subtle text-danger ms-6"> Cancel </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>
    </div>

    @include('custom.customizer')
    </div>
    </div>
@endsection
