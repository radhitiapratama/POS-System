@extends('layouts.main', [
    'page_title' => 'Produk',
    'breadcumbs' => [
        [
            'title' => 'Master Produk',
            'link' => '#',
        ],
        [
            'title' => 'Produk',
            'link' => '#',
        ],
    ],
    'last_breadcumb' => 'Produk',
    'title' => 'Edit Kategori',
])
@section('content')
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title w-100 d-flex justify-content-between align-items-center">
                <div class="card-title">Edit Kategori Produk</div>
                <a href="{{ route('product-category.index') }}" class="btn btn-primary label-btn">
                    <i class="ri-arrow-left-line label-btn-icon"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <div class="card custom-card">
        <div class="card-body">
            <form action="{{ route('product-category.update', $category->id) }}" method="post">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="name" class="form-label fs-14 text-dark">Nama Kategori</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder=""
                        value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <div class="text-danger mt-2" id="name">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Sweetalerts CSS -->
    <link rel="stylesheet" href="noa-assets/assets/libs/sweetalert2/sweetalert2.min.css">
    {{-- Sweetalert --}}
    <script src="noa-assets/assets/libs/sweetalert2/sweetalert2.min.js"></script>
@endpush
@push('scripts')
    <script>
        @if (session()->has('error'))
            Swal.fire({
                icon: 'error',
                title: '{{ session('error') }}',
                text: '{{ session('error_message') }}',
            });
        @endif
    </script>
@endpush
