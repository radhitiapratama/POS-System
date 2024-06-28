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
    'title' => 'Produk akan habis',
])
@section('content')
    <div class="row">
        <div class="col-12 card custom-card">
            <div class="card-header">
                <div class="card-title">Produk akan habis</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="filter_category" class="form-label">Filter Kategori</label>
                            <select name="filter_category" id="filter_category">
                                <option value="" selected>Pilih...</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button class="btn btn-primary" id="clear-filter">Bersihkan Filter</button>
                    </div>
                    {{-- <div class="card-title flex ">
                        <div class="btn-list">
                            <a href="{{ route('product.create') }}" class="btn btn-primary label-btn">
                                <i class="ri-add-circle-line me-1 label-btn-icon"></i>
                                Tambah Produk
                            </a>
                        </div>
                    </div> --}}
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
                                                <th style="max-width: 5px">#</th>
                                                <th style="max-width: 40%">Produk</th>
                                                <th style="max-width: 20%">Kategori</th>
                                                <th style="max-width: 20%">Stok</th>
                                                <th style="max-width: 20%">Stok limit</th>
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
                ajax: {
                    url: '{{ url()->current() }}',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d.filter_category = $("#filter_category").val()
                    }
                },
                drawCallback: function(data) {
                    let json = data.json
                    console.log(json);
                },
                order: [1, "ASC"],
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
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row, meta) {
                            return ` <div class="text-wrap" style="max-width: 100%;">${data}</div>`
                        }
                    },

                    {
                        data: 'category.name',
                        name: 'category.name',
                        render: function(data, type, row, meta) {
                            return ` <div class="text-wrap" style="max-width: 100%;">${data}</div>`
                        }
                    },
                    {
                        data: 'stock.stock',
                        name: 'stock.stock',
                        render: function(data, type, row, meta) {
                            return ` <div class="text-wrap" style="max-width: 100%;">${data}</div>`
                        }
                    },
                    {
                        data: 'stock_limit',
                        name: 'stock_limit',
                        render: function(data, type, row, meta) {
                            return ` <div class="text-wrap" style="max-width: 100%;">${data}</div>`
                        }
                    },
                ]
            });
        }

        $(document).ready(function() {
            createDatatable()
        });

        let category_select = $("#filter_category").select2({
            minimumInputLength: 2,
            placeholder: "Pilih...",
            ajax: {
                delay: 500,
                url: "{{ url('product-category/search') }}",
                type: "GET",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: `${item.name}`,
                                id: item.id
                            }
                        })
                    };
                },
            }
        })

        $("#filter_category").on("change.select2", function() {
            reloadDatatable()
        })

        $("#clear-filter").on("click", function() {
            $('#filter_category').val(null).trigger('change');
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
