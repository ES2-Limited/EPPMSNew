<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #0b1f66 0%, #1e40af 100%); color: #ffffff;">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.05em; opacity: 0.85;">Total Projects</span>
                    <h3 class="fw-bold mb-0 mt-1 text-white">{{ App\Models\Project::count() }}</h3>
                    <small class="text-white-50" style="font-size: 0.75rem;">Active in portfolio</small>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(255, 255, 255, 0.15);">
                    <i class="ti ti-folders text-white fs-6"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="background: #ffffff; border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-uppercase fw-semibold text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Milestones</span>
                    <h3 class="fw-bold mb-0 mt-1" style="color: #0b1f66;">{{ App\Models\Milestone::count() }}</h3>
                    <small class="text-muted" style="font-size: 0.75rem;">Across all tracks</small>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #f0f9ff; color: #0284c7;">
                    <i class="ti ti-flag text-info fs-6"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="background: #ffffff; border-left: 4px solid #059669 !important;">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-uppercase fw-semibold text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Project Tasks</span>
                    <h3 class="fw-bold mb-0 mt-1 text-dark">{{ App\Models\Task::count() }}</h3>
                    <small class="text-muted" style="font-size: 0.75rem;">Deliverables logged</small>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #ecfdf5; color: #059669;">
                    <i class="ti ti-checkbox text-success fs-6"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="background: #ffffff; border-left: 4px solid #d97706 !important;">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-uppercase fw-semibold text-muted" style="font-size: 0.75rem; letter-spacing: 0.05em;">Personnel</span>
                    <h3 class="fw-bold mb-0 mt-1 text-dark">{{ App\Models\ProjectPersonnel::count() }}</h3>
                    <small class="text-muted" style="font-size: 0.75rem;">Staff & contractors</small>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #fffbeb; color: #d97706;">
                    <i class="ti ti-users text-warning fs-6"></i>
                </div>
            </div>
        </div>
    </div>
</div>
