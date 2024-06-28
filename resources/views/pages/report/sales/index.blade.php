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
    ],
    'last_breadcumb' => 'Laporan penjualan',
])
@section('content')
    {{-- <div class="row">
        <div class="col-12 card custom-card">
            <div class="card-header">
                <div class="card-title">Laporan penjualan</div>
            </div>
        </div>
    </div> --}}
    <input type="hidden" name="filter_start_date">
    <input type="hidden" name="filter_end_date">
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header row">
                    <div class="col-12 col-md-5 mb-3">
                        <label for="filter_date_range" class="form-label">Filter Tanggal</label>
                        <div class="input-group w-100">
                            <div class="input-group-text border-0"> <i class="ri-calendar-line"></i> </div>
                            <input type="text" class="form-control flatpickr-input" id="filter_date_range"
                                placeholder="Pilih..." readonly="readonly">
                        </div>
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <label for="" class="form-label">Nomor Invoice</label>
                        <input type="text" class="form-control" id="input" class="form-control"
                            placeholder="Cari berdasarkan No Invoice" name="filter_no_inv">
                    </div>
                    <div class="col-12 mb-3">
                        <button class="btn btn-primary" id="clear-filter">Bersihkan Filter</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="datatable-basic_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="table-report-sales"
                                        class="table table-bordered text-nowrap w-100 dataTable no-footer"
                                        aria-describedby="datatable-basic_info">
                                        <thead>
                                            <tr>
                                                <th width="5">#</th>
                                                <th>Tanggal</th>
                                                <th>No Inv</th>
                                                <th>Total</th>
                                                <th class="d-flex justify-content-center">Action
                                                </th>
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
    {{-- Sweetalert --}}
    <script src="/noa-assets/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    {{-- Flatpicker --}}
    <script src="{{ asset('noa-assets/assets/libs/flatpickr/dist/flatpickr.min.js') }}"></script>

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
                ajax: {
                    url: '{{ url()->current() }}',
                    datatype: "json",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d.filter_start_date = $("input[name='filter_start_date']").val();
                        d.filter_end_date = $("input[name='filter_end_date']").val();
                        d.filter_no_inv = $("input[name='filter_no_inv']").val();
                    }
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
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'number_invoice',
                        name: 'number_invoice'
                    },
                    {
                        data: 'total_sales',
                        name: 'total_sales'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
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

        $("input[name='filter_no_inv']").on("keypress", function(e) {
            if (e.which == 13) {
                reloadDatatable()
            }
        })

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

                console.log(dateStr);

                $("input[name='filter_start_date']").val("")
                $("input[name='filter_end_date']").val("")

                if (dateStr != "") {
                    $("input[name='filter_start_date']").val(startDateFormat)
                    $("input[name='filter_end_date']").val(endDateFormat)
                }

                if (selectedDates.length == 2 || startDateFormat == endDateFormat) {
                    reloadDatatable()
                }
            }
        })

        $("#clear-filter").click(function() {
            fp_daterange.clear()
            $("input[name='filter_start_date']").val("");
            $("input[name='filter_end_date']").val("");
            $("input[name='filter_no_inv']").val("");
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
