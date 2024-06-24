@extends('layouts.main', [
    'page_title' => 'Riwayat Stok Produk',
    'breadcumbs' => [
        [
            'title' => 'Stok Barang',
            'link' => '#',
        ],
        [
            'title' => 'Riwayat Stok Produk',
            'link' => '#',
        ],
    ],
    'last_breadcumb' => 'Riwayat Stok Produk',
])
@section('content')
    <input type="hidden" name="filter_start_date">
    <input type="hidden" name="filter_end_date">
    <input type="hidden" name="filter_month" value="{{ request()->get('filter_month') }}">

    <div class="row">
        <div class="col-12 card custom-card">
            <div class="card-header  w-100 d-flex justify-content-between align-items-center">
                <div class="card-title">Riwayat Stok Produk</div>
                <a href="{{ url('stock') }}" class="btn btn-primary label-btn">
                    <i class="ri-arrow-left-line label-btn-icon"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header row">
                    <div class="col-12">
                        <div class="row align-items-end">
                            <div class="col-12 col-md-5">
                                <label for="filter_date_range" class="form-label">Filter Tanggal</label>
                                <div class="input-group w-100">
                                    <div class="input-group-text border-0"> <i class="ri-calendar-line"></i> </div>
                                    <input type="text" class="form-control flatpickr-input" id="filter_date_range"
                                        placeholder="Pilih..." readonly="readonly">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <button class="btn btn-primary d-block mt-auto" id="btn-clear-filter">Bersihkan
                                    Filter</button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="datatable-basic_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="table-stock-history"
                                        class="table table-bordered text-nowrap w-100 dataTable no-footer"
                                        aria-describedby="datatable-basic_info">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Produk</th>
                                                <th>Tipe</th>
                                                <th>Qty</th>
                                                <th>Stok sebelumnya</th>
                                                <th>Sisa stok</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
    {{-- Flatpicker --}}
    <script src="{{ asset('noa-assets/assets/libs/flatpickr/dist/flatpickr.min.js') }}"></script>



    {{-- Sweetalert --}}
    <script src="/noa-assets/assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <script>
        function reloadDatatable() {
            $("#table-stock-history").DataTable().ajax.reload();
        }

        function createDatatable() {
            $("#table-stock-history").DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{{ url()->current() }}',
                    datatype: "json",
                    data: function(data) {
                        data.filter_month = $("input[name='filter_month']").val();
                        data.filter_start_date = $("input[name='filter_start_date']").val();
                        data.filter_end_date = $("input[name='filter_end_date']").val();
                    }
                },
                drawCallback: function(data) {
                    let json = data.json
                    console.log(json);
                },
                order: [7, "DESC"],
                columnDefs: [{
                    width: '5px',
                    targets: [0]
                }],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'product.name',
                        name: 'product.name',
                        render: function(data, type, row) {
                            return `<div class='text-wrap' style="max-width: 200px; !important">${data}</div>`
                        }
                    },
                    {
                        data: 'type',
                        name: 'type',
                        render: function(data, type, row) {
                            return `<div class='text-wrap' style="max-width: 200px; !important">${data}</div>`
                        }
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'stock_before',
                        name: 'stock_before'
                    },
                    {
                        data: 'remaining_stock',
                        name: 'remaining_stock'
                    },
                    {
                        data: 'created_at_formatted',
                        name: 'created_at_formatted'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false,
                    },
                ]
            });
        }

        $(document).ready(function() {
            createDatatable()
        });

        const fp_daterange = flatpickr("#filter_date_range", {
            mode: "range",
            altInput: true,
            dateFormat: "Y-m-d",
            altFormat: "d M Y",
            onChange: function(selectedDates, dateStr, instance) {
                // Ambil nilai dari altInput
                let altInputValue = instance.altInput.value;
                // Ganti " to " dengan " - "
                let formattedValue = altInputValue.replace(" to ", " - ");
                // Set nilai baru ke altInput
                instance.altInput.value = formattedValue;

                let startDate = new Date(selectedDates[0]);
                let endDate = new Date(selectedDates[1]);

                // Format tanggal untuk perbandingan
                let startDateFormat = startDate.getFullYear() + '-' + (startDate.getMonth() + 1) + '-' +
                    startDate.getDate();
                let endDateFormat = endDate.getFullYear() + '-' + (endDate.getMonth() + 1) + '-' + endDate
                    .getDate();

                $("input[name='filter_start_date']").val(startDateFormat)
                $("input[name='filter_end_date']").val(endDateFormat)

                if (selectedDates.length == 2 || startDateFormat == endDateFormat) {
                    reloadDatatable()
                }
            }
        })

        $("#btn-clear-filter").on("click", function() {
            fp_daterange.clear()
            $("input[name='filter_month']").val("")
            $("input[name='filter_start_date']").val("")
            $("input[name='filter_end_date']").val("")
            reloadDatatable()
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
