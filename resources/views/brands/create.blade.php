@extends('adminlte::page')

@section('title', 'Create Brand')

@section('content_header')
    <h1>Create Brand</h1>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following issues:<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="banner_images" class="form-label">Banner Image Sliders</label>
            <input type="file" name="banner_images[]" multiple class="form-control">
        </div>

        <div class="mb-3">
            <label for="youtube_link" class="form-label">YouTube Video Link</label>
            <input type="url" name="youtube_link" class="form-control" value="{{ old('youtube_link') }}">
        </div>

        <div class="mb-3">
            <label for="below_video_text" class="form-label">Below Video Text</label>
            <textarea name="below_video_text" id="below_video_text" class="form-control" rows="15">
                {{ old('below_video_text') }}
            </textarea>
        </div>
    
        <div class="mb-3">
            <label for="image_gallery" class="form-label">Image Gallery</label>
            <input type="file" name="image_gallery[]" multiple class="form-control">
        </div>

        <div class="mb-3">
            <label for="video_gallery" class="form-label">Video Gallery (YouTube or other URLs)</label>
            <div id="videoGalleryInputs">
                <input type="url" name="video_gallery[]" class="form-control mb-2">
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="addVideoInput()">Add Another Video URL</button>
        </div>

        <button type="submit" class="btn btn-primary">Create Brand</button>
    </form>
@endsection

@section('js')
    {{-- Load CKEditor 5 --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        function addVideoInput() {
            const container = document.getElementById('videoGalleryInputs');
            const input = document.createElement('input');
            input.type = 'url';
            input.name = 'video_gallery[]';
            input.className = 'form-control mb-2';
            container.appendChild(input);
        }

        ClassicEditor
            .create(document.querySelector('#below_video_text'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
