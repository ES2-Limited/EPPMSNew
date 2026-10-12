@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Unit Registration
                </h6>
            </div>
            @include('custom.subhead')
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <div>
                    <h6 class="mb-0 fs-4">Unit Information</h6>
                    <p class="mb-0 fs-4">Update the Unit Information here</p>
                </div>
                <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                    <a href="{{ route('units.index') }}" style="color:white;"> Go back</a>
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
                    <form method="POST" action="{{ route('units.store') }}"
                        onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Saving...';">
                        @csrf
                        <div>
                            <div class="card-body">

                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Department Name</label>
                                            <select class="form-select" id="inlineFormCustomSelect" name="department_id"
                                                required>
                                                <option value='' {{ old('department_id') ? '' : 'selected' }}>Choose
                                                    Department</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Unit Name</label>
                                            <input type="text" name="name" id="UnitName" class="form-control"
                                                placeholder="Unit Name" value="{{ old('name') }}" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-3">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Unit Function</label>
                                            <textarea name="function" id="Unitfunction" class="form-control form-control-danger" placeholder="Unit Function"
                                                required rows="4">{{ old('function') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col text-end">
                                        <button type="submit" class="btn btn-primary" id="submit-button">Save</button>
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
@endsection
