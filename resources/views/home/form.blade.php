@extends('adminlte::page')

@section('title', 'Home Page Videos')

@section('content_header')
    <h1>Home Page - Video URLs</h1>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('homepag.save') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="desktop_full_video" class="form-label">Desktop Full Video URL</label>
            <input type="text" class="form-control" name="desktop_full_video"
                value="{{ old('desktop_full_video', $home->desktop_full_video ?? '') }}">
            @error('desktop_full_video')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            @if (!empty($home->desktop_full_video))
                <video src="{{ $home->desktop_full_video }}" controls width="300" class="mt-2"></video>
            @endif
        </div>

        <div class="mb-3">
            <label for="mobile_full_video" class="form-label">Mobile Full Video URL</label>
            <input type="text" class="form-control" name="mobile_full_video"
                value="{{ old('mobile_full_video', $home->mobile_full_video ?? '') }}">
            @error('mobile_full_video')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            @if (!empty($home->mobile_full_video))
                <video src="{{ $home->mobile_full_video }}" controls width="300" class="mt-2"></video>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">
            {{ $home && ($home->desktop_full_video || $home->mobile_full_video) ? 'Update Videos' : 'Save Videos' }}
        </button>
    </form>
@endsection