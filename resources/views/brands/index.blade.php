@extends('adminlte::page')

@section('title', 'Brands')

@section('content_header')
    <h1>Brands List</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="brandsTable" class="table table-bordered table-striped">
<thead>
    <tr>
        <th>ID</th>
        <th>Banner Images</th>
        <th>YouTube Link</th>
        <th>Below Video Text</th>
        <th>Image Gallery</th>
        <th>Video Gallery</th>
        <th>Created At</th>
        <th>Actions</th> {{-- New --}}
    </tr>
</thead>
<tbody>
    @foreach ($brands as $brand)
        <tr>
            <td>{{ $brand->id }}</td>
            <td>
                @foreach ($brand->banner_images as $img)
                    <img src="{{ asset('storage/' . $img) }}" alt="Banner" width="60" class="me-1 mb-1">
                @endforeach
            </td>
            <td>
                @if($brand->youtube_link)
                    <a href="{{ $brand->youtube_link }}" target="_blank">YouTube</a>
                @endif
            </td>
            <td>{{ Str::limit(strip_tags($brand->below_video_text), 50) }}</td>
            <td>
                @foreach ($brand->image_gallery as $img)
                    <img src="{{ asset('storage/' . $img) }}" alt="Gallery" width="60" class="me-1 mb-1">
                @endforeach
            </td>
            <td>
                @foreach ($brand->video_gallery as $link)
                    <a href="{{ $link }}" target="_blank">Video</a><br>
                @endforeach
            </td>
            <td>{{ $brand->created_at->format('Y-m-d') }}</td>
            <td>
                <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-sm btn-primary">Edit</a>
            </td>
        </tr>
    @endforeach
</tbody>

    </table>
@endsection

@section('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection

@section('js')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#brandsTable').DataTable();
        });
    </script>
@endsection
