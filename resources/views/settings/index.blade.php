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
                        <button class="nav-link py-3 px-4 fw-semibold {{ $activeTab === 'signatures' ? 'active' : '' }}" 
                                id="signatures-tab" data-bs-toggle="tab" data-bs-target="#signatures-pane" type="button" role="tab">
                            <i class="fas fa-file-signature me-2 text-primary"></i> Management Signatures
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

                    <!-- TAB 4: MANAGEMENT SIGNATURES -->
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

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
