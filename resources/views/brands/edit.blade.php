@extends('adminlte::page')

@section('title', 'Edit Brand')

@section('content_header')
    <h1>Edit Brand</h1>
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

    <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="banner_images" class="form-label">Banner Images</label>
            <input type="file" name="banner_images[]" multiple class="form-control">
            <div class="mt-2">
                @foreach ($brand->banner_images as $img)
                    <img src="{{ asset('storage/' . $img) }}" width="80" class="me-1 mb-1" alt="Banner Image">
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label for="youtube_link" class="form-label">YouTube Video Link</label>
            <input type="url" name="youtube_link" class="form-control" value="{{ old('youtube_link', $brand->youtube_link) }}">
        </div>

        <div class="mb-3">
            <label for="below_video_text" class="form-label">Below Video Text</label>
            <textarea name="below_video_text" id="below_video_text" class="form-control" rows="15">{{ old('below_video_text', $brand->below_video_text) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="image_gallery" class="form-label">Image Gallery</label>
            <input type="file" name="image_gallery[]" multiple class="form-control">
            <div class="mt-2">
                @foreach ($brand->image_gallery as $img)
                    <img src="{{ asset('storage/' . $img) }}" width="80" class="me-1 mb-1" alt="Gallery Image">
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label for="video_gallery" class="form-label">Video Gallery (URLs)</label>
            <div id="videoGalleryInputs">
                @foreach ($brand->video_gallery as $url)
                    <input type="url" name="video_gallery[]" class="form-control mb-2" value="{{ $url }}">
                @endforeach
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="addVideoInput()">Add Another Video URL</button>
        </div>

        <button type="submit" class="btn btn-success">Update Brand</button>
    </form>
@endsection

@section('js')
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('below_video_text');

        function addVideoInput() {
            const container = document.getElementById('videoGalleryInputs');
            const input = document.createElement('input');
            input.type = 'url';
            input.name = 'video_gallery[]';
            input.className = 'form-control mb-2';
            container.appendChild(input);
        }
    </script>
@endsection
