@extends('layouts.admin')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">

            <div class="d-flex align-items-center gap-6 mb-2 pb-3 justify-content-between">
                <h6 class="mb-0 fs-4">Milestone Details Page</h6>
                <span class="badge text-bg-secondary rounded-4 px-3 py-1 lh-sm">
                    <a href="{{ route('project.milestones', $milestone->project?->ulid) }}" style="color:white;"> All
                        Milestones</a>
                </span>
            </div>

            <div class="container">
                <div class="row mb-4">
                    <div class="col-md-6 border-end">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <p><strong>Milestone Name:</strong> {{ $milestone->name }}</p>
                                <p><strong>Project Name:</strong> {{ $milestone->project?->name }}</p>
                                <p><strong>Milestone Amount:</strong> {{ number_format($milestone->amount, 2) }}</p>
                                <p><strong>Description:</strong> {{ $milestone->description }}</p>
                                <li class="list-group-item">
                                    <strong>Created:</strong> {{ $milestone->created_at->diffForHumans() }}
                                </li>
                            </div>
                        </div>
                        <div class="card mt-3">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">Milestone Images</h5>
                            </div>
                            <div class="mt-4 text-center">
                                @if (session('image-success'))
                                    <div class="alert alert-success">
                                        {{ session('image-success') }}
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <form action="{{ route('milestone.image.upload', $milestone->ulid) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group">
                                        <input type="file" name="images[]" class="form-control" accept="image/*"
                                            multiple required>
                                        <button type="submit" class="btn btn-secondary">Upload</button>
                                    </div>
                                </form>

                                @if ($milestone->images->count())
                                    <div class="mt-3">
                                        <div class="row">
                                            @foreach ($milestone->images as $image)
                                                <div class="col-md-4 mb-2">
                                                    <img src="{{ asset('storage/milestone_images/' . $image->name) }}"
                                                        class="img-fluid rounded" alt="Task Image">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>



                    <div class="col-md-6">
                        <h2 class="mb-3">Milestone Chat</h2>
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
                                <form action="{{ route('milestones.chat.send', $milestone->ulid) }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="message" class="form-control"
                                            placeholder="Type a message..." required>
                                        <button type="submit" class="btn btn-success">Send</button>
                                    </div>
                                </form>
                            </div>
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
