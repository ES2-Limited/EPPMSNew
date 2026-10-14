@extends('layouts.admin')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid py-4">

        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: #01054C;">
                    <i class="fas fa-shield-alt me-2 text-primary"></i>Audit Trail & Activity Logs
                </h3>
                <p class="text-muted mb-0">Immutable compliance log recording user actions, state changes, IP origins, and system events.</p>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2">
                <a href="{{ route('audit-logs.export', request()->query()) }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
                    <i class="fas fa-file-csv"></i> Export CSV
                </a>
                <a href="{{ route('settings.index', ['tab' => 'diagnostics']) }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                    <i class="fas fa-server"></i> Diagnostics
                </a>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-3">
                <form action="{{ route('audit-logs.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted mb-1">Search Keywords</label>
                        <input type="text" name="search" class="form-control form-control-sm" 
                               value="{{ request('search') }}" placeholder="User, entity name, description...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1">Action</label>
                        <select name="action" class="form-select form-select-sm">
                            <option value="">All Actions</option>
                            @foreach($actions as $act)
                                <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>
                                    {{ ucfirst($act) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1">Entity Type</label>
                        <select name="entity" class="form-select form-select-sm">
                            <option value="">All Entities</option>
                            @foreach($entities as $ent)
                                <option value="{{ $ent }}" {{ request('entity') == $ent ? 'selected' : '' }}>
                                    {{ $ent }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1">Date From</label>
                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1">Date To</label>
                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-1 d-flex gap-1">
                        <button type="submit" class="btn btn-sm text-white w-100" style="background-color: #01054C;" title="Filter">
                            <i class="fas fa-filter"></i>
                        </button>
                        <a href="{{ route('audit-logs.index') }}" class="btn btn-sm btn-light border w-100" title="Reset">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Audit Trail Table Card -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 170px;">Timestamp</th>
                                <th>Actor</th>
                                <th>Action</th>
                                <th>Target Entity</th>
                                <th>Summary / Description</th>
                                <th>IP Address</th>
                                <th class="text-end pe-3">Changes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td class="ps-3 text-nowrap">
                                        <div class="fw-semibold text-dark">{{ $log->created_at->format('M d, Y') }}</div>
                                        <div class="small text-muted">{{ $log->created_at->format('h:i:s A') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $log->user_name }}</div>
                                        @if($log->user_id)
                                            <div class="small text-muted">ID: #{{ $log->user_id }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if(strtolower($log->action) === 'created')
                                            <span class="badge bg-success-subtle text-success fw-bold px-2 py-1">
                                                <i class="fas fa-plus-circle me-1"></i> CREATED
                                            </span>
                                        @elseif(strtolower($log->action) === 'updated')
                                            <span class="badge bg-warning-subtle text-warning-emphasis fw-bold px-2 py-1">
                                                <i class="fas fa-edit me-1"></i> UPDATED
                                            </span>
                                        @elseif(strtolower($log->action) === 'deleted')
                                            <span class="badge bg-danger-subtle text-danger fw-bold px-2 py-1">
                                                <i class="fas fa-trash me-1"></i> DELETED
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary fw-bold px-2 py-1">
                                                {{ strtoupper($log->action) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border me-1">{{ $log->auditable_type }}</span>
                                        @if($log->auditable_name)
                                            <span class="fw-semibold text-dark">{{ Str::limit($log->auditable_name, 30) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-secondary small">{{ $log->description ?? '—' }}</span>
                                    </td>
                                    <td class="text-nowrap">
                                        <code class="small text-muted">{{ $log->ip_address ?? 'CLI/Internal' }}</code>
                                    </td>
                                    <td class="text-end pe-3">
                                        @if($log->old_values || $log->new_values)
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="modal" data-bs-target="#logDiffModal{{ $log->id }}">
                                                <i class="fas fa-code-branch me-1"></i> Diffs
                                            </button>

                                            <!-- Diff Modal -->
                                            <div class="modal fade text-start" id="logDiffModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold">
                                                                Audit Event #{{ $log->id }}: {{ ucfirst($log->action) }} {{ $log->auditable_type }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-3">
                                                            <div class="mb-3 p-2 bg-light rounded border">
                                                                <div class="small"><strong>Actor:</strong> {{ $log->user_name }} ({{ $log->ip_address }})</div>
                                                                <div class="small"><strong>Timestamp:</strong> {{ $log->created_at->format('Y-m-d H:i:s') }}</div>
                                                                <div class="small"><strong>Description:</strong> {{ $log->description }}</div>
                                                            </div>

                                                            <div class="row g-3">
                                                                @if($log->old_values)
                                                                    <div class="{{ $log->new_values ? 'col-md-6' : 'col-12' }}">
                                                                        <label class="fw-bold small text-danger mb-1">
                                                                            <i class="fas fa-minus-circle me-1"></i> Previous State (Old)
                                                                        </label>
                                                                        <pre class="bg-light p-2 rounded border small text-dark" style="max-height: 350px; overflow-y: auto;">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                                    </div>
                                                                @endif
                                                                @if($log->new_values)
                                                                    <div class="{{ $log->old_values ? 'col-md-6' : 'col-12' }}">
                                                                        <label class="fw-bold small text-success mb-1">
                                                                            <i class="fas fa-plus-circle me-1"></i> Updated State (New)
                                                                        </label>
                                                                        <pre class="bg-light p-2 rounded border small text-dark" style="max-height: 350px; overflow-y: auto;">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-clipboard-list fs-1 opacity-25 mb-2 d-block"></i>
                                        No audit log entries matching the criteria were found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="card-footer bg-white border-top py-3">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

