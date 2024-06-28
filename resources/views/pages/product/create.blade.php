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
])
@section('content')
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title w-100 d-flex justify-content-between align-items-center">
                <div class="card-title">Tambah Produk</div>
                <a href="{{ route('product.index') }}" class="btn btn-primary label-btn">
                    <i class="ri-arrow-left-line label-btn-icon"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <div class="card custom-card">
        <div class="card-body">
            <form action="{{ route('product.store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label fs-14 text-dark">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder=""
                        value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger mt-2" id="name">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label fs-14 text-dark">Kategori</label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">Pilih...</option required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="text-danger mt-2" id="category_id">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="unit_id" class="form-label fs-14 text-dark">Satuan</label>
                    <select name="unit_id" id="unit_id" class="form-control" required>
                        <option value="">Pilih...</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <div class="text-danger mt-2" id="unit_id">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="selling_price" class="form-label fs-14 text-dark">Harga Jual</label>
                    <div class="input-group">
                        <div class="input-group-text">RP</div>
                        <input type="text" class="form-control" id="selling_price" name="selling_price" placeholder=""
                            value="{{ old('selling_price') }}" required>
                    </div>
                    @error('selling_price')
                        <div class="text-danger mt-2" id="selling_price">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="stock_limit" class="form-label fs-14 text-dark">Stok Limit</label>
                    <input type="text" class="form-control" id="stock_limit" name="stock_limit" placeholder=""
                        value="{{ old('stock_limit') }}" required>
                    @error('stock_limit')
                        <div class="text-danger mt-2" id="stock_limit">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="barcode" class="form-label fs-14 text-dark">Barcode</label>
                    <input type="text" class="form-control" id="barcode" name="barcode" placeholder=""
                        value="{{ old('barcode') }}" maxlength="255" required>
                    @error('barcode')
                        <div class="text-danger mt-2" id="barcode">
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
    <link rel="stylesheet" href="/noa-assets/assets/libs/sweetalert2/sweetalert2.min.css">
    {{-- Select 2 --}}
    <link href="/noa-assets/assets/libs/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">


    {{-- Sweetalert --}}
    <script src="/noa-assets/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    {{-- Select2 --}}
    <script src="/noa-assets/assets/libs/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- Autonumeric JS --}}
    <script src="{{ asset('assets/autonumeric/autoNumeric.min.js') }}"></script>

    <script>
        $("#category_id").select2()
        $("#unit_id").select2()

        let autoNumericOptions = {
            minimumValue: 0,
            decimalPlaces: 0, // remove decimal
            digitGroupSeparator: '.', // Group separator
            decimalCharacter: ',', // Decimal character
            unformatOnSubmit: true // Ensure the unformatted value is submitted
        }

        new AutoNumeric("#selling_price", autoNumericOptions)
        new AutoNumeric("#stock_limit", autoNumericOptions)

        $(document).on('paste', '#barcode', function(e) {
            e.preventDefault();
            var withoutSpaces = e.originalEvent.clipboardData.getData('Text');
            withoutSpaces = withoutSpaces.replace(/\s+/g, '');
            $(this).val(withoutSpaces);
        });
    </script>
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
