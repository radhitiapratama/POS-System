@extends('layouts.main', [
    'page_title' => 'Laporan Penjualan',
    'breadcumbs' => [
        [
            'title' => 'Laporan',
            'link' => '#',
        ],
        [
            'title' => 'Laporan penjualan',
            'link' => '#',
        ],
        [
            'title' => 'Detail penjualan',
            'link' => '#',
        ],
    ],
    'last_breadcumb' => 'Detail penjualan',
])
@section('content')
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title w-100 d-flex justify-content-end align-items-center">
                {{-- <div class="card-title">Tambah Produk</div> --}}
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
                    <div class="card-title">No Invoice : {{ $sales->number_invoice }}</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="datatable-basic_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="table-report-sales" style="width: 100% !important"
                                        class="table table-bordered text-nowrap w-100 dataTable no-footer"
                                        aria-describedby="datatable-basic_info">
                                        <thead>
                                            <tr>
                                                <th width="5">#</th>
                                                <th style="max-width: 30%">Produk</th>
                                                <th style="max-width: 20%">Qty</th>
                                                <th style="max-width: 20%">Harga</th>
                                                <th style="max-width: 20%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4"></th>
                                                <th colspan="1" class="text-end">Total : </th>
                                            </tr>
                                        </tfoot>
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

    <script>
        function reloadDatatable() {
            $("#table-report-sales").DataTable().ajax.reload();
        }

        function createDatatable() {
            $("#table-report-sales").DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 1500,
                searching: false,
                pagination: false,
                ordering: false,
                ajax: {
                    url: '{{ url()->current() }}',
                    datatype: "json",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                },
                drawCallback: function(data) {
                    let json = data.json
                    console.log(json);
                },
                order: [1, "DESC"],
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
                        render: function(data, type, row, meta) {
                            return ` <div class="text-wrap" style="max-width: 100%;">${data}</div>`
                        }
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                        render: function(data, type, row, meta) {
                            return ` <div class="text-wrap" style="max-width: 100%;">${data}</div>`
                        }
                    },
                    {
                        data: 'price',
                        name: 'price',
                        render: function(data, type, row, meta) {
                            return ` <div class="text-wrap" style="max-width: 100%;">${data}</div>`
                        }
                    },
                    {
                        data: 'total_price',
                        name: 'total_price',
                        render: function(data, type, row, meta) {
                            return ` <div class="text-wrap" style="max-width: 100%;">${formatRupiah(data)}</div>`
                        },
                    },
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Remove the formatting to get integer data for summation
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i :
                            0;
                    };

                    // Total over all pages
                    total = api
                        .column(4)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer
                    $(api.column(4).footer()).html(`Total ${formatRupiah(total)}`);
                }
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
            createDatatable();
        });
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
