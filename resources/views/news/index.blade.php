@extends('adminlte::page')

@section('title', 'News CRUD')

{{-- DataTables CSS --}}
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container">
    <h2 class="mb-4">News Management</h2>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form (Create or Edit) --}}
    <div class="card mb-4">
        <div class="card-header">{{ isset($editNews) ? 'Edit News' : 'Add News' }}</div>
        <div class="card-body">
            <form action="{{ isset($editNews) ? route('news.update', $editNews->id) : route('news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($editNews)) @method('PUT') @endif

                <div class="mb-3">
                    <label for="news_title" class="form-label">News Title</label>
                    <input type="text" name="news_title" class="form-control" value="{{ old('news_title', $editNews->news_title ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="news_link" class="form-label">News Link</label>
                    <input type="url" name="news_link" class="form-control" value="{{ old('news_link', $editNews->news_link ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="thumbnail_picture" class="form-label">Thumbnail Picture</label>
                    <input type="file" name="thumbnail_picture" class="form-control">
                    @if(isset($editNews) && $editNews->thumbnail_picture)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $editNews->thumbnail_picture) }}" width="100">
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">{{ isset($editNews) ? 'Update' : 'Create' }}</button>
                @if(isset($editNews))
                    <a href="{{ route('news.index') }}" class="btn btn-secondary">Cancel</a>
                @endif
            </form>
        </div>
    </div>

    {{-- News List --}}
    <div class="card">
        <div class="card-header">All News</div>
        <div class="card-body table-responsive">
            <table id="newsTable" class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Link</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($item->thumbnail_picture)
                                    <img src="{{ asset('storage/' . $item->thumbnail_picture) }}" width="80">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $item->news_title }}</td>
                            <td><a href="{{ $item->news_link }}" target="_blank">{{ $item->news_link }}</a></td>
                            <td>
                                <a href="{{ route('news.index', ['edit' => $item->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('news.destroy', ['news' => $item->id]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this news?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No news added yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

{{-- DataTables JS --}}
@section('js')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#newsTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        });
    </script>
@endsection
