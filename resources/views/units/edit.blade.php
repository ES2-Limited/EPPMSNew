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
                    <form method="POST" action="{{ route('units.update', $unit->ulid) }}"
                        onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Updating...';">
                        @csrf
                        @method('PUT')
                        <div>
                            <div class="card-body">

                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Department Name</label>
                                            <select class="form-select" id="inlineFormCustomSelect" name="department_id"
                                                required>
                                                <option selected>Choose Department</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ $unit->department?->id == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Unit Name</label>
                                            <input type="text" name="name" id="UnitName"
                                                value="{{ old('name', $unit->name) }}" class="form-control"
                                                placeholder="Unit Name" required />
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>


                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Unit Function</label>
                                            <textarea name="function" id="Unitfunction" value="{{ old('function', $unit->function) }}"
                                                class="form-control form-control-danger" placeholder="Unit Function" required rows="4">{{ old('function', $unit->function) }}</textarea>
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
                <!-- end Person Info -->
            </div>
        </div>
    </div>
    @include('custom.customizer')
    </div>
    </div>
@endsection
