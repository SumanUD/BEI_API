@extends('adminlte::page')

@section('title', 'Website Loader')

@section('content_header')
    <h1>Update Website Loader GIF</h1>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('website-loader.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="gif" class="form-label">Upload GIF</label>
            <input type="file" name="gif" class="form-control" accept="image/gif">
            @error('gif')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        @if($loader->gif_path)
            <div class="mb-3">
                <label>Current GIF:</label><br>
                <img src="{{ asset('storage/' . $loader->gif_path) }}" alt="Loader GIF" style="width:150px;">
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Update Loader</button>
    </form>
@endsection
