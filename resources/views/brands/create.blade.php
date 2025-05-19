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


        {{-- Brand Name --}}
        <div class="mb-3">
            <label class="form-label">Banner Name</label>
            <div id="brandName">
                <input type="text" name="brand_name" class="form-control mb-2">
            </div>
        </div>

        {{-- Brand Name --}}
        <div class="mb-3">
            <label class="form-label">Banner logo</label>
            <div id="brandLogo">
                <input type="file" name="brand_logo" class="form-control mb-2">
            </div>
        </div>

        {{-- Brand Image Sliders --}}
        <div class="mb-3">
            <label class="form-label">Banner Image Sliders</label>
            <div id="bannerImageInputs">
                <input type="file" name="banner_images[]" class="form-control mb-2">
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="addBannerImageInput()">Add Another Slider
                Image</button>
        </div>

        {{-- YouTube Video Link --}}
        <div class="mb-3">
            <label class="form-label">YouTube Video Link</label>
            <div id="youtubeLinks">
                @php
                    $youtubeLinks = old('youtube_link');
                    $youtubeLinks = is_array($youtubeLinks) ? $youtubeLinks : [''];
                @endphp

                @foreach ($youtubeLinks as $link)
                    <input type="url" name="youtube_link[]" class="form-control mb-2" value="{{ $link }}">
                @endforeach
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="addYouTubeLink()">Add Another YouTube Video
                Link</button>
        </div>

        {{-- Below Video Text --}}
        <div class="mb-3">
            <label for="below_video_text" class="form-label">Below Video Text</label>
            <textarea name="below_video_text" id="below_video_text" class="form-control"
                rows="15">{{ old('below_video_text') }}</textarea>
        </div>

        {{-- Image Gallery --}}
        <div class="mb-3">
            <label class="form-label">Image Gallery</label>
            <input type="file" name="image_gallery[]" multiple class="form-control">
        </div>

        {{-- Video Gallery --}}
        <div class="mb-3">
            <label class="form-label">Video Gallery (YouTube or other URLs)</label>
            <div id="videoGalleryInputs">
                @php
                    $videoLinks = old('video_gallery');
                    $videoLinks = is_array($videoLinks) ? $videoLinks : [''];
                @endphp

                @foreach ($videoLinks as $video)
                    <input type="url" name="video_gallery[]" class="form-control mb-2" value="{{ $video }}">
                @endforeach
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
        // CKEditor init
        ClassicEditor
            .create(document.querySelector('#below_video_text'))
            .catch(error => {
                console.error(error);
            });

        // Add input for Video Gallery
        function addVideoInput() {
            const container = document.getElementById('videoGalleryInputs');
            const input = document.createElement('input');
            input.type = 'url';
            input.name = 'video_gallery[]';
            input.className = 'form-control mb-2';
            container.appendChild(input);
        }

        // Add input for Banner Image Sliders
        function addBannerImageInput() {
            const container = document.getElementById('bannerImageInputs');
            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'banner_images[]';
            input.className = 'form-control mb-2';
            container.appendChild(input);
        }

        // Add input for YouTube Video Links
        function addYouTubeLink() {
            const container = document.getElementById('youtubeLinks');
            const input = document.createElement('input');
            input.type = 'url';
            input.name = 'youtube_link[]';
            input.className = 'form-control mb-2';
            container.appendChild(input);
        }
    </script>
@endsection