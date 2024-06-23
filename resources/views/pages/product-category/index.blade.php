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
    <div class="row">
        <div class="col-12 card custom-card">
            <div class="card-header">
                <div class="card-title">Data Kategori Produk</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title flex ">
                        <div class="btn-list">
                            <a href="{{ route('product-category.create') }}" class="btn btn-primary label-btn">
                                <i class="ri-add-circle-line me-1 label-btn-icon"></i>
                                Tambah Produk Kategori
                            </a>
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
                                                <th>
                                                    Nama
                                                </th>
                                                <th>Action</th>
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
    <link rel="stylesheet" href="noa-assets/assets/libs/datatables/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="noa-assets/assets/libs/datatables/responsive/2.3.0/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="noa-assets/assets/libs/datatables/buttons/2.2.3/css/buttons.bootstrap5.min.css">

    <!-- Sweetalerts CSS -->
    <link rel="stylesheet" href="noa-assets/assets/libs/sweetalert2/sweetalert2.min.css">

    {{-- Datatable --}}
    <script src="noa-assets/assets/libs/datatables/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="noa-assets/assets/libs/datatables/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="noa-assets/assets/libs/datatables/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="noa-assets/assets/libs/datatables/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="noa-assets/assets/libs/datatables/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="noa-assets/assets/libs/ajax/libs/pdfmake/0.2.6/pdfmake.min.js"></script>
    <script src="noa-assets/assets/libs/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="noa-assets/assets/libs/datatables/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="noa-assets/assets/libs/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="noa-assets/assets/js/datatables.js"></script>


    {{-- Sweetalert --}}
    <script src="noa-assets/assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <script>
        // $("#table-product-category").DataTable();
        $("#table-product-category").DataTable({
            processing: true,
            serverSide: true,
            searchDelay: 1500,
            ajax: '{{ url()->current() }}',
            order: [3, "DESC"],
            columnDefs: [{
                width: '5px',
                targets: [0, 2]
            }],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
                {
                    data: "id",
                    name: "id",
                    visible: false
                }
            ]
        });

        $(document).on("click", ".btn-delete", function() {
            let id = $(this).data("id")
            let name = $(this).data("name")
            Swal.fire({
                title: "Peringatan",
                text: `Apakah anda yakin ingin menghapus kategori ${name}?`,
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
