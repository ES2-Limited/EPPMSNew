@extends('layouts.admin')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid py-4">

        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: #01054C;">System Settings & Configuration</h3>
                <p class="text-muted mb-0">Configure branding, application identity, localization formats, and authorized signatures.</p>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Dashboard
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

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <strong>Please check the errors below:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            $activeTab = request()->query('tab', 'general');
        @endphp

        <!-- Settings Card with Tabs -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom p-0">
                <ul class="nav nav-tabs card-header-tabs ms-0 me-0" id="settingsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 px-4 fw-semibold {{ $activeTab === 'general' ? 'active' : '' }}" 
                                id="general-tab" data-bs-toggle="tab" data-bs-target="#general-pane" type="button" role="tab">
                            <i class="fas fa-sliders-h me-2 text-primary"></i> General
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 px-4 fw-semibold {{ $activeTab === 'branding' ? 'active' : '' }}" 
                                id="branding-tab" data-bs-toggle="tab" data-bs-target="#branding-pane" type="button" role="tab">
                            <i class="fas fa-palette me-2 text-primary"></i> Branding & Logos
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 px-4 fw-semibold {{ $activeTab === 'localization' ? 'active' : '' }}" 
                                id="localization-tab" data-bs-toggle="tab" data-bs-target="#localization-pane" type="button" role="tab">
                            <i class="fas fa-globe me-2 text-primary"></i> Localization & Formats
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 px-4 fw-semibold {{ $activeTab === 'hierarchy' ? 'active' : '' }}" 
                                id="hierarchy-tab" data-bs-toggle="tab" data-bs-target="#hierarchy-pane" type="button" role="tab">
                            <i class="fas fa-sitemap me-2 text-primary"></i> Hierarchy & Project Tiers
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 px-4 fw-semibold {{ $activeTab === 'offices' ? 'active' : '' }}" 
                                id="offices-tab" data-bs-toggle="tab" data-bs-target="#offices-pane" type="button" role="tab">
                            <i class="fas fa-building me-2 text-primary"></i> Office Types & Locations
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 px-4 fw-semibold {{ $activeTab === 'project_types' ? 'active' : '' }}" 
                                id="project_types-tab" data-bs-toggle="tab" data-bs-target="#project_types-pane" type="button" role="tab">
                            <i class="fas fa-cubes me-2 text-primary"></i> Project Types
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 px-4 fw-semibold {{ $activeTab === 'firm_types' ? 'active' : '' }}" 
                                id="firm_types-tab" data-bs-toggle="tab" data-bs-target="#firm_types-pane" type="button" role="tab">
                            <i class="fas fa-handshake me-2 text-primary"></i> Firm Classifications
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 px-4 fw-semibold {{ $activeTab === 'signatures' ? 'active' : '' }}" 
                                id="signatures-tab" data-bs-toggle="tab" data-bs-target="#signatures-pane" type="button" role="tab">
                            <i class="fas fa-file-signature me-2 text-primary"></i> Management Signatures
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 px-4 fw-semibold {{ $activeTab === 'fiscal_budget' ? 'active' : '' }}" 
                                id="fiscal_budget-tab" data-bs-toggle="tab" data-bs-target="#fiscal_budget-pane" type="button" role="tab">
                            <i class="fas fa-coins me-2 text-primary"></i> Budget & Fiscal
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 px-4 fw-semibold {{ $activeTab === 'certificates' ? 'active' : '' }}" 
                                id="certificates-tab" data-bs-toggle="tab" data-bs-target="#certificates-pane" type="button" role="tab">
                            <i class="fas fa-certificate me-2 text-primary"></i> Reports & Certificates
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 px-4 fw-semibold {{ $activeTab === 'notifications' ? 'active' : '' }}" 
                                id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications-pane" type="button" role="tab">
                            <i class="fas fa-bell me-2 text-primary"></i> Notifications & Alerts
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content" id="settingsTabContent">
                    
                    <!-- TAB 1: GENERAL SETTINGS -->
                    <div class="tab-pane fade {{ $activeTab === 'general' ? 'show active' : '' }}" id="general-pane" role="tabpanel">
                        <form action="{{ route('settings.update.batch') }}" method="POST">
                            @csrf
                            <input type="hidden" name="group" value="general">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Application Name <span class="text-danger">*</span></label>
                                    <input type="text" name="app_name" class="form-control" 
                                           value="{{ old('app_name', setting('app_name', 'Enterprise Project & Performance Management System')) }}" required>
                                    <div class="form-text">Full system name displayed on titles and portal headers.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Short Name / Acronym <span class="text-danger">*</span></label>
                                    <input type="text" name="app_short_name" class="form-control" 
                                           value="{{ old('app_short_name', setting('app_short_name', 'EPPMS')) }}" required>
                                    <div class="form-text">Displayed on badges, notifications, and mobile navigation.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Organization / Agency Name <span class="text-danger">*</span></label>
                                    <input type="text" name="organization_name" class="form-control" 
                                           value="{{ old('organization_name', setting('organization_name', 'Nigeria Civil Aviation Authority (NCAA)')) }}" required>
                                    <div class="form-text">Parent agency or government authority name.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Official Support / Contact Email</label>
                                    <input type="email" name="contact_email" class="form-control" 
                                           value="{{ old('contact_email', setting('contact_email', 'info@ncaa.gov.ng')) }}">
                                    <div class="form-text">Public contact email displayed in footers and automated communications.</div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold">Footer Copyright Text</label>
                                    <input type="text" name="footer_text" class="form-control" 
                                           value="{{ old('footer_text', setting('footer_text', '© ' . date('Y') . ' NCAA EPPMS. All rights reserved.')) }}">
                                    <div class="form-text">Copyright text rendered in the footer of all pages.</div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                <button type="submit" class="btn text-white px-4 py-2" style="background-color: #01054C;">
                                    <i class="fas fa-save me-1"></i> Save General Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 2: BRANDING & LOGOS -->
                    <div class="tab-pane fade {{ $activeTab === 'branding' ? 'show active' : '' }}" id="branding-pane" role="tabpanel">
                        <form action="{{ route('settings.update.batch') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="group" value="branding">

                            <div class="row g-4">
                                <!-- Full Brand Logo -->
                                <div class="col-md-6">
                                    <div class="p-4 border rounded-3 bg-light text-center h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <h6 class="fw-bold mb-1">Primary Brand Logo</h6>
                                            <p class="text-muted small mb-3">Appears on login screens, print reports, and landing navigation.</p>
                                            
                                            <div class="d-flex align-items-center justify-content-center p-3 bg-white rounded border mb-3" style="min-height: 120px;">
                                                <img src="{{ setting_asset('app_logo', 'assets/images/logos/ncaalogo.png') }}" 
                                                     alt="Brand Logo" class="img-fluid" style="max-height: 80px; object-fit: contain;">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="form-label fw-semibold text-start w-100">Upload New Logo (PNG, JPG, SVG, WebP)</label>
                                            <input type="file" name="app_logo" class="form-control" accept="image/*">
                                            <div class="form-text text-start">Recommended height: 60px - 100px with transparent background.</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Compact Icon / Favicon -->
                                <div class="col-md-6">
                                    <div class="p-4 border rounded-3 bg-light text-center h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <h6 class="fw-bold mb-1">Sidebar Icon & Favicon</h6>
                                            <p class="text-muted small mb-3">Appears in the left sidebar banner, browser tab, and loaders.</p>
                                            
                                            <div class="d-flex align-items-center justify-content-center p-3 rounded border mb-3" style="min-height: 120px; background-color: #01054C;">
                                                <img src="{{ setting_asset('app_icon', 'assets/images/logos/ncaa-icon.png') }}" 
                                                     alt="Brand Icon" class="img-fluid" style="max-height: 70px; object-fit: contain;">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="form-label fw-semibold text-start w-100">Upload New Icon (PNG, SVG, ICO)</label>
                                            <input type="file" name="app_icon" class="form-control" accept="image/*">
                                            <div class="form-text text-start">Recommended square ratio: 128x128 or 256x256.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                <button type="submit" class="btn text-white px-4 py-2" style="background-color: #01054C;">
                                    <i class="fas fa-upload me-1"></i> Update Brand Assets
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 3: LOCALIZATION & FORMATS -->
                    <div class="tab-pane fade {{ $activeTab === 'localization' ? 'show active' : '' }}" id="localization-pane" role="tabpanel">
                        <form action="{{ route('settings.update.batch') }}" method="POST">
                            @csrf
                            <input type="hidden" name="group" value="localization">

                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Currency Symbol <span class="text-danger">*</span></label>
                                    <input type="text" name="currency_symbol" class="form-control" 
                                           value="{{ old('currency_symbol', setting('currency_symbol', '₦')) }}" required>
                                    <div class="form-text">e.g. ₦ (Naira), $ (Dollar), € (Euro), £ (Pound)</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">ISO Currency Code <span class="text-danger">*</span></label>
                                    <input type="text" name="currency_code" class="form-control" 
                                           value="{{ old('currency_code', setting('currency_code', 'NGN')) }}" required>
                                    <div class="form-text">e.g. NGN, USD, EUR, GBP</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Default Date Format <span class="text-danger">*</span></label>
                                    <select name="date_format" class="form-select" required>
                                        @php
                                            $currentFormat = setting('date_format', 'd M, Y');
                                        @endphp
                                        <option value="d M, Y" {{ $currentFormat === 'd M, Y' ? 'selected' : '' }}>14 Oct, 2026 (d M, Y)</option>
                                        <option value="d/m/Y" {{ $currentFormat === 'd/m/Y' ? 'selected' : '' }}>14/10/2026 (DD/MM/YYYY)</option>
                                        <option value="m/d/Y" {{ $currentFormat === 'm/d/Y' ? 'selected' : '' }}>10/14/2026 (MM/DD/YYYY)</option>
                                        <option value="Y-m-d" {{ $currentFormat === 'Y-m-d' ? 'selected' : '' }}>2026-10-14 (YYYY-MM-DD)</option>
                                        <option value="jS F Y" {{ $currentFormat === 'jS F Y' ? 'selected' : '' }}>14th October 2026 (jS F Y)</option>
                                    </select>
                                    <div class="form-text">Format used in tables, milestones, and project timelines.</div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                <button type="submit" class="btn text-white px-4 py-2" style="background-color: #01054C;">
                                    <i class="fas fa-save me-1"></i> Save Localization
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 4: HIERARCHY & PROJECT TIERS -->
                    <div class="tab-pane fade {{ $activeTab === 'hierarchy' ? 'show active' : '' }}" id="hierarchy-pane" role="tabpanel">
                        <form action="{{ route('settings.update.batch') }}" method="POST">
                            @csrf
                            <input type="hidden" name="group" value="hierarchy">

                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-3">
                                        <i class="fas fa-info-circle fs-4 text-info"></i>
                                        <div>
                                            <strong>Organizational Structure & Project Binding:</strong>
                                            <p class="mb-0 small">Toggle which organizational levels are active across the system. Deactivating a tier hides it from project creation forms and navigation.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tier Toggles -->
                                <div class="col-md-4">
                                    <div class="card h-100 border rounded-3 p-3 bg-light">
                                        <div class="form-check form-switch d-flex justify-content-between align-items-center ps-0">
                                            <label class="form-check-label fw-bold text-dark fs-4 mb-0" for="enableDirectoratesSwitch">
                                                Directorates Tier
                                            </label>
                                            <input class="form-check-input ms-3 fs-5" type="checkbox" name="enable_directorates" value="1" 
                                                   id="enableDirectoratesSwitch" {{ setting('enable_directorates', true) ? 'checked' : '' }}>
                                        </div>
                                        <p class="text-muted small mt-2 mb-0">Highest internal executive divisions (e.g. Directorate of Airworthiness).</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card h-100 border rounded-3 p-3 bg-light">
                                        <div class="form-check form-switch d-flex justify-content-between align-items-center ps-0">
                                            <label class="form-check-label fw-bold text-dark fs-4 mb-0" for="enableDepartmentsSwitch">
                                                Departments Tier
                                            </label>
                                            <input class="form-check-input ms-3 fs-5" type="checkbox" name="enable_departments" value="1" 
                                                   id="enableDepartmentsSwitch" {{ setting('enable_departments', true) ? 'checked' : '' }}>
                                        </div>
                                        <p class="text-muted small mt-2 mb-0">Departmental subdivisions responsible for operational execution.</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card h-100 border rounded-3 p-3 bg-light">
                                        <div class="form-check form-switch d-flex justify-content-between align-items-center ps-0">
                                            <label class="form-check-label fw-bold text-dark fs-4 mb-0" for="enableUnitsSwitch">
                                                Units Tier
                                            </label>
                                            <input class="form-check-input ms-3 fs-5" type="checkbox" name="enable_units" value="1" 
                                                   id="enableUnitsSwitch" {{ setting('enable_units', true) ? 'checked' : '' }}>
                                        </div>
                                        <p class="text-muted small mt-2 mb-0">Specialized operational units under departments or directly under executive office.</p>
                                    </div>
                                </div>

                                <!-- Primary Project Tie -->
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Primary Project Ownership Tier <span class="text-danger">*</span></label>
                                    @php
                                        $primaryTier = setting('project_primary_tier', 'directorate');
                                    @endphp
                                    <select name="project_primary_tier" class="form-select" required>
                                        <option value="directorate" {{ $primaryTier === 'directorate' ? 'selected' : '' }}>
                                            Directorate (Required: Projects belong primarily to a Directorate)
                                        </option>
                                        <option value="department" {{ $primaryTier === 'department' ? 'selected' : '' }}>
                                            Department (Required: Projects belong directly to a Department)
                                        </option>
                                        <option value="unit" {{ $primaryTier === 'unit' ? 'selected' : '' }}>
                                            Unit (Required: Projects belong directly to a Specialized Unit)
                                        </option>
                                        <option value="optional" {{ $primaryTier === 'optional' ? 'selected' : '' }}>
                                            Flexible / Optional (Any active tier can be assigned without enforcing one)
                                        </option>
                                    </select>
                                    <div class="form-text">Controls which field is mandatory when personnel create or update projects.</div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                <button type="submit" class="btn text-white px-4 py-2" style="background-color: #01054C;">
                                    <i class="fas fa-save me-1"></i> Save Hierarchy Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 5: OFFICE TYPES & LOCATIONS -->
                    <div class="tab-pane fade {{ $activeTab === 'offices' ? 'show active' : '' }}" id="offices-pane" role="tabpanel">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1">Office Typology & Classifications</h5>
                                <p class="text-muted small mb-0">Manage office types (e.g. Headquarters, Regional Command, Field Station) assigned to physical locations.</p>
                            </div>
                            <div class="mt-3 mt-md-0 d-flex gap-2">
                                <a href="{{ route('offices.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-map-marker-alt me-1"></i> View Office Locations ({{ $officesCount }})
                                </a>
                                <button type="button" class="btn btn-sm text-white" style="background-color: #01054C;" 
                                        data-bs-toggle="modal" data-bs-target="#createOfficeTypeModal">
                                    <i class="fas fa-plus me-1"></i> Add Office Type
                                </button>
                            </div>
                        </div>

                        <!-- Office Types Table -->
                        <div class="table-responsive border rounded-3">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3 py-3">Office Type Name</th>
                                        <th class="py-3">Attached Locations</th>
                                        <th class="py-3">Created</th>
                                        <th class="text-end pe-3 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($officeTypes as $type)
                                        <tr>
                                            <td class="ps-3 py-3 fw-semibold text-dark">
                                                <i class="fas fa-building text-primary me-2"></i> {{ $type->name }}
                                            </td>
                                            <td class="py-3">
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                    {{ $type->offices_count }} location(s)
                                                </span>
                                            </td>
                                            <td class="py-3 text-muted small">
                                                {{ $type->created_at ? $type->created_at->diffForHumans() : 'System Default' }}
                                            </td>
                                            <td class="text-end pe-3 py-3">
                                                <div class="d-inline-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" data-bs-target="#editOfficeTypeModal-{{ $type->id }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <form action="{{ route('office-types.destroy', $type->id) }}" method="POST" 
                                                          onsubmit="return confirm('Delete office type {{ $type->name }}?');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                {{ $type->offices_count > 0 ? 'disabled title="Locations attached"' : '' }}>
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Office Type Modal -->
                                        <div class="modal fade" id="editOfficeTypeModal-{{ $type->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('office-types.update', $type->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Office Type</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <label class="form-label fw-bold">Office Type Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control" value="{{ $type->name }}" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn text-white" style="background-color: #01054C;">Update Type</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">No office types defined yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Create Office Type Modal -->
                        <div class="modal fade" id="createOfficeTypeModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('office-types.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Create Office Type</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label class="form-label fw-bold">Office Type Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" placeholder="e.g. Airport Command, Liaison Office" required>
                                            <div class="form-text">Used to classify physical facilities and offices across the country.</div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn text-white" style="background-color: #01054C;">Add Type</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: PROJECT TYPES -->
                    <div class="tab-pane fade {{ $activeTab === 'project_types' ? 'show active' : '' }}" id="project_types-pane" role="tabpanel">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1">Project Typologies & Categories</h5>
                                <p class="text-muted small mb-0">Classify capital and operational projects into manageable categories (e.g. Navigational Aids, Runway Civil Works, ICT Systems).</p>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <button type="button" class="btn btn-sm text-white" style="background-color: #01054C;" 
                                        data-bs-toggle="modal" data-bs-target="#createProjectTypeModal">
                                    <i class="fas fa-plus me-1"></i> Add Project Type
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive border rounded-3">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3 py-3">Project Type</th>
                                        <th class="py-3">Associated Projects</th>
                                        <th class="py-3">Created</th>
                                        <th class="text-end pe-3 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($projectTypes as $type)
                                        <tr>
                                            <td class="ps-3 py-3 fw-semibold text-dark">
                                                <i class="fas fa-cube text-primary me-2"></i> {{ $type->name }}
                                            </td>
                                            <td class="py-3">
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                    {{ $type->projects_count }} project(s)
                                                </span>
                                            </td>
                                            <td class="py-3 text-muted small">
                                                {{ $type->created_at ? $type->created_at->diffForHumans() : 'System Default' }}
                                            </td>
                                            <td class="text-end pe-3 py-3">
                                                <div class="d-inline-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" data-bs-target="#editProjectTypeModal-{{ $type->id }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <form action="{{ route('project-types.destroy', $type->id) }}" method="POST" 
                                                          onsubmit="return confirm('Delete project type {{ $type->name }}?');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                {{ $type->projects_count > 0 ? 'disabled title="Projects linked to this type"' : '' }}>
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editProjectTypeModal-{{ $type->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('project-types.update', $type->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Project Type</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <label class="form-label fw-bold">Type Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control" value="{{ $type->name }}" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn text-white" style="background-color: #01054C;">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">No project types configured yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Create Modal -->
                        <div class="modal fade" id="createProjectTypeModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('project-types.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Create Project Type</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label class="form-label fw-bold">Project Type Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" placeholder="e.g. Navigational Aids, Runway Civil Works" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn text-white" style="background-color: #01054C;">Add Project Type</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: FIRM CLASSIFICATIONS -->
                    <div class="tab-pane fade {{ $activeTab === 'firm_types' ? 'show active' : '' }}" id="firm_types-pane" role="tabpanel">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1">External Firm & Partner Classifications</h5>
                                <p class="text-muted small mb-0">Manage classifications for contracting firms, engineering consultants, suppliers, and external partners.</p>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <button type="button" class="btn btn-sm text-white" style="background-color: #01054C;" 
                                        data-bs-toggle="modal" data-bs-target="#createFirmTypeModal">
                                    <i class="fas fa-plus me-1"></i> Add Firm Classification
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive border rounded-3">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3 py-3">Classification Name</th>
                                        <th class="py-3">Registered Firms</th>
                                        <th class="py-3">Created</th>
                                        <th class="text-end pe-3 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($firmTypes as $ftype)
                                        <tr>
                                            <td class="ps-3 py-3 fw-semibold text-dark">
                                                <i class="fas fa-handshake text-primary me-2"></i> {{ $ftype->name }}
                                            </td>
                                            <td class="py-3">
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                    {{ $ftype->contractors_count }} registered firm(s)
                                                </span>
                                            </td>
                                            <td class="py-3 text-muted small">
                                                {{ $ftype->created_at ? $ftype->created_at->diffForHumans() : 'System Default' }}
                                            </td>
                                            <td class="text-end pe-3 py-3">
                                                <div class="d-inline-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" data-bs-target="#editFirmTypeModal-{{ $ftype->id }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <form action="{{ route('firm-types.destroy', $ftype->id) }}" method="POST" 
                                                          onsubmit="return confirm('Delete firm classification {{ $ftype->name }}?');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                {{ $ftype->contractors_count > 0 ? 'disabled title="Firms linked to this classification"' : '' }}>
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editFirmTypeModal-{{ $ftype->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('firm-types.update', $ftype->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Firm Classification</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <label class="form-label fw-bold">Classification Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control" value="{{ $ftype->name }}" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn text-white" style="background-color: #01054C;">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">No firm classifications configured yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Create Modal -->
                        <div class="modal fade" id="createFirmTypeModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('firm-types.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Create Firm Classification</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label class="form-label fw-bold">Classification Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" placeholder="e.g. EPC Contractor, Quality Consultant, Supplier" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn text-white" style="background-color: #01054C;">Add Classification</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: MANAGEMENT SIGNATURES -->
                    <div class="tab-pane fade {{ $activeTab === 'signatures' ? 'show active' : '' }}" id="signatures-pane" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-0">Authorized Executive Signatures</h5>
                                <p class="text-muted small mb-0">Used for automated report sign-offs, certifications, and approvals.</p>
                            </div>
                            <a href="{{ route('settings.create') }}" class="btn btn-sm btn-success d-flex align-items-center gap-1">
                                <i class="fas fa-plus"></i> Add Signature Profile
                            </a>
                        </div>

                        <div class="row g-4">
                            @forelse ($signatures as $signature)
                                <div class="col-md-4">
                                    <div class="card h-100 border shadow-sm rounded-3">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div>
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                        {{ strtoupper($signature->key) }}
                                                    </span>
                                                    <span class="badge {{ $signature->active ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $signature->active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                                <h6 class="fw-bold mb-1">{{ $signature->name }}</h6>
                                                
                                                <div class="text-center p-3 bg-light rounded my-3" style="min-height: 90px;">
                                                    @if ($signature->signature)
                                                        <img src="{{ asset('storage/signature_files/' . $signature->signature) }}"
                                                             alt="Signature" class="img-fluid" style="max-height: 60px;">
                                                    @else
                                                        <span class="text-muted small fst-italic">No signature image uploaded</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mt-2 text-end">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" data-bs-target="#editModal-{{ $signature->id }}">
                                                    <i class="fas fa-edit me-1"></i> Edit Profile
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal-{{ $signature->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $signature->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('settings.update.single', $signature->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel-{{ $signature->id }}">Edit Signature: {{ $signature->key }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Signatory Name</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $signature->name }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Signature Image</label>
                                                        <input type="file" name="signature" class="form-control" accept="image/*">
                                                        @if ($signature->signature)
                                                            <div class="mt-2 text-center p-2 bg-light rounded">
                                                                <img src="{{ asset('storage/signature_files/' . $signature->signature) }}" 
                                                                     alt="Current" style="max-height: 50px;">
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="form-check form-switch mt-3">
                                                        <input class="form-check-input" type="checkbox" name="active" value="1" id="activeSwitch-{{ $signature->id }}" {{ $signature->active ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-semibold" for="activeSwitch-{{ $signature->id }}">Active on Reports</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn text-white" style="background-color: #01054C;">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center py-5 bg-light rounded-3">
                                        <i class="fas fa-file-signature text-muted fs-1 mb-2"></i>
                                        <p class="text-muted mb-3">No management signature profiles created yet.</p>
                                        <a href="{{ route('settings.create') }}" class="btn btn-primary btn-sm">Create First Profile</a>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- TAB: FISCAL & BUDGETING -->
                    <div class="tab-pane fade {{ $activeTab === 'fiscal_budget' ? 'show active' : '' }}" id="fiscal_budget-pane" role="tabpanel">
                        <!-- Fiscal Years Section -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                            <div>
                                <h5 class="fw-bold mb-1"><i class="fas fa-calendar-alt text-primary me-2"></i>Fiscal / Appropriation Years</h5>
                                <p class="text-muted small mb-0">Manage federal budget years and appropriation cycles (e.g., 2024, 2025, 2026).</p>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <button type="button" class="btn btn-sm text-white" style="background-color: #01054C;" 
                                        data-bs-toggle="modal" data-bs-target="#createFiscalYearModal">
                                    <i class="fas fa-plus me-1"></i> Add Fiscal Year
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive border rounded-3 mb-5">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3 py-3">Fiscal Year</th>
                                        <th class="py-3">Description</th>
                                        <th class="py-3">Status</th>
                                        <th class="py-3">Associated Projects</th>
                                        <th class="text-end pe-3 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($fiscalYears as $fy)
                                        <tr>
                                            <td class="ps-3 py-3 fw-bold text-dark fs-6">{{ $fy->year }}</td>
                                            <td class="py-3 text-muted">{{ $fy->description ?? 'Standard Appropriation' }}</td>
                                            <td class="py-3">
                                                @if($fy->is_active)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="fas fa-check-circle me-1"></i> Active</span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Closed / Inactive</span>
                                                @endif
                                            </td>
                                            <td class="py-3">
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                    {{ $fy->projects_count }} project(s)
                                                </span>
                                            </td>
                                            <td class="text-end pe-3 py-3">
                                                <div class="d-inline-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" data-bs-target="#editFiscalYearModal-{{ $fy->id }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <form action="{{ route('fiscal-years.destroy', $fy->id) }}" method="POST" 
                                                          onsubmit="return confirm('Delete fiscal year {{ $fy->year }}?');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                {{ $fy->projects_count > 0 ? 'disabled title="Projects linked to this year"' : '' }}>
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editFiscalYearModal-{{ $fy->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('fiscal-years.update', $fy->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Fiscal Year</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Year <span class="text-danger">*</span></label>
                                                                <input type="text" name="year" class="form-control" value="{{ $fy->year }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Description</label>
                                                                <input type="text" name="description" class="form-control" value="{{ $fy->description }}">
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeFy-{{ $fy->id }}" {{ $fy->is_active ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-semibold" for="activeFy-{{ $fy->id }}">Active for Project Selection</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn text-white" style="background-color: #01054C;">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No fiscal years configured yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Create Fiscal Year Modal -->
                        <div class="modal fade" id="createFiscalYearModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('fiscal-years.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Fiscal Year</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Year <span class="text-danger">*</span></label>
                                                <input type="text" name="year" class="form-control" placeholder="e.g. 2025" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Description</label>
                                                <input type="text" name="description" class="form-control" placeholder="e.g. 2025 Appropriation Act">
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="createFyActive" checked>
                                                <label class="form-check-label fw-semibold" for="createFyActive">Active for Project Selection</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn text-white" style="background-color: #01054C;">Add Year</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Funding Sources Section -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 pt-3 border-top">
                            <div>
                                <h5 class="fw-bold mb-1"><i class="fas fa-money-bill-wave text-success me-2"></i>Funding Sources & Envelopes</h5>
                                <p class="text-muted small mb-0">Classify capital and recurrent funding streams (e.g. FGN Capital, IGR, Bilateral Grants, PPP).</p>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <button type="button" class="btn btn-sm text-white" style="background-color: #01054C;" 
                                        data-bs-toggle="modal" data-bs-target="#createFundingSourceModal">
                                    <i class="fas fa-plus me-1"></i> Add Funding Source
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive border rounded-3">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3 py-3">Source Name</th>
                                        <th class="py-3">Code / Acronym</th>
                                        <th class="py-3">Status</th>
                                        <th class="py-3">Attached Projects</th>
                                        <th class="text-end pe-3 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($fundingSources as $fs)
                                        <tr>
                                            <td class="ps-3 py-3 fw-semibold text-dark">{{ $fs->name }}</td>
                                            <td class="py-3">
                                                <span class="badge bg-light text-dark border">{{ $fs->code ?? 'N/A' }}</span>
                                            </td>
                                            <td class="py-3">
                                                @if($fs->is_active)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="fas fa-check-circle me-1"></i> Active</span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Disabled</span>
                                                @endif
                                            </td>
                                            <td class="py-3">
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                    {{ $fs->projects_count }} project(s)
                                                </span>
                                            </td>
                                            <td class="text-end pe-3 py-3">
                                                <div class="d-inline-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" data-bs-target="#editFundingSourceModal-{{ $fs->id }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <form action="{{ route('funding-sources.destroy', $fs->id) }}" method="POST" 
                                                          onsubmit="return confirm('Delete funding source {{ $fs->name }}?');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                {{ $fs->projects_count > 0 ? 'disabled title="Projects linked to this source"' : '' }}>
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editFundingSourceModal-{{ $fs->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('funding-sources.update', $fs->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Funding Source</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Source Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="name" class="form-control" value="{{ $fs->name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Code / Acronym</label>
                                                                <input type="text" name="code" class="form-control" value="{{ $fs->code }}">
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeFs-{{ $fs->id }}" {{ $fs->is_active ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-semibold" for="activeFs-{{ $fs->id }}">Active for Project Selection</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn text-white" style="background-color: #01054C;">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No funding sources configured yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Create Funding Source Modal -->
                        <div class="modal fade" id="createFundingSourceModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('funding-sources.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Funding Source</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Source Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" placeholder="e.g. FGN Capital Appropriation" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Code / Acronym</label>
                                                <input type="text" name="code" class="form-control" placeholder="e.g. FGN-CAP">
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="createFsActive" checked>
                                                <label class="form-check-label fw-semibold" for="createFsActive">Active for Project Selection</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn text-white" style="background-color: #01054C;">Add Source</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: REPORTS & CERTIFICATES -->
                    <div class="tab-pane fade {{ $activeTab === 'certificates' ? 'show active' : '' }}" id="certificates-pane" role="tabpanel">
                        <form action="{{ route('settings.update.batch') }}" method="POST">
                            @csrf
                            <input type="hidden" name="group" value="certificates">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Project Completion Certificate Title <span class="text-danger">*</span></label>
                                    <input type="text" name="certificate_title" class="form-control" 
                                           value="{{ $certificateSettings->get('certificate_title')?->value ?? 'Project Completion Certificate' }}" required>
                                    <div class="form-text">Header text printed at the top of completed project certificates.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Milestone IPC Certificate Title <span class="text-danger">*</span></label>
                                    <input type="text" name="ipc_certificate_title" class="form-control" 
                                           value="{{ $certificateSettings->get('ipc_certificate_title')?->value ?? 'Project Milestone Completion/Interim Payment Certificate (IPC)' }}" required>
                                    <div class="form-text">Header text printed on milestone Interim Payment Certificates.</div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold">Official Attestation / Compliance Statement</label>
                                    <textarea name="certificate_attestation" class="form-control" rows="3">{{ $certificateSettings->get('certificate_attestation')?->value ?? 'The project has been executed in accordance with agreed-upon specifications, civil aviation safety guidelines, and statutory engineering standards.' }}</textarea>
                                    <div class="form-text">Legal certification statement printed above the executive signature line on reports.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Designated Official Executive Signatory</label>
                                    <select name="certificate_signatory_id" class="form-select">
                                        <option value="">-- Automatic / Default Signatory --</option>
                                        @foreach ($signatures as $sig)
                                            <option value="{{ $sig->id }}" {{ ($certificateSettings->get('certificate_signatory_id')?->value == $sig->id) ? 'selected' : '' }}>
                                                {{ $sig->name }} ({{ $sig->key }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Select which management profile signature is automatically appended to printable certificates.</div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top text-end">
                                <button type="submit" class="btn text-white px-4" style="background-color: #01054C;">
                                    <i class="fas fa-save me-1"></i> Save Certificate Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB: NOTIFICATIONS & ALERTS -->
                    <div class="tab-pane fade {{ $activeTab === 'notifications' ? 'show active' : '' }}" id="notifications-pane" role="tabpanel">
                        <form action="{{ route('settings.update.batch') }}" method="POST">
                            @csrf
                            <input type="hidden" name="group" value="notifications">

                            <div class="row g-4">
                                <div class="col-12">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-envelope-open-text text-primary me-2"></i>Automated Dispatch Triggers</h6>
                                    
                                    <div class="p-3 border rounded-3 mb-3 bg-light">
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" name="notify_project_award" value="1" id="notifyAwardSwitch" 
                                                   {{ (bool)($notificationSettings->get('notify_project_award')?->value ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="notifyAwardSwitch">Project Award Dispatch</label>
                                        </div>
                                        <p class="text-muted small mb-0 ms-4">Notify assigned contractor and technical consultant when a project is created and awarded.</p>
                                    </div>

                                    <div class="p-3 border rounded-3 mb-3 bg-light">
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" name="notify_milestone_completion" value="1" id="notifyMilestoneSwitch" 
                                                   {{ (bool)($notificationSettings->get('notify_milestone_completion')?->value ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="notifyMilestoneSwitch">Milestone Completion Notices</label>
                                        </div>
                                        <p class="text-muted small mb-0 ms-4">Send email alerts when milestones are certified and completed.</p>
                                    </div>

                                    <div class="p-3 border rounded-3 mb-3 bg-light">
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" name="notify_task_completion" value="1" id="notifyTaskSwitch" 
                                                   {{ (bool)($notificationSettings->get('notify_task_completion')?->value ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="notifyTaskSwitch">Field Task Sign-Off Notices</label>
                                        </div>
                                        <p class="text-muted small mb-0 ms-4">Alert project supervisors and technical consultants when a specific task item is marked done.</p>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Procurement & Audit Oversight Mailbox (BCC)</label>
                                    <input type="email" name="audit_notification_email" class="form-control" 
                                           value="{{ $notificationSettings->get('audit_notification_email')?->value ?? '' }}" 
                                           placeholder="e.g. audit.projects@ncaa.gov.ng">
                                    <div class="form-text">When configured, all project awards and milestone payment notices will BCC this oversight email.</div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top text-end">
                                <button type="submit" class="btn text-white px-4" style="background-color: #01054C;">
                                    <i class="fas fa-save me-1"></i> Save Notification Settings
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
