@extends('layouts.main', [
    'page_title' => 'Produk',
    'breadcumbs' => [
        [
            'title' => 'Master Produk',
            'link' => '#',
        ],
        [
            'title' => 'Kategori',
            'link' => '#',
        ],
    ],
    'last_breadcumb' => 'Kategori',
    'title' => 'Stok',
])
@section('content')
    <div class="row">
        <div class="col-12 card custom-card">
            <div class="card-header">
                <div class="card-title">Data Stock Produk</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title flex ">
                        <div class="btn-list">
                            <a href="{{ url('stock/create') }}" class="btn btn-primary label-btn">
                                <i class="ri-add-circle-line me-1 label-btn-icon"></i>
                                Tambah Stock
                            </a>
                            <a href="{{ url('stock/stock-adjustment') }}" class="btn btn-primary label-btn">
                                <i class="ri-add-circle-line me-1 label-btn-icon"></i>
                                Tambah Penyesuaian Stock
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <div class="form-group">
                        <label for="filter-month" class="form-label">Filter Bulan</label>
                        <div class="input-group">
                            <div class="input-group-text border-0"> <i class="ri-calendar-line"></i> </div>
                            <input type="text" class="form-control flatpickr-input" id="filter-month"
                                placeholder="Pilih..." readonly="readonly">
                        </div>
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
                                                <th>Kategori</th>
                                                <th>Stok awal</th>
                                                <th>Sisa stok</th>
                                                <th class="text-center">Action</th>
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
    <!-- Sweetalerts CSS -->
    <link rel="stylesheet" href="/noa-assets/assets/libs/sweetalert2/sweetalert2.min.css">
    {{-- Flatpicker --}}
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
    {{-- Flatipicker --}}
    {{-- <script src="/noa-assets/assets/libs/flatpickr/flatpickr.min.js"></script> --}}
    <script src="{{ asset('noa-assets/assets/libs/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script src="{{ asset('noa-assets/assets/libs/flatpickr/dist/plugins/monthSelect/index.js') }}"></script>



    {{-- Sweetalert --}}
    <script src="/noa-assets/assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <script>
        function reloadDatatable() {
            $("#table-product-category").DataTable().ajax.reload();
        }

        function createDatatable() {
            $("#table-product-category").DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 1200,
                ajax: {
                    url: '{{ url()->current() }}',
                    data: function(data) {
                        data.filter_month = $("#filter-month").val();
                    }
                },
                drawCallback: function(data) {
                    let json = data.json
                    console.log(json);
                },
                order: [1, "ASC"],
                columnDefs: [{
                    width: '5px',
                    targets: [0, 5]
                }],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                            return `<div class='text-wrap' style="max-width: 200px; !important">${data}</div>`
                        }
                    },
                    {
                        data: 'category.name',
                        name: 'category.name'
                    },

                    {
                        data: 'initial_stock',
                        name: 'initial_stock'
                    },

                    {
                        data: 'remaining_stock',
                        name: 'remaining_stock'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    },
                ]
            });
        }

        $(document).ready(function() {
            createDatatable();
        });

        $(document).on("click", ".btn-delete", function() {
            let id = $(this).data("id")
            let name = $(this).data("name")
            Swal.fire({
                title: "Peringatan",
                text: `Apakah anda yakin ingin menghapus produk ${name}?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Iya,hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`.form-delete-${id}`).submit();
                }
            });
        })

        flatpickr("#filter-month", {
            altInput: true,
            altFormat: "F Y",
            dateFormat: "Y-m",
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
