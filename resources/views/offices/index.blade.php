@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            @include('custom.subhead')
            <div class="row">
                <div id="content" class="flex-grow-1 tableCard">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-md-row align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/assets/images/logos/ncaalogo.png" alt="NCAA Logo" style="width: 10%" />
                                    <h5 class="mb-2 mb-md-0 ps-4">NCAA Offices</h5>
                                </div>

                                <div class="ms-md-auto mt-2 mt-md-0">

                                    <div class="d-flex align-items-between">

                                        <a href="{{ route('offices.create') }}" style="color: white" class="mx-2">
                                            <button
                                                class="btn d-flex align-items-center gap-2 git reset --hard update: updated the personnels report page"
                                                style="background-color:green; color: white">
                                                <iconify-icon icon="solar:document-add-bold" class="fs-4"></iconify-icon>
                                                Add Office Location
                                            </button></a>

                                        @if ($offices->count() > 0)
                                            <a href="{{ route('records.print', ['ulid' => 'offFR18@4']) }}"
                                                style="color:white;">
                                                <button
                                                    class="btn d-flex align-items-center gap-2 git reset --hard update: updated the personnels report page"
                                                    style="background-color: #01054c; color: white">
                                                    <iconify-icon icon="solar:printer-bold" class="fs-4"></iconify-icon>
                                                    Print Report
                                                </button>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @include('custom.form-response')
                            <p class="mb-4 text-dark fw-bold">
                                Document contains the list of registered personnels
                            </p>
                            <div>
                                <table class="table table-striped rounded-4">
                                    <tbody>
                                        <tr>
                                            <th>Name</th>
                                            <th>Office Type</th>
                                            <th>State</th>
                                            <th></th>

                                        </tr>
                                        @forelse ($offices as $office)
                                            <tr>
                                                <td>{{ $office->name }}</td>
                                                <td>{{ $office->office_type?->name }}</td>
                                                <td>{{ $office->lga?->state?->name }}</td>
                                                <td>
                                                    @include('custom.action', [
                                                        'url' => 'admin/offices/' . $office->ulid,
                                                    ])
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">
                                                    No office registered!!!
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    {{ $offices->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('custom.customizer')
    </div>
    </div>
    <script>
        function deleteConfirmation() {
            return confirm('Are you sure you want to delete this office record?');
        }
    </script>
@endsection
