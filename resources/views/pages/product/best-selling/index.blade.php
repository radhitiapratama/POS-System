@extends('layouts.main', [
    'page_title' => 'Produk akan habis',
    'breadcumbs' => [
        [
            'title' => 'Produk',
            'link' => '#',
        ],
        [
            'title' => 'Produk akan habis',
            'link' => '#',
        ],
    ],
    'last_breadcumb' => 'Produk akan habis',
])
@section('content')
    <div class="row">
        <div class="col-12 card custom-card">
            <div class="card-header">
                <div class="card-title">Produk terlaris</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="filter_month" class="form-label">Filter Bulan</label>
                            <div class="input-group">
                                <div class="input-group-text border-0"> <i class="ri-calendar-line"></i> </div>
                                <input type="text" class="form-control flatpickr-input" id="filter_month"
                                    placeholder="Pilih..." readonly="readonly">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button class="btn btn-primary" id="clear-filter">Bersihkan Filter</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="datatable-basic_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="table-product-category"
                                        class="table table-bordered text-nowrap w-100 dataTable no-footer"
                                        aria-describedby="datatable-basic_info">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Produk</th>
                                                <th>Qty terjual</th>
                                                <th>Total</th>
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
    {{-- Select 2 --}}
    <link href="/noa-assets/assets/libs/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <!-- Sweetalerts CSS -->
    <link rel="stylesheet" href="/noa-assets/assets/libs/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="/noa-assets/assets/libs/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('noa-assets/assets/libs/flatpickr/dist/plugins/monthSelect/style.css') }}">

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
    {{-- Select2 --}}
    <script src="/noa-assets/assets/libs/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- Sweetalert --}}
    <script src="/noa-assets/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ asset('noa-assets/assets/libs/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script src="{{ asset('noa-assets/assets/libs/flatpickr/dist/plugins/monthSelect/index.js') }}"></script>

    <script>
        function reloadDatatable() {
            $("#table-product-category").DataTable().ajax.reload()
        }

        function createDatatable() {
            $("#table-product-category").DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 1200,
                ordering: false,
                searching: false,
                ajax: {
                    url: '{{ url()->current() }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d.filter_month = $("#filter_month").val()
                    }
                },
                drawCallback: function(data) {
                    let json = data.json
                    console.log(json);
                },
                order: [2, "DESC"],
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
                        name: 'product.name'
                    },
                    {
                        data: 'qty_sold',
                        name: 'qty_sold',
                        render: function(data, type, row, meta) {
                            return ` <div class="text-wrap" style="max-width: 100%;">${formatRupiah(data).replaceAll("Rp","")}</div>`
                        }
                    },
                    {
                        data: 'total_sold',
                        name: 'total_sold',
                        render: function(data, type, row, meta) {
                            return ` <div class="text-wrap" style="max-width: 100%;">${formatRupiah(data)}</div>`
                        }
                    },
                ]
            });
        }

        function formatRupiah(num) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0
            }).format(num);
        }

        $(document).ready(function() {
            createDatatable()
        });

        let fp = flatpickr("#filter_month", {
            altInput: true,
            altFormat: "F Y",
            dateFormat: "Y-m",
            defaultDate: "{{ date('Y-m') }}",
            plugins: [
                new monthSelectPlugin({
                    altFormat: true,
                    dateFormat: "Y-m-01",
                    altFormat: "F Y",
                })
            ],
            onChange: function(selectedDates, dateStr, instance) {
                reloadDatatable();
            },
        });

        $("#clear-filter").on("click", function() {
            fp.clear()
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
