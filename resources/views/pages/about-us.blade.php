@extends('adminlte::page')

@section('title', 'About Us')

@section('content_header')
    <h1>About Us Page</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('about.save') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Desktop Video (as string) -->
        <div class="mb-3">
            <label>Desktop Video (URL or path)</label>
            <input type="text" name="desktop_video" class="form-control" value="{{ old('desktop_video', $about?->desktop_video) }}">
            @if($about?->desktop_video)
                <video src="{{ $about->desktop_video }}" controls width="200" class="mt-2"></video>
            @endif
        </div>

        <!-- Mobile Video (as string) -->
        <div class="mb-3">
            <label>Mobile Video (URL or path)</label>
            <input type="text" name="mobile_video" class="form-control" value="{{ old('mobile_video', $about?->mobile_video) }}">
            @if($about?->mobile_video)
                <video src="{{ $about->mobile_video }}" controls width="200" class="mt-2"></video>
            @endif
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" id="editor" class="form-control">{{ old('description', $about?->description) }}</textarea>
        </div>

        <!-- Right Image -->
        <div class="mb-3">
            <label>Right Image</label>
            <input type="file" name="right_image" class="form-control">
            @if($about?->right_image)
                <img src="{{ asset('storage/' . $about->right_image) }}" width="120" class="mt-2">
            @endif
        </div>

        <!-- Team Members -->
        <div id="teamMembersContainer">
            @if($about && $about->team_members)
                @foreach($about->team_members as $index => $member)
                    @include('pages.partials.team-member', ['member' => $member, 'index' => $index])
                @endforeach
            @else
                @include('pages.partials.team-member', ['index' => 0])
            @endif
        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addTeamMember()">Add Team Member</button>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection

@section('js')
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');

    let memberIndex = {{ $about?->team_members ? count($about->team_members) : 1 }};

    function addTeamMember() {
        fetch(`/team-member-template?index=${memberIndex}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('teamMembersContainer').insertAdjacentHTML('beforeend', html);
                memberIndex++;
            })
            .catch(error => {
                console.error("Error loading team member block:", error);
            });
    }
</script>
@endsection
