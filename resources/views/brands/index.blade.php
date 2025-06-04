@extends('adminlte::page')

@section('title', 'Brands')

@section('content_header')
    <h1>Brands List</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="brandsTable" class="table table-bordered table-striped align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>Brand Order</th>
                <th>Brand Name</th>
                <th>Brand Logo</th>
                <th>Banner Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brands as $brand)
                <tr>
                    <td>{{ $brand->id }}</td>
                    <td class="fw-bold">{{ $brand->brand_name }}</td>
                    <td>
                        @if($brand->brand_logo)
                            <img src="{{ asset('storage/' . $brand->brand_logo) }}" alt="Logo" width="60" class="img-thumbnail">
                        @endif
                    </td>
                    <td>
                       @if($brand->banner_images && is_array(json_decode($brand->banner_images)))
                            @foreach(json_decode($brand->banner_images) as $image)
                                <img src="{{ asset('storage/' . $image) }}" alt="Banner" width="100" class="img-fluid rounded shadow-sm me-1 mb-1">
                            @endforeach
                        @endif

                    </td>
                    <td>
                        <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        table img {
            object-fit: cover;
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#brandsTable').DataTable();
        });
    </script>
@endsection
