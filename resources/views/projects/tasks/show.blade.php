@extends('layouts.admin')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">

            @include('custom.project-counter')


            <div class="d-flex align-items-center gap-6 mb-6 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">{{ $task->name }} - Task Details</h6>

                <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">
                    <a href="{{ route('milestone.tasks', ['milestone_ulid' => $task->milestone?->id]) }}"
                        style="color:white;">Go back</a>
                </span>
            </div>

            <div class="row">
                <div class="col-md-6 border-end">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <div class="mt-4 text-center">
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                            </div>

                            @if ($task->status?->value == 'pending')
                                <div class="mt-1 mb-3 text-start">
                                    <form action="{{ route('milestone.tasks.markAsDone', $task->ulid) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success ml-4">Mark as Done</button>
                                    </form>
                                </div>
                            @endif
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>Name:</strong> {{ $task->name }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Description:</strong> {{ $task->description }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Task Status:</strong> {{ Str::ucfirst($task->status?->value) }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Start Date:</strong> {{ $task->start_date }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Milestone Name:</strong> {{ $task->milestone?->name }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Project Name:</strong> {{ $task->milestone?->project?->name }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Created:</strong> {{ $task->created_at->diffForHumans() }}
                                </li>
                            </ul>

                            @hasanyrole('admin|personnel')
                                <div class="mt-4 text-center">
                                    <a href="#" class="btn btn-primary">Edit Task</a>
                                </div>
                            @endrole

                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">Task Images</h5>
                        </div>
                        <div class="mt-4 text-center">
                            @if (session('image-success'))
                                <div class="alert alert-success">
                                    {{ session('image-success') }}
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <form action="{{ route('task.image.upload', $task->ulid) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple
                                        required>
                                    <button type="submit" class="btn btn-secondary">Upload</button>
                                </div>
                            </form>

                            @if ($task->images->count())
                                <div class="mt-3">
                                    <div class="row">
                                        @foreach ($task->images as $image)
                                            <div class="col-md-4 mb-2">
                                                <img src="{{ asset('storage/task_images/' . $image->name) }}" class="img-fluid rounded"
                                                    alt="Task Image">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h2 class="mb-3">Task Chat</h2>
                    <div class="card">
                        <div class="mt-4 text-center">
                            @if (session('chat-success'))
                                <div class="alert alert-success">
                                    {{ session('chat-success') }}
                                </div>
                            @endif
                        </div>
                        <div class="card-body" id="chat-box" style="height: 300px; overflow-y: auto;">
                            @foreach ($messages as $message)
                                <div class="mb-2">
                                    <strong>{{ $message->sender->name }}:</strong> {{ $message->message }}
                                </div>
                            @endforeach
                        </div>

                        <div class="card-footer">

                            <form action="{{ route('task.chat.send', $task->ulid) }}" method="POST" class="mt-3">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="message" class="form-control"
                                        placeholder="Type a message..." required>
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    @include('custom.customizer')
    </div>
    </div>
@endsection
