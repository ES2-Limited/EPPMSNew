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
                    <form method="POST"
                        action="{{ $isEdit ? route('milestone.tasks.update', [$task->ulid]) : route('milestone.tasks.store') }}"
                        onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Saving...';">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="alert alert-primary">
                            <h5>The max amount for task that can be added is:
                                {{ number_format($milestone->cost_left(), 2) }}</h5>
                        </div>
                        <input id="milestone_id" name="milestone_id" type="hidden" value="{{ $milestone->id ?? '' }}" />
                        <div>
                            <div class="card-body">
                                <h4 class="mb-3 fs-4">Add Milestone Task</h4>

                                <div class="row pt-3">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Milestone Name</label>
                                        <input type="text" class="form-control" value="{{ $milestone->name }}" readonly
                                            disabled />
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="taskname">Task Name</label>
                                        <input type="text" class="form-control" placeholder="Task Name"
                                            value="{{ old('name', $task->name) }}" name="name" />
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="taskstart">Task Start Date</label>
                                        <input type="date" class="form-control" id="taskstart" name="start_date"
                                            value="{{ old('start_date', $task->start_date) }}" />
                                    </div>

                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="taskdue">Task Due Date</label>
                                        <input type="date" class="form-control" id="taskdue" name="due_date"
                                            value="{{ old('due_date', $task->due_date) }}" />
                                    </div>

                                    <div class="mb-3 col-md-4">
                                        <label class="form-label"> Task Cost</label>
                                        <input type="number" class="form-control" placeholder="0.00" min="0"
                                            name="cost" value="{{ old('cost', $task->cost) }}"
                                            max="{{ $milestone->cost_left() + ($isEdit ? $task->cost : 0) }}" />
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="taskDescription">Task Description</label>
                                        <textarea class="form-control" rows="2" placeholder="Task Description" name="description" required>{{ old('description', $task->description) }}</textarea>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="card-body border-top">
                                        <button type="submit" class="btn btn-primary" id="submit-button">
                                            Save
                                        </button>
                                        <button type="reset" class="btn bg-danger-subtle text-danger ms-6">
                                            Cancel
                                        </button>
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
@endsection
