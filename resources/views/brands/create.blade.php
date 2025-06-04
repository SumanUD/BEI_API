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
            <label class="form-label">Brand Name</label>
            <input type="text" name="brand_name" class="form-control" value="{{ old('brand_name') }}">
        </div>

        {{-- Brand Logo --}}
        <div class="mb-3">
            <label class="form-label">Brand Logo</label>
            <input type="file" name="brand_logo" class="form-control">
        </div>

        <div class="form-group">
            <label for="brand_order">Brand Order (Unique)</label>
            <input type="number" name="brand_order" value="{{ old('brand_order') }}" class="form-control" required>
        </div>


        {{-- Banner Image Sliders --}}
        <div class="mb-3">
            <label class="form-label">Banner Image Sliders</label>
            <div id="bannerImageInputs">
                @if(old('banner_images'))
                    @foreach(old('banner_images') as $key => $bannerImage)
                        <input type="file" name="banner_images[]" class="form-control mb-2">
                    @endforeach
                @else
                    <input type="file" name="banner_images[]" class="form-control mb-2">
                @endif
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="addBannerImageInput()">Add Another Slider Image</button>
        </div>

        {{-- YouTube Video Links --}}
        <div class="mb-3">
            <label class="form-label">YouTube Video Links</label>
            <div id="youtubeLinks">
                @php
                    $youtubeLinks = old('youtube_link', ['']);
                @endphp
                @foreach ($youtubeLinks as $link)
                    <input type="url" name="youtube_link[]" class="form-control mb-2" value="{{ $link }}">
                @endforeach
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="addYouTubeLink()">Add Another YouTube Video Link</button>
        </div>

        {{-- Below Video Text --}}
        <div class="mb-3">
            <label for="below_video_text" class="form-label">Below Video Text</label>
            <textarea name="below_video_text" id="below_video_text" class="form-control" rows="6">{{ old('below_video_text') }}</textarea>
        </div>

        {{-- Image Gallery --}}
        <div class="mb-3">
            <label class="form-label">Image Gallery</label>
            <input type="file" name="image_gallery[]" multiple class="form-control">
        </div>

        {{-- Video Gallery (YouTube URLs) --}}
        <div class="mb-3">
            <label class="form-label">Video Gallery (YouTube or other URLs)</label>
            <div id="videoGalleryInputs">
                @php
                    $videoLinks = old('video_gallery', ['']);
                @endphp
                @foreach ($videoLinks as $video)
                    <input type="url" name="video_gallery[]" class="form-control mb-2" value="{{ $video }}">
                @endforeach
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="addVideoInput()">Add Another Video URL</button>
        </div>

        {{-- Video Gallery Video Complex Inputs --}}
        <div class="mb-3">
            <label class="form-label">Video Gallery Videos (each with URL, Title, Thumbnail)</label>
            <div id="videoGalleryVideoContainer">
                @php
                    $vgv_old = old('video_gallery_video');
                    $videoInputs = $vgv_old['video_input'] ?? [''];
                    $videoTitles = $vgv_old['video_title'] ?? [''];
                @endphp

                @foreach ($videoInputs as $index => $videoInput)
                    <div class="video-gallery-video-group border rounded p-3 mb-3">
                        <div class="mb-2">
                            <label>Video </label>
                        <input type="file" name="video_gallery_video[video_input][]" class="form-control" accept="video/*">
                        </div>
                        <div class="mb-2">
                            <label>Video Title</label>
                            <input type="text" name="video_gallery_video[video_title][]" class="form-control" value="{{ $videoTitles[$index] ?? '' }}">
                        </div>
                        <div class="mb-2">
                            <label>Thumbnail Picture</label>
                            <input type="file" name="video_gallery_video[thumbnail_picture][]" class="form-control">
                            @if(isset($vgv_old['thumbnail_picture'][$index]))
                                <small>Previously uploaded file: {{ $vgv_old['thumbnail_picture'][$index] }}</small>
                            @endif
                        </div>
                        <button type="button" class="btn btn-danger btn-sm remove-video-group">Remove This Video</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-sm btn-secondary" id="addVideoGalleryVideoBtn">Add Another Video</button>
        </div>

        <button type="submit" class="btn btn-primary">Create Brand</button>
    </form>
@endsection

@section('js')
    {{-- CKEditor --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#below_video_text'))
            .catch(error => { console.error(error); });

        // Banner Images
        function addBannerImageInput() {
            const container = document.getElementById('bannerImageInputs');
            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'banner_images[]';
            input.className = 'form-control mb-2';
            container.appendChild(input);
        }

        // YouTube Video Links
        function addYouTubeLink() {
            const container = document.getElementById('youtubeLinks');
            const input = document.createElement('input');
            input.type = 'url';
            input.name = 'youtube_link[]';
            input.className = 'form-control mb-2';
            container.appendChild(input);
        }

        // Video Gallery URLs
        function addVideoInput() {
            const container = document.getElementById('videoGalleryInputs');
            const input = document.createElement('input');
            input.type = 'url';
            input.name = 'video_gallery[]';
            input.className = 'form-control mb-2';
            container.appendChild(input);
        }

        // Video Gallery Video Groups (URL + Title + Thumbnail)
        document.getElementById('addVideoGalleryVideoBtn').addEventListener('click', function () {
            const container = document.getElementById('videoGalleryVideoContainer');

            const group = document.createElement('div');
            group.className = 'video-gallery-video-group border rounded p-3 mb-3';

            group.innerHTML = `
                <div class="mb-2">
                    <label>Video</label>
                    <input type="file" name="video_gallery_video[video_input][]" class="form-control" accept="video/*">
                </div>
                <div class="mb-2">
                    <label>Video Title</label>
                    <input type="text" name="video_gallery_video[video_title][]" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Thumbnail Picture</label>
                    <input type="file" name="video_gallery_video[thumbnail_picture][]" class="form-control" required>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-video-group">Remove This Video</button>
            `;

            container.appendChild(group);
        });

        // Remove video group dynamically
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-video-group')) {
                e.target.closest('.video-gallery-video-group').remove();
            }
        });
    </script>
@endsection
