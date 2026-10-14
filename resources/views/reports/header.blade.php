<div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-end no-print">
    <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-md no-print">
        <a href="{{ url()->previous() }}" style="color: white; text-decoration:none;">
            {{ $back_name }}</a>
    </span>
</div>

<div class="text-center letterhead mb-4">
    <img src="{{ setting_asset('app_logo', 'assets/images/logos/ncaalogo.png') }}" alt="Logo" style="max-height: 70px; width: auto;">
    <h6 class="text-muted text-uppercase fw-bold mt-2 mb-1" style="letter-spacing: 1px;">{{ setting('organization_name', 'Nigeria Civil Aviation Authority (NCAA)') }}</h6>
    <h2 class="mt-1 fw-bold">{{ $header }}</h2>
    <p><strong>Print Date:</strong> <span id="currentDate"></span></p>
</div>
