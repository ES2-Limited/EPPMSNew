@extends('layouts.admin')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Edit Personnel Information
                </h6>


                <a href="{{ url()->previous() }}" class="text-decoration-none me-3 text-primary">
                    <iconify-icon icon="solar:alt-arrow-left-bold" class="fs-2"></iconify-icon>
                    <span>Back</span>
                </a>
            </div>

            @include('custom.card-counter')

            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <div>
                    <h6 class="mb-0 fs-4">Contractor Personnel Information</h6>
                    <p class="mb-0 fs-4">Update the Contractor Personnel Information here</p>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @include('contractor.personnels.partials._form', [
                        'formAction' => route('contractor-personnels.update', $contractor_personnel->ulid),
                        'isEdit' => true,
                        'contractor_personnel' => $contractor_personnel,
                    ])
                </div>
            </div>
        </div>
    </div>

    @include('custom.customizer')
    </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#department").change(() => {
                let department = $("#department").val();
                $('#unit').children('option:not(:first)').remove();
                $.ajax({
                    type: "GET",
                    url: "/get-units",
                    data: {
                        department_id: department
                    }
                }).done(function(data) {
                    $.each(data, function(index, item) {
                        $("#unit").append(`
                     <option value="${item.id}">${item.name}</option>
                     `);
                    });
                });
            });
        });

        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });
    </script>

@endsection
