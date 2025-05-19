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

        <div class="mb-3">
            <label for="banner_images" class="form-label">Banner Images</label>
            <input type="file" name="banner_images[]" multiple class="form-control">
            <div class="mt-2">
                @if(is_array($brand->banner_images))
                    @foreach ($brand->banner_images as $img)
                        <img src="{{ asset('storage/' . $img) }}" width="80" class="me-1 mb-1" alt="Banner Image">
                    @endforeach
                @endif
            </div>
        </div>



        <div class="mb-3">
            <label for="youtube_link" class="form-label">YouTube Video Links</label>
            <div id="youtubeLinkInputs">
                @if(is_array($brand->youtube_link) && count($brand->youtube_link) > 0)
                    @foreach ($brand->youtube_link as $url)
                        <input type="url" name="youtube_link[]" class="form-control mb-2" value="{{ $url }}">
                    @endforeach
                @else
                    <input type="url" name="youtube_link[]" class="form-control mb-2" placeholder="Enter YouTube URL">
                @endif
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="addYoutubeInput()">Add Another YouTube
                URL</button>
        </div>

        <div class="mb-3">
            <label for="below_video_text" class="form-label">Below Video Text</label>
            <textarea name="below_video_text" id="below_video_text" class="form-control"
                rows="15">{{ old('below_video_text', $brand->below_video_text) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="image_gallery" class="form-label">Image Gallery</label>
            <input type="file" name="image_gallery[]" multiple class="form-control">
            <div class="mt-2">
                @if(is_array($brand->image_gallery))
                    @foreach ($brand->image_gallery as $img)
                        <img src="{{ asset('storage/' . $img) }}" width="80" class="me-1 mb-1" alt="Gallery Image">
                    @endforeach
                @endif
            </div>
        </div>

        <div class="mb-3">
            <label for="video_gallery" class="form-label">Video Gallery (URLs)</label>
            <div id="videoGalleryInputs">
                @if(is_array($brand->video_gallery) && count($brand->video_gallery) > 0)
                    @foreach ($brand->video_gallery as $url)
                        <input type="url" name="video_gallery[]" class="form-control mb-2" value="{{ $url }}">
                    @endforeach
                @else
                    <input type="url" name="video_gallery[]" class="form-control mb-2" placeholder="Enter Video URL">
                @endif
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

        function addYoutubeInput() {
            const container = document.getElementById('youtubeLinkInputs');
            const input = document.createElement('input');
            input.type = 'url';
            input.name = 'youtube_link[]';
            input.className = 'form-control mb-2';
            input.placeholder = 'Enter YouTube URL';
            container.appendChild(input);
        }

        function addVideoInput() {
            const container = document.getElementById('videoGalleryInputs');
            const input = document.createElement('input');
            input.type = 'url';
            input.name = 'video_gallery[]';
            input.className = 'form-control mb-2';
            input.placeholder = 'Enter Video URL';
            container.appendChild(input);
        }
    </script>
@endsection