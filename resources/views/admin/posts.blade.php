@extends('admin.includes.main')

@section('content')
    <div class="container-fluid px-4 py-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 fw-bold">Posts</h4>
                <small class="text-muted">{{ count($posts) }} total posts</small>
            </div>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Create Post
            </a>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Table --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">
                @if($posts->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-newspaper fa-3x mb-3 d-block"></i>
                        No posts found. <a href="{{ route('posts.create') }}">Create your first post</a>.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Tags</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($posts as $post)
                                <tr>
                                    <td class="text-muted small">{{ $post->id }}</td>

                                    <td class="fw-semibold">
                                        {{ Str::limit($post->title, 40) }}
                                    </td>

                                    <td class="text-muted small">
                                        {{ Str::limit($post->description, 60) }}
                                    </td>

                                    <td>
                                      {{ $post->tags }}
                                    </td>

                                    <td class="text-muted small">
                                        {{ \Carbon\Carbon::parse($post->date)->format('M d, Y') }}
                                    </td>

                                    <td>
                                        @if($post->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge bg-info text-dark">{{ ucfirst($post->type) }}</span>
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('posts.edit', $post->id) }}"
                                           class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <a href="{{ route('posts.delete', $post->id) }}" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
