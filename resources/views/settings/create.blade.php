@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="max-w-xl mx-auto p-4">
                <div class="d-flex flex-column flex-md-row align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <a href="{{ url()->previous() }}" style="color:white;">
                            <button
                                class="btn d-flex align-items-center gap-2 git reset --hard update: updated the personnels report page"
                                style="background-color: #01054c; color: white">
                                <iconify-icon icon="solar:back" class="fs-4"></iconify-icon>
                                Go Back
                            </button>
                        </a>
                    </div>

                </div>

                <h2 class="text-xl font-semibold mb-4">Add New Management Details</h2>

                <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-4 border rounded shadow-sm bg-light">
                    @csrf

                    <div class="mb-3">
                        <label for="key" class="form-label">Key <small class="text-muted">(e.g. manager, chief
                                director)</small></label>
                        <input type="text" name="key" id="key" class="form-control" value="{{ old('key') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="signature" class="form-label">Signature</label>
                        <input type="file" name="signature" id="signature" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success">Create Details</button>
                </form>
            </div>
        </div>
    </div>
    @include('custom.customizer')
    </div>
    </div>
@endsection
