@extends('layouts.admin')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Directorate Edit
                </h6>

            </div>

            @include('custom.subhead')
            <div class="d-flex align-items-center mb-3">
                <a href="" class="text-decoration-none me-3">
                    <iconify-icon icon="solar:alt-arrow-left-bold" class="fs-2"></iconify-icon>
                </a>
                <span class="text-dark">Directorate Report</span>
            </div>
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <div>
                    <p class="mb-0 fs-4">Update the Directorate Information here</p>
                </div>
                <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                    <a href="{{ route('directorates.index') }}" style="color:white;"> Go back</a>
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
                    <form method="POST" action="{{ route('directorates.update', $directorate->ulid) }}"
                        onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Updating...';">
                        @csrf
                        @method('PUT')
                        <div>
                            <div class="card-body">

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Directorate ID</label>
                                        <input type="text" id="DeptID" name="code"
                                            class="form-control form-control-danger"
                                            value="{{ old('code', $directorate->code) }}" placeholder="Directorate ID" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Directorate Name</label>
                                        <input type="text" name="name" id="DirectorateName" class="form-control"
                                            value="{{ old('name', $directorate->name) }}" placeholder="Directorate Name" />
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Directorate Function</label>
                                        <textarea name="function" id="function" class="form-control form-control-danger" placeholder="Directorate Function"
                                            rows="4">{{ old('function', $directorate->function) }}</textarea>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col text-end">
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
@endsection
