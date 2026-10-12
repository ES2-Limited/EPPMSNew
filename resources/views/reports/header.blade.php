<div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-end no-print">
    <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-md no-print">
        <a href="{{ url()->previous() }}" style="color: white; text-decoration:none;">
            {{ $back_name }}</a>
    </span>
</div>

<div class="text-center letterhead mb-4">
    <img src="{{ asset('/assets/images/logos/ncaalogo.png') }}" alt="Logo" width="100">
    <h2 class="mt-2">{{ $header }}</h2>
    <p><strong>Print Date:</strong> <span id="currentDate"></span></p>
</div>
