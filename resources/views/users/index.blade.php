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
                                    <h5 class="mb-2 mb-md-0 ps-4">NCAA Users</h5>
                                </div>

                                <div class="ms-md-auto mt-2 mt-md-0">

                                    <div class="d-flex align-items-between">

                                        <a href="{{ route('admin.users.create') }}" style="color: white" class="mx-2">
                                            <button
                                                class="btn d-flex align-items-center gap-2 git reset --hard update: updated the personnels report page"
                                                style="background-color:green; color: white">
                                                <iconify-icon icon="solar:document-add-bold" class="fs-4"></iconify-icon>
                                                Add User
                                            </button></a>

                                        <form method="GET" class="mb-3">
                                            <div class="input-group">
                                                <input type="text" name="q" class="form-control"
                                                    placeholder="Search by name or email" value="{{ request('q') }}">
                                                <button class="btn btn-outline-secondary" type="submit">Search</button>
                                            </div>
                                        </form>
                                    </div>


                                </div>
                            </div>
                            @include('custom.form-response')
                            <p class="mb-4 text-dark fw-bold">
                                Document contains the list of registered users
                            </p>
                            <div>
                                <table class="table table-striped rounded-4">
                                    <tbody>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Created</th>
                                            <th></th>

                                        </tr>
                                        @forelse($users as $user)
                                            <tr>
                                                <td>{{ $user->name ?? $user->first_name .''. $user->last_name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->created_at->diffForHumans() }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.users.edit', $user) }}"
                                                        class="btn btn-sm btn-outline-primary me-2">Edit</a>
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                        class="d-inline" onsubmit="return confirm('Delete this user?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger"
                                                            type="submit">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">
                                                    No users registered!!!
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
                    {{ $users->links() }}
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
