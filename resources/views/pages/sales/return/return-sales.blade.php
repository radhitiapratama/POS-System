@extends('layouts.main', [
    'page_title' => 'Retur Penjualan',
    'breadcumbs' => [
        [
            'title' => 'Laporan Penjualan',
            'link' => '#',
        ],
        [
            'title' => 'Retur penjualan',
            'link' => '#',
        ],
    ],
    'last_breadcumb' => 'Retur penjualan',
    'title' => 'Tambah retur penjualan',
])
@section('content')
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title w-100 d-flex justify-content-end align-items-center">
                <a href="{{ url('report/sales') }}" class="btn btn-primary label-btn">
                    <i class="ri-arrow-left-line label-btn-icon"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Retur Penjualan : {{ $sales->number_invoice }}</div>
                </div>
                <div class="card-body">
                    <form action="" method="post" class="form-retur">
                        @foreach ($sale_details as $index => $sale_detail)
                            <div class="form-group sale_detail_id_{{ $sale_detail->id }} mb-5">
                                <input type="hidden" name="id[]" value="{{ $sale_detail->id }}" disabled>
                                <input type="hidden" name="qty_beli[]" value="{{ $sale_detail->qty }}" disabled>
                                <div class="custom-toggle-switch d-flex align-items-center mb-4">
                                    <input id="toggleswitchPrimary-{{ $sale_detail->id }}"
                                        data-sale_detail_id="{{ $sale_detail->id }}" class="cb-isretur" name="cb[]"
                                        type="checkbox">
                                    <label for="toggleswitchPrimary-{{ $sale_detail->id }}" class="label-primary"></label>
                                    <span class="ms-3">Retur</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-12">
                                        <div class="mb-3">
                                            <label for="form-text" class="form-label fs-14 text-dark">Nama Produk</label>
                                            <input type="text" class="form-control" id="form-text" disabled
                                                value="{{ $sale_detail->product->name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="mb-3">
                                            <label for="form-text" class="form-label fs-14 text-dark">Harga</label>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text" id="addon-wrapping">Rp</span>
                                                <input type="text" class="form-control product-price"
                                                    value="{{ number_format($sale_detail->price, 0, ',', '.') }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="mb-3">
                                            <label for="form-text" class="form-label fs-14 text-dark">Qty di beli</label>
                                            <input type="text" class="form-control " id="form-text"
                                                value="{{ number_format($sale_detail->qty, 0, ',', '.') }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="mb-3">
                                            <label for="form-text" class="form-label fs-14 text-dark">Total</label>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text" id="addon-wrapping">Rp</span>
                                                <input type="text" class="form-control"
                                                    value="{{ number_format($sale_detail->qty * $sale_detail->price, 0, ',', '.') }}"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            <label for="form-text" class="form-label fs-14 text-dark">Qty retur <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control qty_return" name="qty_return[]"
                                                id="form-text" data-sale_detail_id="{{ $sale_detail->id }}"
                                                data-qty_return_max="{{ $sale_detail->qty }}" disabled>
                                            <small class="text-muted d-block mt-2">Maksimal qty return :
                                                {{ $sale_detail->qty }}</small>
                                            <small class="text-danger error_message_{{ $index }}"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            <label for="form-text" class="form-label fs-14 text-dark">Total
                                                Retur</label>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text" id="addon-wrapping">Rp</span>
                                                <input type="text" class="form-control total_return" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="form-text" class="form-label fs-14 text-dark">Alasan retur</label>
                                            <input type="text" class="form-control" name="return_message[]"
                                                id="form-text" value="" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <button class="btn btn-primary btn-submit" type="button">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    {{-- DataTable --}}
    <link rel="stylesheet" href="/noa-assets/assets/libs/datatables/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="/noa-assets/assets/libs/datatables/responsive/2.3.0/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="/noa-assets/assets/libs/datatables/buttons/2.2.3/css/buttons.bootstrap5.min.css">
    {{-- Flatpicker --}}
    <link rel="stylesheet" href="/noa-assets/assets/libs/flatpickr/flatpickr.min.css">
    <!-- Sweetalerts CSS -->
    <link rel="stylesheet" href="/noa-assets/assets/libs/sweetalert2/sweetalert2.min.css">

    {{-- Datatable --}}
    <script src="/noa-assets/assets/libs/datatables/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="/noa-assets/assets/libs/datatables/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="/noa-assets/assets/libs/datatables/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="/noa-assets/assets/libs/datatables/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="/noa-assets/assets/libs/datatables/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="/noa-assets/assets/libs/ajax/libs/pdfmake/0.2.6/pdfmake.min.js"></script>
    <script src="/noa-assets/assets/libs/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="/noa-assets/assets/libs/datatables/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="/noa-assets/assets/libs/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="/noa-assets/assets/js/datatables.js"></script>
    {{-- Sweetalert --}}
    <script src="/noa-assets/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    {{-- Flatpicker --}}
    <script src="{{ asset('noa-assets/assets/libs/flatpickr/dist/flatpickr.min.js') }}"></script>

    <script>
        function removeDisable(id) {
            $(`.sale_detail_id_${id} input[name='qty_return[]']`).prop("disabled", false)
            $(`.sale_detail_id_${id} input[name='return_message[]']`).prop("disabled", false)
            $(`.sale_detail_id_${id} input[name='id[]']`).prop("disabled", false)
            $(`.sale_detail_id_${id} input[name='qty_beli[]']`).prop("disabled", false)
            $(`.sale_detail_id_${id}`).addClass("retur-true")
        }

        function enableDisable(id) {
            $(`.sale_detail_id_${id} input[name='qty_return[]']`).prop("disabled", true)
            $(`.sale_detail_id_${id} input[name='qty_return[]']`).val("")
            $(`.sale_detail_id_${id} input[name='return_message[]']`).prop("disabled", true)
            $(`.sale_detail_id_${id} input[name='return_message[]']`).val("")
            $(`.sale_detail_id_${id} input[name='id[]']`).prop("disabled", true)
            $(`.sale_detail_id_${id} input[name='qty_beli[]']`).prop("disabled", true)
            $(`.sale_detail_id_${id}`).removeClass("retur-true")
        }


        function calcTotalReturn(qty, id) {
            let price = $(`.sale_detail_id_${id} .product-price`).val().replaceAll(".", "")

            let total_return = parseInt(price) * parseInt(qty)
            total_return = formatRupiah(total_return).replaceAll("Rp", "")

            $(`.sale_detail_id_${id} .total_return`).val(total_return)
        }

        function formatRupiah(num) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0
            }).format(num);
        }

        $(document).on("change", ".cb-isretur", function() {
            let sale_detail_id = $(this).data("sale_detail_id")
            if (this.checked) {
                removeDisable(sale_detail_id)
                calcTotalReturn(0, sale_detail_id)
            } else {
                enableDisable(sale_detail_id)
                calcTotalReturn(0, sale_detail_id)
            }
        })

        $('.qty_return').on('keydown', function(e) {
            if (isNaN(e.key) && e.key !== 'Backspace') {
                e.preventDefault();
                return
            }
        })

        $(".qty_return").on("keyup", function(e) {
            let id = $(this).data("sale_detail_id")
            let qty = $(this).val()
            if (qty == "" || qty == null || qty == undefined) {
                qty = 0
            }

            calcTotalReturn(qty, id)
        })

        $(".btn-submit").click(function(e) {
            let qty_return = $(".retur-true input[name='qty_return[]']")

            for (let i = 0; i < qty_return.length; i++) {
                if (qty_return[i].value == "") {
                    Swal.fire({
                        title: "Gagal!",
                        text: "Qty return wajib di isi!",
                        icon: "error"
                    });
                    return;
                }

                let qty_return_max = qty_return[i].dataset.qty_return_max;

                if (qty_return[i].value > qty_return_max) {
                    Swal.fire({
                        title: "Gagal!",
                        text: "Qty return tidak boleh lebih dari qty beli!",
                        icon: "error"
                    });
                    return;
                }
            }

            Swal.fire({
                title: "Peringatan",
                text: "Apakah anda yakin ingin retur produk ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Iya,retur",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('sales/return/confirm') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: $(".form-retur").serialize(),
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({
                                title: "Sukses",
                                text: `${response.message}`,
                                icon: "success",
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "Ok",
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = "{{ url('report/sales') }}";
                                }
                            });
                        },
                        error: function(res) {
                            let msg = res.dataJSON
                            console.log(msg);
                            Swal.fire({
                                title: "Gagal!",
                                text: "Terjadi kesalahan saat meretur produk,silahkan coba lagi",
                                icon: "error",
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "Iya,retur",
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        },
                    });
                }
            });

        })
    </script>
@endpush

@push('scripts')
    <script>
        @if (session()->has('success'))
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                text: '{{ session('success_message') }}',
            });
        @endif

        @if (session()->has('error'))
            Swal.fire({
                icon: 'error',
                title: '{{ session('error') }}',
                text: '{{ session('error_message') }}',
            });
        @endif
    </script>
@endpush
