@extends('admin.includes.main')
@section('content')
    <div class="container">
        <a href="{{ route('posts.create') }}" class="btn btn-primary"> Create Post</a>
        <br>
        <br>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">tags</th>
            <th scope="col">Date</th>
            <th scope="col">Status</th>
            <th scope="col">Type</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <tr>
                <th scope="row">{{ $post->id }}</th>
                <td>{{ $post->title }}</td>
                <td>{{ $post->description }}</td>
                <td>{{ $post->tags }}</td>
                <td>{{ $post->date }}</td>
                <td>@if($post->status == 1)
                Active @else Deactive @endif</td>
                <td>{{ $post->type }}</td>
                <td>
                    <a href="" class="btn btn-primary">Edit</a>
                    <a href="" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
    </div>
@endsection
