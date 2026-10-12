@extends('layouts.admin')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">

            @include('custom.subhead')

            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <div id="content" class="flex-grow-1 tableCard">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <a href="#" class="text-decoration-none me-3">
                                    <iconify-icon icon="solar:alt-arrow-left-bold" class="fs-2"></iconify-icon>
                                </a>
                                <span class="text-dark">Role Report</span>
                            </div>

                            <div class="d-flex flex-column flex-md-row align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/assets/images/logos/ncaalogo.png" alt="NCAA Logo" style="width: 10%" />
                                    <h5 class="mb-2 mb-md-0 ps-4">NCAA Roles</h5>
                                </div>

                                <div class="ms-md-auto mt-2 mt-md-0">

                                    <div class="d-flex align-items-between">

                                        <a href="{{ route('roles.create') }}" style="color: white" class="mx-2">
                                            <button
                                                class="btn d-flex align-items-center gap-2 git reset --hard update: updated the personnels report page"
                                                style="background-color:green; color: white">
                                                <iconify-icon icon="solar:document-add-bold" class="fs-4"></iconify-icon>
                                                Add Role
                                            </button></a>

                                        <a href="{{ route('records.print', ['ulid' => 'rolE18@2']) }}" style="color:white;">
                                            <button
                                                class="btn d-flex align-items-center gap-2 git reset --hard update: updated the personnels report page"
                                                style="background-color: #01054c; color: white">
                                                <iconify-icon icon="solar:printer-bold" class="fs-4"></iconify-icon>
                                                Print Report
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <p class="mb-4 text-dark fw-bold">
                                Document contains the list of registered units
                            </p>
                            <div class="table-responsive">
                                <table class="table table-striped rounded-4 overflow-hidden">
                                    <tbody>
                                        <tr>
                                            <th>Name</th>
                                            <th>Created</th>
                                            <th></th>
                                        </tr>
                                        @forelse ($roles as $role)
                                            <tr>
                                                <td>{{ ucfirst(str_replace(['_', ' '], ' ', $role->name)) }} </td>
                                                <td> {{ $role->created_at->diffForHumans() }} </td>
                                                <td>
                                                    @include('custom.action', [
                                                        'url' => 'admin/roles/' . $role->id,
                                                        'show_view' => true
                                                    ])
                                                </td>
                                            </tr>

                                        @empty
                                            <tr colspan="3">
                                                No role added!!!
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('custom.customizer')
    </div>
    </div>
    <script>
        function deleteConfirmation() {
            return confirm('Are you sure you want to delete this role record?');
        }
    </script>
@endsection
