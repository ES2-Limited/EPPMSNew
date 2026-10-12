@section('custom-css')
    <style>
        .three-dots {
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            background: none;
            padding: 0;
        }
    </style>
@endsection
<div class="dropdown">
    <button class="three-dots btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        &#8942;
    </button>
    <ul class="dropdown-menu">
        @if (!isset($show_view))
        <li><a class="dropdown-item btn btn-info btn-sm" href="{{ url($url) }}">View</a></li>
        @endif
        @hasanyrole('admin')
            <li><a class="dropdown-item btn btn-info btn-sm" href="{{ url($url.'/edit') }}">Edit</a></li>
            <li>
                <form action="{{ url($url) }}" method="POST" style="display:inline;"
                    onsubmit="return deleteConfirmation()">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item text-danger btn btn-danger btn-sm">Delete</button>
                </form>
            </li>
        @endrole
    </ul>
</div>
