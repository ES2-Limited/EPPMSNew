@extends('layouts.admin')

@section('content')

    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-2 pb-3 justify-content-between">

                <h6 class="mb-0 fs-4"> {{ $project->name }} Milestone Page
                </h6>

                <span class="badge text-bg-secondary rounded-4 px-3 py-1 lh-sm">
                    <a href="{{ route('project.milestones', $project->ulid) }}" style="color:white;">Back</a>
                </span>
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
                    <form method="POST"
                        action="{{ route('project.milestones.update', ['milestone_ulid' => $milestone->ulid]) }}">
                        @csrf
                        @method('PUT')
                        <div class="alert alert-primary">
                            <h5>The max amount for milestone that can be added is:
                                {{ number_format($project->cost_left(), 2) }}</h5>
                        </div>
                        <div>
                            <div class="card-body">
                                <input id="p_ulid" name="project_ulid" type="hidden"
                                    value="{{ $project->ulid ?? '' }}" />
                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="name">Milestone Name</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            placeholder="Milestone Name" required value="{{ old('name', $milestone->name) }}" />
                                    </div>
                                    <div class="mb-3 col-md-8">
                                        <label class="form-label" for="milestoneDescription">Milestone Description</label>
                                        <textarea class="form-control" name="description" id="description" rows="1" placeholder="Milestone Description"
                                            required>{{ old('description', $milestone->description) }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Milestone Cost</label>
                                            <select class="form-select" id="cost_type" name="cost_type" required>
                                                <option value="payable" {{ $milestone->cost_type == 'payable' ? 'selected' : '' }}>Payable</option>
                                                <option value="non-payable" {{ $milestone->cost_type == 'non-payable' ? 'selected' : '' }}>Non-Payable</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Milestone Amount</label>
                                        <input type="number" class="form-control" name="amount"
                                            value="{{ old('amount', $milestone->amount) }}" id="amount" placeholder="0.00" min="0"
                                            max="{{ $project->cost_left() }}" />
                                    </div>

                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="deadline">Milestone Deadline</label>
                                        <input type="date" class="form-control" value="{{ old('deadline', $project->deadline) }}"
                                            name="deadline" id="deadline" required />
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="card-body border-top">
                                        <button type="submit" class="btn btn-primary">
                                            Update
                                        </button>
                                        <button type="reset" class="btn bg-danger-subtle text-danger ms-6">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('custom.customizer')
    </div>
    </div>
    <script>
        document.getElementById('cost_type').addEventListener('change', function() {
            let amountInput = document.getElementById('amount');

            if (this.value === 'non-payable') {
                amountInput.disabled = true;
                amountInput.value = '';
            } else {
                amountInput.disabled = false;
            }
        });
    </script>
@endsection
