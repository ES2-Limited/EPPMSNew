@extends('layouts.admin')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: #01054C;">Roles & Access Permissions</h3>
                <p class="text-muted mb-0">Manage system roles and configure their granular module capabilities.</p>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2">
                <a href="{{ route('roles.create') }}" class="btn text-white d-flex align-items-center gap-2" style="background-color: #01054C;">
                    <i class="fas fa-plus"></i> Add New Role
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Roles Table Card -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3">Role Name</th>
                                <th class="py-3">Assigned Permissions</th>
                                <th class="py-3">Created</th>
                                <th class="text-end pe-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $role)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle p-2 rounded-circle">
                                                <i class="fas fa-user-shield"></i>
                                            </span>
                                            <div>
                                                <div class="fw-bold text-dark fs-4">
                                                    {{ ucwords(str_replace(['_', ' '], ' ', $role->name)) }}
                                                </div>
                                                <div class="text-muted small"><code>{{ $role->name }}</code></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        @if(strtolower($role->name) === 'admin')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                                <i class="fas fa-infinity me-1"></i> Full Access (Super Admin)
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                                <i class="fas fa-key me-1"></i> {{ $role->permissions->count() }} active permissions
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-muted">
                                        {{ $role->created_at ? $role->created_at->diffForHumans() : 'System Default' }}
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                                <i class="fas fa-sliders-h"></i> Configure Permissions
                                            </a>

                                            @if(strtolower($role->name) !== 'admin')
                                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-user-slash fs-1 d-block mb-2"></i>
                                        No roles found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($roles->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    {{ $roles->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
