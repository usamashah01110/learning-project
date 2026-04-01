@extends('admin.includes.main')
@section('content')
    <div class="container">
        <h1> Create Post</h1>
        


        <form action="{{ route('posts.store') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="inputEmail4" value="{{ old('title') }}">
                    @error('title')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="inputAddress">Description</label>
                <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" id="inputAddress" value="{{ old('description') }}">
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="inputAddress2">Tags</label>
                <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror" id="inputAddress2" placeholder="Apartment, studio, or floor" value="{{ old('tags') }}">
                @error('tags')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputCity">Date</label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" id="inputCity" value="{{ old('date') }}">
                    @error('date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="inputState">Status</label>
                    <select id="inputState" name="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-2">
                    <label for="inputState">Type</label>
                    <select id="inputState" name="type" class="form-control @error('type') is-invalid @enderror">
                        <option>Select Your Type</option>
                        <option value="post" {{ old('type') == 'post' ? 'selected' : '' }}>post</option>
                        <option value="comment" {{ old('type') == 'comment' ? 'selected' : '' }}>comment</option>
                    </select>
                    @error('type')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>
    </div>
@endsection
