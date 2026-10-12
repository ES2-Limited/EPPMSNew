@extends('layouts.admin')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Project Report Page</h6>

                <div>
                    <span class="badge text-bg-success rounded-4 px-3 py-1 lh-sm">
                        <a href="{{ route('reports.index') }}" style="color: white">
                            Back to Reports</a>
                    </span>
                </div>
            </div>

            <div class="container mt-4">
                <div class="text-center">
                    <img src="{{ asset('/assets/images/logos/ncaalogo.png') }}" alt="Logo" class="img-fluid"
                        style="max-height: 30px;">
                    <h3 class="d-inline-block ms-1 align-middle">Nigerian Civil Aviation Authority HQ, Abuja</h3>
                </div>

                <div class="text-center"> <br>
                    <h5>Project Milestone Completion/Interim Payment Certificate (IPC)</h5>
                </div>

                <div class="mt-4">
                    <p class="h6"><strong>Certificate No:</strong> NCAA129....</p>
                    <p class="h6"><strong>Date:</strong> {{ now()->format('l, F j, Y g:i A') }}</p>
                </div>

                <div class="mt-4">
                    <div>
                        <h5>Project Details</h5>
                        <ul>
                            <li>Project Name: {{ $project->name }}</li>
                            <li>Project Cost: {{ $project->cost }}</li>
                            <li>Project Progress: {{ $project->get_progress() }}%</li>
                            <li>Created: {{ $project->created_at->diffForHumans() }}</li>
                        </ul>
                    </div>
                    <hr>

                    <div>
                        <h5>Milestones Description</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>No. of Tasks</th>
                                </tr>
                            </thead>
                            <tbody id="itemTable">
                                @forelse ($project->milestones as $milestone)
                                    <tr>
                                        <td>{{ $milestone->name }}</td>
                                        <td>{{ $milestone->amount }}</td>
                                        <td>{{ Str::ucfirst($milestone->status?->value) }}</td>
                                        <td>
                                            {{ $milestone->tasks?->count() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr colspan="3">
                                        No project milestones registered!!!
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                    <hr>

                    <div>
                        <h5>Payment Details</h5>
                        <ul>
                            <li></li>
                            <li>Milestone Description: [Insert Milestone Description] </li>
                            <li>Milestone Amount: [Insert Amount Allocated for Milestone]</li>
                            <li>Amount Certified for Payment: [Insert Amount Certified] </li>
                            <li>Cumulative Amount Paid to Date: [Insert Total Amount Paid to Date] </li>
                        </ul>
                    </div>
                    <hr>

                    <div>
                        <h5>Authorizations:</h5>

                        <h4><b>Certified By:</b></h4>
                        <ul>
                            <li>Name: {{ $project->project_personnel }}</li>
                            <li>Designation: [insert supervisor Designation]</li>
                            <li>Signature:[Insert signature]</li>
                            <li>Date: [Insert authorization date]</li>
                        </ul>
                    </div>
                    <hr>

                    <div>
                        <h5> <b>Approved By:</b></h5>
                        <ul>
                            <li><b>Project Director Name:</b>  {{ $project->creator?->name  }} </li>
                            <li><b>Designation:</b> {{ $project->creator->hasRole('admin') ? "Admin" : "User" }} </li>
                            <li>Signature:[Insert signature]</li>
                            <li><b>Date:</b> {{ $project->updated_at->diffForHumans() }}</li>
                        </ul>
                    </div>
                    <hr>
                </div>
            </div>

        </div>
    </div>
    @include('custom.customizer')
    </div>
    </div>
@endsection
