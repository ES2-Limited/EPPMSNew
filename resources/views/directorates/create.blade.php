@extends('layouts.admin')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Directorate Registration
                </h6>

            </div>

            @include('custom.subhead')
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <div>
                    <h6 class="mb-0 fs-4">Enter the Information to create a directorate</h6>
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
                    <form method="POST" action="{{ route('directorates.store') }}"
                        onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Saving...';">
                        @csrf
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="directorate_id" class="form-label fw-bold">Directorate ID</label>
                                    <input type="text" id="DeptID" name="code" class="form-control"
                                        placeholder="Directorate ID" value="{{ old('code') }}" />
                                </div>

                                <div class="col-md-6">
                                    <label for="directorate_name" class="form-label fw-bold">Directorate Name</label>
                                    <input type="text" id="directorateName" name="name" class="form-control"
                                        placeholder="Directorate Name" value="{{ old('name') }}" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="directorate_function" class="form-label fw-bold">Directorate
                                        Function</label>
                                    <textarea id="function" name="function" class="form-control form-control-danger" placeholder="Directorate Function"
                                        rows="4">{{ old('function') }}</textarea>
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
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('custom.customizer')
    </div>
    </div>
@endsection
