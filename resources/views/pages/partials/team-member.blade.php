<div class="team-member border p-3 mb-3">
    <h2>Team Member {{ $index + 1 }}</h2>

    <div class="mb-2">
        <label>Name</label>
        <input type="text" name="team_members[{{ $index }}][name]" class="form-control" value="{{ $member['name'] ?? '' }}">
    </div>

    <div class="mb-2">
        <label>Photo</label>
        <input type="file" name="team_members[{{ $index }}][photo]" class="form-control">
        @if(!empty($member['photo']))
            <img src="{{ asset('storage/' . $member['photo']) }}" width="100" class="mt-2">
            <input type="hidden" name="team_members[{{ $index }}][old_photo]" value="{{ $member['photo'] }}">
        @endif
    </div>

    <div class="mb-2">
        <label>Description</label>
        <textarea name="team_members[{{ $index }}][description]" class="form-control">{{ $member['description'] ?? '' }}</textarea>
    </div>

    <div class="mb-2">
        <label>Email</label>
        <input type="email" name="team_members[{{ $index }}][email]" class="form-control" value="{{ $member['email'] ?? '' }}">
    </div>

    <div class="mb-2">
        <label>LinkedIn</label>
        <input type="url" name="team_members[{{ $index }}][linkedin]" class="form-control" value="{{ $member['linkedin'] ?? '' }}">
    </div>

    <div class="mb-2">
        <label>Website</label>
        <input type="url" name="team_members[{{ $index }}][website]" class="form-control" value="{{ $member['website'] ?? '' }}">
    </div>
</div>
