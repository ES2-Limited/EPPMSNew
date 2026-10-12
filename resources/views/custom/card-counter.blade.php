@php
    $user_id = auth()->id();
@endphp
<div class="row">

    <div class="col-lg-3">
        <div class="row">
            <div class="col-md-6 col-lg-12">
                <a href="{{ route('contractors.index') }}" class="text-decoration-none">
                    <div class="card bg-secondary-subtle overflow-hidden shadow-none">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-6 mb-6 pb-3  justify-content-between">
                                <h6 class="mb-0 fs-4">Contractors</h6>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-6">
                                <h4 class="mb-0 fw-medium">
                                    {{ App\Models\Contractor::where('firm_type_id', 1)->count() }}
                                </h4>
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
                <a href="{{ route('contractors.index') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-6 mb-6 pb-3  justify-content-between">
                                <h6 class="mb-0 fs-4">Consultants</h6>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-6">
                                <h4 class="mb-0 fw-medium">
                                    {{ App\Models\Contractor::where('firm_type_id', 2)->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

</div>
