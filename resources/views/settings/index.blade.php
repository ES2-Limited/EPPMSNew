@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">

        <div class="container py-4">
            <h2 class="mb-4">Manage Settings</h2>

            <div class="d-flex flex-column flex-md-row align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <a href="{{ url()->previous() }}" style="color:white;">
                        <button
                            class="btn d-flex align-items-center gap-2"
                            style="background-color: #01054c; color: white">
                            <iconify-icon icon="solar:back" class="fs-4"></iconify-icon>
                            Go Back
                        </button>
                    </a>
                </div>

                <div class="ms-md-auto mt-2 mt-md-0">
                    <div class="d-flex align-items-between">

                        <a href="{{ route('settings.create') }}" style="color: white" class="mx-2">
                            <button
                                class="btn d-flex align-items-center gap-2"
                                style="background-color:green; color: white">
                                <iconify-icon icon="solar:document-add-bold" class="fs-4"></iconify-icon>
                                Add Setting
                            </button></a>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                @forelse ($admin_settings as $admin_setting)
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ ucfirst($admin_setting->key) }}</h5>
                                <p><strong>Name:</strong> {{ $admin_setting->name }}</p>
                                <p><strong>Status:</strong> {{ ($admin_setting->active) ? 'Active' : 'Inactive' }}</p>

                                @if ($admin_setting->signature)
                                    <img src="{{ asset('storage/signature_files/' . $admin_setting->signature) }}"
                                        class="img-thumbnail mb-2" style="max-height: 100px;">
                                @endif

                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editModal-{{ $admin_setting->id }}">
                                    Edit
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editModal-{{ $admin_setting->id }}" tabindex="-1"
                        aria-labelledby="editModalLabel-{{ $admin_setting->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('settings.update.single', $admin_setting->id) }}"
                                enctype="multipart/form-data" class="modal-content">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel-{{ $admin_setting->id }}">Edit
                                        {{ ucfirst($admin_setting->key) }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name-{{ $admin_setting->id }}" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name-{{ $admin_setting->id }}"
                                            name="name" value="{{ $admin_setting->name }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Signature</label>
                                        <input type="file" class="form-control" name="signature">
                                        @if ($admin_setting->signature)
                                            <img src="{{ asset('storage/signature_files/' . $admin_setting->signature) }}"
                                                class="mt-2 rounded" style="height: 60px;">
                                        @endif
                                    </div>

                                    <div class="form-check mb-2">
                                        <input type="hidden" name="active" value="0">
                                        <input class="form-check-input" type="checkbox" name="active" value="1"
                                            id="active-{{ $admin_setting->id }}"
                                            {{ $admin_setting->active ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="active-{{ $admin_setting->id }}">Active</label>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="d-flex flex-column flex-md-row align-items-center mb-3">
                        No Settings available!!!
                    </div>
                @endforelse
            </div>

        </div>

    </div>
    @include('custom.customizer')
    </div>
    </div>
    <script>
        function deleteConfirmation() {
            return confirm('Are you sure you want to delete this role record?');
        }
    </script>
@endsection
