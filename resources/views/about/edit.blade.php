@extends('adminlte::page')

@section('title', 'Edit About Us')

@section('content_header')
    <h1>Edit About Us</h1>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following issues:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('about-us.update', $about->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Banner Video -->
        <div class="mb-3">
            <label for="banner_video" class="form-label">Banner Video</label>
            <input type="file" name="banner_video" class="form-control">
            @if($about->banner_video)
                <a href="{{ asset('storage/' . $about->banner_video) }}" target="_blank">Current Video</a>
            @endif
        </div>

        <!-- About Image -->
        <div class="mb-3">
            <label for="about_image" class="form-label">About Image</label>
            <input type="file" name="about_image" class="form-control">
            @if($about->about_image)
                <img src="{{ asset('storage/' . $about->about_image) }}" alt="About Image" width="100">
            @endif
        </div>

        <!-- About Background Image -->
        <div class="mb-3">
            <label for="about_bg_image" class="form-label">About Background Image</label>
            <input type="file" name="about_bg_image" class="form-control">
            @if($about->about_bg_image)
                <img src="{{ asset('storage/' . $about->about_bg_image) }}" alt="Background Image" width="100">
            @endif
        </div>

        <!-- Team Members -->
        <div class="mb-3">
            <label for="team_members" class="form-label">Team Members</label>
            <div id="teamMembersInputs">
                @foreach($about->team_members as $index => $team_member)
                    <div class="teamMember">
                        <input type="file" name="team_members[{{ $index }}][image]" class="form-control mb-2">
                        @if($team_member['image'])
                            <img src="{{ asset('storage/' . $team_member['image']) }}" alt="Team Image" width="100">
                        @endif
                        <textarea name="team_members[{{ $index }}][description]" class="form-control mb-2">{{ $team_member['description'] }}</textarea>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-sm btn-secondary" onclick="addTeamMember()">Add Another Team Member</button>
        </div>

        <!-- Social Links -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $about->email }}">
        </div>
        <div class="mb-3">
            <label for="linkedin" class="form-label">LinkedIn</label>
            <input type="url" name="linkedin" class="form-control" value="{{ $about->linkedin }}">
        </div>

        <!-- About Gallery -->
        <div class="mb-3">
            <label for="about_gallery" class="form-label">About Gallery Images</label>
            <input type="file" name="about_gallery[]" multiple class="form-control">
            @foreach($about->about_gallery as $image)
                <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image" width="100">
            @endforeach
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update About Us</button>
    </form>
@endsection

@section('js')
    <script>
        function addTeamMember() {
            const container = document.getElementById('teamMembersInputs');
            const newMember = document.createElement('div');
            newMember.classList.add('teamMember');
            newMember.innerHTML = `
                <input type="file" name="team_members[][image]" class="form-control mb-2">
                <textarea name="team_members[][description]" class="form-control mb-2" placeholder="Team member description"></textarea>
            `;
            container.appendChild(newMember);
        }
    </script>
@endsection
