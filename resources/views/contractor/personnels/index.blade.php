@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            @hasanyrole('admin')
                @include('custom.card-counter')
            @endrole
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Contractor Personnels</h6>
                <div>
                    @if ($personnels->count() > 0)
                        <span class="badge text-bg-secondary rounded-4 px-3 py-1 lh-sm">
                            <a href="{{ route('records.print', ['ulid' => 'conPE11£2']) }}" style="color:white;">Print
                                Report</a>
                        </span>
                    @endif

                    <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                        <a href="{{ route('contractor-personnels.create') }}" style="color: white">
                            Add Contractor Personnel</a>
                    </span>
                </div>
            </div>
            @include('custom.form-response')
            <div class="row">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Contractor Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="itemTable">
                        @forelse ($personnels as $personnel)
                            <tr>
                                <td>{{ $personnel->user?->first_name . ' ' . $personnel->user?->last_name }}</td>
                                <td>{{ $personnel->phone }}</td>
                                <td>{{ $personnel->user?->email }}</td>
                                <td>{{ $personnel->contractor?->user?->name }}</td>
                                <td>
                                    @include('custom.action', [
                                        'url' => 'contractor-personnels/' . $personnel->ulid,
                                    ])
                                </td>
                            </tr>
                        @empty
                            <tr colspan="3">
                                No contractor personnels registered!!!
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $personnels->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('custom.customizer')
    </div>
    </div>
    <script>
        function deleteConfirmation() {
            return confirm('Are you sure you want to delete this item?');
        }
    </script>

    </script>
@endsection
