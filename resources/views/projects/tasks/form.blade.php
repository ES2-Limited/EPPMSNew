@extends('layouts.admin')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-6 mb-2 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4"> {{ $milestone->name }} Tasks Page</h6>
                <span class="badge text-bg-secondary rounded-4 px-3 py-1 lh-sm">
                    <a href="{{ route('milestone.tasks', $milestone->ulid) }}" style="color:white;">Back</a>
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
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-transparent border-bottom py-3">
                            <h5 class="mb-0 fw-bold text-dark">{{ $isEdit ? 'Edit Milestone Task' : 'Add Milestone Task' }}</h5>
                            <small class="text-muted">Fill in the task details, budget limits, and schedule below.</small>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-primary d-flex align-items-center gap-2 border-0 rounded-3 mb-4" role="alert">
                                <i class="fas fa-coins fs-4 text-primary"></i>
                                <div>
                                    <span class="fw-semibold">Budget Allowance:</span> Maximum amount that can be added for this task is
                                    <strong class="text-primary">₦{{ number_format($milestone->cost_left() + ($isEdit ? $task->cost : 0), 2) }}</strong>
                                </div>
                            </div>

                            <form method="POST"
                                action="{{ $isEdit ? route('milestone.tasks.update', [$task->ulid]) : route('milestone.tasks.store') }}"
                                onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Saving...';">
                                @csrf
                                @if ($isEdit)
                                    @method('PUT')
                                @endif

                                <input id="milestone_id" name="milestone_id" type="hidden" value="{{ $milestone->id ?? '' }}" />

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Milestone Name</label>
                                        <input type="text" class="form-control bg-light" value="{{ $milestone->name }}" readonly disabled />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="taskname">Task Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="taskname" placeholder="e.g. Foundation Excavation"
                                            value="{{ old('name', $task->name) }}" name="name" required />
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="taskstart">Start Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="taskstart" name="start_date"
                                            value="{{ old('start_date', $task->start_date) }}" required />
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="taskdue">Due Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="taskdue" name="due_date"
                                            value="{{ old('due_date', $task->due_date) }}" required />
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="taskcost">Task Cost (₦) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control" id="taskcost" placeholder="0.00" min="0"
                                            name="cost" value="{{ old('cost', $task->cost) }}"
                                            max="{{ $milestone->cost_left() + ($isEdit ? $task->cost : 0) }}" required />
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label" for="taskDescription">Task Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="taskDescription" rows="3" placeholder="Provide detailed operational requirements..." name="description" required>{{ old('description', $task->description) }}</textarea>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-end gap-2 pt-4 mt-4 border-top">
                                    <a href="{{ route('milestone.tasks', $milestone->id) }}" class="btn btn-outline-secondary px-4">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4" id="submit-button">
                                        <i class="fas fa-check me-1"></i> {{ $isEdit ? 'Update Task' : 'Create Task' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
    @include('custom.customizer')
    </div>
    </div>
@endsection
