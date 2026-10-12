@role('admin')
<div class="row">
    <div class="col-lg-3">
        <div class="row">
            <div class="col-md-6 col-lg-12">
                <a href="{{ route('offices.index') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-6 mb-6 pb-3  justify-content-between">
                                <h6 class="mb-0 fs-4">Office Locations</h6>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-6">
                                <h4 class="mb-0 fw-medium">{{ App\Models\Office::count() }}</h4>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="row">
            <div class="col-md-6 col-lg-12">
                <a href="{{ route('directorates.index') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-6 mb-6 pb-3  justify-content-between">
                                <h6 class="mb-0 fs-4">Directorates</h6>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-6">
                                <h4 class="mb-0 fw-medium">{{ App\Models\Directorate::count() }}</h4>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-2">
        <div class="row">
            <div class="col-md-6 col-lg-12">
                <a href="{{ route('departments.index') }}" class="text-decoration-none">
                    <div class="card bg-secondary-subtle overflow-hidden shadow-none">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-6 mb-6 pb-3  justify-content-between">
                                <h6 class="mb-0 fs-4">Departments</h6>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-6">
                                <h4 class="mb-0 fw-medium"> {{ App\Models\Department::count() }}</h4>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-2">
        <div class="row">
            <div class="col-md-6 col-lg-12">
                <a href="{{ route('units.index') }}" class="text-decoration-none">
                    <div class="card bg-danger-subtle overflow-hidden shadow-none">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-6 mb-6 pb-3  justify-content-between">
                                <h6 class="mb-0 fs-4">Units</h6>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-6">
                                <h4 class="mb-0 fw-medium">{{ App\Models\Unit::count() }} </h4>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-2">
        <div class="row">
            <div class="col-md-6 col-lg-12">
                <div class="card bg-success-subtle overflow-hidden shadow-none">
                    <a href="{{ route('personnels.index') }}" class="text-decoration-none">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-6 mb-6 pb-3  justify-content-between">
                                <h6 class="mb-0 fs-4">Personnels</h6>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-6">
                                <h4 class="mb-0 fw-medium">{{ App\Models\Personnel::count() }}</h4>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endrole
