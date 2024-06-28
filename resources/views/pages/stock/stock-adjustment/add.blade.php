@extends('layouts.main', [
    'page_title' => 'Stok Produk',
    'breadcumbs' => [
        [
            'title' => 'Stok Produk',
            'link' => '#',
        ],
        [
            'title' => 'Tambah Stok Penyesuaian',
            'link' => '#',
        ],
    ],
    'last_breadcumb' => 'Tambah Stok Penyesuaian',
    'title' => 'Penyesuaian stok',
])
@section('content')
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title w-100 d-flex justify-content-between align-items-center">
                <div class="card-title">Tambah penyesuaian stok</div>
                <a href="{{ url('stock') }}" class="btn btn-primary label-btn">
                    <i class="ri-arrow-left-line label-btn-icon"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <div class="card custom-card">
        <div class="card-body">
            <form action="{{ url('stock/stock-adjustment/store') }}" method="post">
                @csrf
                <input type="hidden" name="current_product_stock">
                <div class="mb-3">
                    <label for="product_id" class="form-label fs-14 text-dark">Produk</label>
                    <select name="product_id" id="product_id" class="form-control">
                    </select>
                    @error('product_id')
                        <div class="text-danger mt-2" id="product_id">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="current_product_stock_temp" class="form-label fs-14 text-dark">Stock produk saat ini</label>
                    <input type="text" class="form-control" id="current_product_stock_temp"
                        name="current_product_stock_temp" placeholder=""
                        value="{{ old('current_product_stock_temp', '-') }}" maxlength="255" disabled>
                    @error('current_product_stock_temp')
                        <div class="text-danger mt-2" id="current_product_stock_temp">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="type_adjustment" class="form-label fs-14 text-dark">Tipe penyesuaian</label>
                    <select name="type_adjustment" id="type_adjustment" class="form-control">
                        <option value="">Pilih...</option>
                        <option value="additional-adjustment">Penambahan</option>
                        <option value="reduction-adjustment">Pengurangan</option>
                    </select>
                    @error('type_adjustment')
                        <div class="text-danger mt-2" id="type_adjustment">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="qty" class="form-label fs-14 text-dark">Qty</label>
                    <input type="text" class="form-control" id="qty" name="qty" placeholder=""
                        value="{{ old('qty') }}" maxlength="255">
                    @error('qty')
                        <div class="text-danger mt-2" id="qty">
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
        $("#type_adjustment").select2()
        $("#product_id").select2({
            minimumInputLength: 2,
            placeholder: "Pilih...",
            ajax: {
                delay: 1000,
                url: "{{ url('products/search') }}",
                type: "POST",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processResults: function(data) {
                    console.log(data);
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: `${item.barcode} | ${item.name}`,
                                id: item.id
                            }
                        })
                    };
                },
            }
        })

        let autoNumericOptions = {
            decimalPlaces: 0, // remove decimal
            digitGroupSeparator: '.', // Group separator
            decimalCharacter: ',', // Decimal character
            unformatOnSubmit: true // Ensure the unformatted value is submitted
        }

        new AutoNumeric("#qty", autoNumericOptions)

        $("#product_id").on("select2:selecting", function(e) {
            let product_id = e.params.args.data.id
            $.ajax({
                type: "POST",
                url: "{{ url('/product/get-stock') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id
                },
                dataType: "json",
                success: function(response) {
                    $("input[name='current_product_stock_temp']").val(response.data.stock)
                    $("input[name='current_product_stock']").val(response.data.stock)
                }
            });
        })
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
