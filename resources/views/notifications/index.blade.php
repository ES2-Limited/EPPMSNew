@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div id="content" class="flex-grow-1 tableCard">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <ul class="list-group">
                                @forelse($notifications as $notification)
                                    <li
                                        class="list-group-item d-flex  justify-content-between mb-2 {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
                                        <a href="{{ url($notification->data['url']) }}" class="mark-as-read"
                                            data-id="{{ $notification->id }}">
                                            {{ $notification->data['message'] }}

                                            <span class="text-muted small"> —
                                                {{ $notification->created_at->diffForHumans() }}</span>
                                        </a>
                                        @if (is_null($notification->read_at))
                                            <span class="badge bg-warning">Unread</span>
                                        @endif
                                    </li>
                                @empty
                                    <li class="list-group-item">No notifications</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.mark-as-read').forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                let notificationId = this.getAttribute('data-id');
                fetch('/notifications/mark-as-read/' + notificationId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        window.location.href = this.getAttribute('href');
                    }
                });
            });
        });
    </script>
@endsection
