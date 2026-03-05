@extends('admin.includes.main')
@section('content')
    <div class="container">
        <h1> Create Post</h1>
        <form action="{{ route('posts.store') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">Title</label>
                    <input type="text" name="title" class="form-control" id="inputEmail4">
                </div>
            </div>
            <div class="form-group">
                <label for="inputAddress">Description</label>
                <input type="text" name="description" class="form-control" id="inputAddress" placeholder="1234 Main St">
            </div>
            <div class="form-group">
                <label for="inputAddress2">Tags</label>
                <input type="text" name="tags" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputCity">Date</label>
                    <input type="date" name="date" class="form-control" id="inputCity">
                </div>
                <div class="form-group col-md-4">
                    <label for="inputState">Status</label>
                    <select id="inputState" name="status" class="form-control">

                        <option value="1">Active</option>
                        <option value="0">Deavtive</option>

                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="inputState">Type</label>
                    <select id="inputState" name="type" class="form-control">

                        <option value="post">post</option>
                        <option value="comment">comment</option>

                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>
    </div>
@endsection
