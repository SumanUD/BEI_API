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

        {{-- Brand Name --}}
        <div class="mb-3">
            <label class="form-label">Brand Name</label>
            <input type="text" name="brand_name" class="form-control" 
                   value="{{ old('brand_name', $brand->brand_name) }}">
        </div>

        {{-- Brand Logo --}}
        <div class="mb-3">
            <label class="form-label">Brand Logo</label><br>
            @if($brand->brand_logo)
                <img src="{{ asset('storage/' . $brand->brand_logo) }}" alt="Brand Logo" style="max-height: 120px; margin-bottom:10px;">
            @endif
            <input type="file" name="brand_logo" class="form-control">
            <small class="form-text text-muted">Upload to replace existing logo.</small>
        </div>

        <div class="form-group">
            <label for="brand_order">Brand Order (Must be Unique)</label>
            <input type="number" name="brand_order" value="{{ old('brand_order', $brand->id ?? '') }}" class="form-control" required>
        </div>


        {{-- Banner Image Sliders --}}
        <div class="mb-3">
            <label class="form-label">Banner Image Sliders</label>
            <div id="bannerImageInputs">
                @php
                    $oldBanners = old('banner_images') ?: json_decode($brand->banner_images, true) ?: [];
                @endphp
                @if(count($oldBanners) > 0)
                    @foreach($oldBanners as $key => $banner)
                        <div class="mb-2">
                            @if(is_string($banner) && $banner)
                                <img src="{{ asset('storage/' . $banner) }}" style="max-height: 100px; display:block; margin-bottom:5px;">
                            @endif
                            <input type="file" name="banner_images[]" class="form-control mb-2">
                        </div>
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
                    $youtubeLinks = old('youtube_link', json_decode($brand->youtube_link, true) ?: ['']);
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
            <textarea name="below_video_text" id="below_video_text" class="form-control" rows="6">{{ old('below_video_text', $brand->below_video_text) }}</textarea>
        </div>

        {{-- Image Gallery --}}
        <div class="mb-3">
            <label class="form-label">Image Gallery</label>
            <div>
                @php
                    $oldGallery = json_decode($brand->image_gallery, true) ?: [];
                @endphp
                @foreach($oldGallery as $img)
                    @if($img)
                        <img src="{{ asset('storage/' . $img) }}" style="max-height: 100px; margin-right: 10px; margin-bottom: 10px;">
                    @endif
                @endforeach
            </div>
            <input type="file" name="image_gallery[]" multiple class="form-control">
            <small class="form-text text-muted">Upload to add more images. Existing images remain unless removed server-side.</small>
        </div>

        {{-- Video Gallery (YouTube URLs) --}}
        <div class="mb-3">
            <label class="form-label">Video Gallery (YouTube or other URLs)</label>
            <div id="videoGalleryInputs">
                @php
                    $videoLinks = old('video_gallery', json_decode($brand->video_gallery, true) ?: ['']);
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
                    $videoInputs = $vgv_old['video_input'] ?? null;
                    $videoTitles = $vgv_old['video_title'] ?? null;
                    $videoThumbnails = $vgv_old['thumbnail_picture'] ?? null;

                    if (!$vgv_old) {
                        $existingVideos = json_decode($brand->video_gallery_video, true) ?: [];
                    } else {
                        $existingVideos = [];
                    }
                @endphp

                @if($videoInputs && is_array($videoInputs))
                    @foreach($videoInputs as $index => $videoInput)
                        <div class="video-gallery-video-group border rounded p-3 mb-3">
                            <div class="mb-2">
                                <label>Video</label>
                                <input type="file" name="video_gallery_video[video_input][]" class="form-control" accept="video/*">
                                @if(isset($vgv_old['old_video_input'][$index]))
                                    <small>Previously uploaded file: {{ $vgv_old['old_video_input'][$index] }}</small>
                                @endif
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
                @elseif(count($existingVideos) > 0)
                    @foreach($existingVideos as $index => $video)
                        <div class="video-gallery-video-group border rounded p-3 mb-3">
                            <div class="mb-2">
                                <label>Video</label><br>
                                @if(!empty($video['video_input']))
                                    <video width="320" height="180" controls style="display:block; margin-bottom: 5px;">
                                        <source src="{{ asset('storage/' . $video['video_input']) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                                <input type="file" name="video_gallery_video[video_input][]" class="form-control" accept="video/*">
                                <input type="hidden" name="video_gallery_video[old_video_input][]" value="{{ $video['video_input'] ?? '' }}">
                            </div>
                            <div class="mb-2">
                                <label>Video Title</label>
                                <input type="text" name="video_gallery_video[video_title][]" class="form-control" value="{{ $video['video_title'] ?? '' }}">
                            </div>
                            <div class="mb-2">
                                <label>Thumbnail Picture</label><br>
                                @if(!empty($video['thumbnail_picture']))
                                    <img src="{{ asset('storage/' . $video['thumbnail_picture']) }}" style="max-height: 100px; display:block; margin-bottom: 5px;">
                                @endif
                                <input type="file" name="video_gallery_video[thumbnail_picture][]" class="form-control">
                                <input type="hidden" name="video_gallery_video[old_thumbnail_picture][]" value="{{ $video['thumbnail_picture'] ?? '' }}">
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-video-group">Remove This Video</button>
                        </div>
                    @endforeach
                @else
                    {{-- Default one empty group --}}
                    <div class="video-gallery-video-group border rounded p-3 mb-3">
                        <div class="mb-2">
                            <label>Video</label>
                            <input type="file" name="video_gallery_video[video_input][]" class="form-control" accept="video/*">
                        </div>
                        <div class="mb-2">
                            <label>Video Title</label>
                            <input type="text" name="video_gallery_video[video_title][]" class="form-control" value="">
                        </div>
                        <div class="mb-2">
                            <label>Thumbnail Picture</label>
                            <input type="file" name="video_gallery_video[thumbnail_picture][]" class="form-control">
                        </div>
                        <button type="button" class="btn btn-danger btn-sm remove-video-group">Remove This Video</button>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-sm btn-secondary" id="addVideoGalleryVideoBtn">Add Another Video</button>
        </div>

        <button type="submit" class="btn btn-primary">Update Brand</button>
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

        // Video Gallery Video Groups
        document.getElementById('addVideoGalleryVideoBtn').addEventListener('click', () => {
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
                    <input type="text" name="video_gallery_video[video_title][]" class="form-control" value="">
                </div>
                <div class="mb-2">
                    <label>Thumbnail Picture</label>
                    <input type="file" name="video_gallery_video[thumbnail_picture][]" class="form-control">
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-video-group">Remove This Video</button>
            `;

            container.appendChild(group);
        });

        // Remove video group button
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-video-group')) {
                e.target.closest('.video-gallery-video-group').remove();
            }
        });
    </script>
@endsection
