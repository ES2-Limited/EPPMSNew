@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Department Registration
                </h6>
            </div>
            @include('custom.subhead')
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <div>
                    <p class="mb-0 fs-4">Update the Department Information here</p>
                </div>
                <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                    <a href="{{ route('departments.index') }}" style="color:white;"> Go back</a>
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
                    <form method="POST" action="{{ route('departments.update', $department->ulid) }}"
                        onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Updating...';">
                        @csrf
                        @method('PUT')
                        <div>
                            <div class="card-body">

                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Directorate Name</label>
                                            <select class="form-select" id="inlineFormCustomSelect" name="directorate_id"
                                                required>
                                                <option selected>Choose directorate</option>
                                                @foreach ($directorates as $directorate)
                                                    <option value="{{ $directorate->id }}"
                                                        {{ $department->directorate?->id == $directorate->id ? 'selected' : '' }}>
                                                        {{ $directorate->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 ">
                                            <label class="form-label">Department ID</label>
                                            <input type="text" id="DeptID" name="code"
                                                class="form-control form-control-danger"
                                                value="{{ old('code', $department->code) }}" placeholder="Department ID" />
                                        </div>
                                    </div>

                                    <div class="row pt-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Department Name</label>
                                                <input type="text" name="name" id="DepartmentName"
                                                    value="{{ old('name', $department->name) }}" class="form-control"
                                                    placeholder="Department Name" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row pt-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Department Function</label>
                                                <textarea name="function" id="function" class="form-control form-control-danger" placeholder="Department Function"
                                                    rows="4">{{ old('function', $department->function) }}</textarea>
                                            </div>
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
            </div>
        </div>
    </div>

    @include('custom.customizer')
    </div>
    </div>
@endsection
