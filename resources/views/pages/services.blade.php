@extends('adminlte::page')

@section('content')
<div class="container">
    <h2>Services Page</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('services.storeOrUpdate') }}" method="POST">
        @csrf

        @foreach([
            'brand_strategy' => 'Brand Strategy',
            'creative' => 'Creative',
            'packaging' => 'Packaging',
            'social_media' => 'Social Media',
            'digital_media' => 'Digital Media',
            'seo_website_ecommerce' => 'SEO / Website / E-Commerce'
        ] as $field => $label)
            <div class="mb-3">
                <label>{{ $label }}</label>
                <textarea name="{{ $field }}" class="form-control ckeditor">{{ old($field, $service->$field ?? '') }}</textarea>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection

@section('js')
<script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
<script>
    document.querySelectorAll('.ckeditor').forEach(el => {
        CKEDITOR.replace(el);
    });
</script>
@endsection
