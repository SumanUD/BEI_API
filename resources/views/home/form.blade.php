@extends('adminlte::page')

@section('title', 'Home Page Videos')

@section('content_header')
    <h1>Home Page - Upload Videos</h1>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('homepag.save') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="desktop_full_video" class="form-label">Desktop Full Video</label>
            <input type="file" class="form-control" name="desktop_full_video">
            @if ($home && $home->desktop_full_video)
                <video src="{{ asset('storage/' . $home->desktop_full_video) }}" controls width="300" class="mt-2"></video>
            @endif
        </div>

        <div class="mb-3">
            <label for="mobile_full_video" class="form-label">Mobile Full Video</label>
            <input type="file" class="form-control" name="mobile_full_video">
            @if ($home && $home->mobile_full_video)
                <video src="{{ asset('storage/' . $home->mobile_full_video) }}" controls width="300" class="mt-2"></video>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">
            {{ $home && ($home->desktop_full_video || $home->mobile_full_video) ? 'Update Videos' : 'Save Videos' }}
        </button>
    </form>
@endsection
