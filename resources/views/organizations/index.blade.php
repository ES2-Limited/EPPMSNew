@extends('layouts.admin')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Organization Details
                </h6>

            </div>

            @include('custom.subhead')

            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <div>
                    <h6 class="mb-0 fs-4">Registered Organization Information</h6>
                </div>
            </div>

            <div class="col-12">

                <div class="row justify-content-start">
                    <div class="col-md-8 col-lg-9">
                        @include('custom.form-response')
                        <div class="card">
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>Organization Name:</strong> {{ $organization->name }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Organization Email:</strong> {{ $organization->email }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Website:</strong> {{ $organization->website }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Created:</strong> {{ $organization->created_at->diffForHumans() }}
                                    </li>
                                </ul>
                                <div class="mt-4 text-center">
                                    <a href="{{ url('admin/organizations/' . $organization->ulid . '/edit') }}"
                                        class="btn btn-primary px-5">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>

                                    <button onclick="printDiv('printableArea')" class="btn btn-primary px-5"> <i
                                            class="bi bi-printer"></i>
                                        Print</button>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container d-none">
            <div id="printableArea">
                <div class="text-center letterhead">
                    <img src="{{ asset('/assets/images/logos/ncaalogo.png') }}" alt="Logo" width="100">
                    <h2 class="mt-2">{{ $organization->name }}</h2>
                    <p class="mb-0"> {{ $organization->address1 }}</p>
                    <p class="info"><strong>Created On:</strong> {{ $organization->created_at->diffForHumans() }}</p>
                    <p>Email: {{ $organization->email }} | Phone: {{ $organization->contact_no ?? 'No contact NO.' }} </p>
                </div>

                <div class="mb-4">
                    <p class="info"><strong>Org. Name:</strong> {{ $organization->name }}</p>
                    <p class="info"><strong>Org. Email:</strong> {{ $organization->email }}</p>
                    <p class="info"><strong>Org. Website:</strong> {{ $organization->website }}</p>
                    <p><strong>Print Date:</strong> <span id="currentDate"></span></p>
                </div>

                <div class="footer">
                    <p>{{ $organization->name }} | {{ $organization->website }}</p>
                </div>
            </div>
        </div>
    </div>


    @include('custom.customizer')
    </div>
    </div>
    <script>
        function printDiv(divId) {
            const originalContents = document.body.innerHTML;
            const printContents = document.getElementById(divId).innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        document.getElementById('currentDate').innerText = new Date().toLocaleDateString();
    </script>
@endsection
