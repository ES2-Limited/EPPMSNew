@extends('layouts.admin')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
            <div>
                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                    <i class="fas fa-arrow-left me-1"></i> Back to Roles
                </a>
                <h3 class="fw-bold mb-1" style="color: #01054C;">Create New Role</h3>
                <p class="text-muted mb-0">Define a custom system role and select its assigned module permissions.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('roles.store') }}">
            @csrf

            <!-- Role Details Card -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Role Identifier <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   placeholder="e.g. procurement_officer, quality_manager" 
                                   value="{{ old('name') }}" required>
                            <div class="form-text">Unique lowercase identifier with underscores or hyphens.</div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <button type="button" class="btn btn-outline-primary btn-sm me-2" id="selectAllBtn">
                                <i class="fas fa-check-double me-1"></i> Select All Permissions
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="deselectAllBtn">
                                <i class="fas fa-times me-1"></i> Deselect All
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permission Modules Grid -->
            <div class="row g-4">
                @foreach ($modules as $moduleName => $permissions)
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0 text-dark">
                                    <i class="fas fa-layer-group text-primary me-2"></i> {{ $moduleName }}
                                </h6>
                                <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none module-toggle-btn" 
                                        data-module="{{ Str::slug($moduleName) }}">
                                    Toggle All
                                </button>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex flex-column gap-3">
                                    @foreach ($permissions as $permKey => $permLabel)
                                        <div class="form-check form-switch p-2 rounded hover-bg d-flex align-items-center justify-content-between">
                                            <div>
                                                <label class="form-check-label fw-semibold text-dark mb-0 cursor-pointer" for="perm-{{ $permKey }}">
                                                    {{ $permLabel }}
                                                </label>
                                                <div class="text-muted small"><code>{{ $permKey }}</code></div>
                                            </div>
                                            <input class="form-check-input ms-3 perm-checkbox module-{{ Str::slug($moduleName) }}" 
                                                   type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $permKey }}" 
                                                   id="perm-{{ $permKey }}"
                                                   {{ in_array($permKey, old('permissions', [])) ? 'checked' : '' }}>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Bottom Action Bar -->
            <div class="card border-0 shadow-sm rounded-3 mt-4">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    <button type="submit" class="btn text-white px-5 py-2" style="background-color: #01054C;">
                        <i class="fas fa-plus me-1"></i> Create Role
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<style>
.cursor-pointer { cursor: pointer; }
.hover-bg:hover { background-color: #f8f9fa; }
.form-check-input:checked {
    background-color: #01054C;
    border-color: #01054C;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAllBtn = document.getElementById('selectAllBtn');
    const deselectAllBtn = document.getElementById('deselectAllBtn');
    const allCheckboxes = document.querySelectorAll('.perm-checkbox');

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function () {
            allCheckboxes.forEach(cb => cb.checked = true);
        });
    }

    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function () {
            allCheckboxes.forEach(cb => cb.checked = false);
        });
    }

    document.querySelectorAll('.module-toggle-btn').forEach(button => {
        button.addEventListener('click', function () {
            const moduleSlug = this.getAttribute('data-module');
            const moduleCheckboxes = document.querySelectorAll('.module-' + moduleSlug);
            const allChecked = Array.from(moduleCheckboxes).every(cb => cb.checked);
            moduleCheckboxes.forEach(cb => cb.checked = !allChecked);
        });
    });
});
</script>
@endsection
