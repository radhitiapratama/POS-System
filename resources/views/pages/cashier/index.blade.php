@extends('layouts.main', [
    'page_title' => 'Kasir',
    'breadcumbs' => [
        [
            'title' => 'Kasir',
            'link' => '#',
        ],
    ],
    'last_breadcumb' => 'Kasir',
    'title' => 'Kasir',
])
@section('content')
    <input type="hidden" name="total_sales_hide">
    <div class="row">
        <div class="col-xl-8 col-md-12">
            <div class="card custom-card" id="cart-container-delete">
                <div class="card-header">
                    <label for="scan-barcode" class="form-label">Scan di sini</label>
                    <input type="text" class="form-control" id="scan-barcode" tabindex="1">
                    <div id="emailHelp" class="form-text">Cari berdasarkan Barcode/Nama Produk</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl-cart" class="table text-nowrap border table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-wrap">#</th>
                                    <th scope="col" class="text-wrap" style="max-width: 200px">Produk</th>
                                    <th scope="col" class="text-wrap" style="max-width: 200px">Harga</th>
                                    <th scope="col" class="text-wrap" style="max-width: 60px">Qty</th>
                                    <th scope="col" class="text-wrap" style="max-width: 200px">Harga total</th>
                                    <th scope="col" class="text-wrap text-center" style="max-width: 50px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12">
            <div class="card custom-card">
                <div class="card-header">
                    <h4 class="card-title">Price Details</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="#" class="form-label">Total belanja</label>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping">Rp</span>
                            <input type="text" class="form-control total-sales" aria-describedby="addon-wrapping"
                                readonly value="0" tabindex="-1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="#" class="form-label">Kembali</label>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping">Rp</span>
                            <input type="text" class="form-control return-price" aria-describedby="addon-wrapping"
                                readonly value="0" tabindex="-1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="#" class="form-label">Bayar</label>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping">Rp</span>
                            <input type="text" class="form-control" aria-describedby="addon-wrapping" name="pay"
                                value="0" tabindex="2">
                        </div>
                    </div>
                </div>
                {{-- <div class="card-footer">
                    <button class="btn btn-primary w-100">Selesaikan Transaksi</button>
                </div> --}}
            </div>
        </div>
    </div> <!--End::row-1 -->
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
    {{-- AutoNumeric JS --}}
    <script src="{{ asset('assets/autonumeric/autoNumeric.min.js') }}"></script>

    <script>
        let autoNumericOptions = {
            minimumValue: 0,
            decimalPlaces: 0, // remove decimal
            digitGroupSeparator: '.', // Group separator
            decimalCharacter: ',', // Decimal character
            unformatOnSubmit: true // Ensure the unformatted value is submitted
        }

        const base_url = "{{ url('') }}/"

        new AutoNumeric("input[name='pay']", autoNumericOptions)

        $(document).ready(function() {
            $("#scan-barcode").focus()
            loadCart()
            calcTotalSales()
        });

        $(document).on("keypress", "#scan-barcode", function(e) {
            if (e.which == 13) {
                let product = $(this).val()
                $("#scan-barcode").val("")
                setTimeout(() => {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('cashier/product') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            product,
                            cart: JSON.parse(localStorage.getItem("cart"))
                        },
                        dataType: "json",
                        success: function(response) {
                            $("#scan-barcode").val("")
                            console.log(response);
                            if (response.data == [] || response.data == null) {
                                Swal.fire({
                                    title: "Produk tidak ditemukan!",
                                    text: "Silahkan cek kembali Barcode/Nama Produk...",
                                    icon: "error",
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "Ok",
                                });

                                return;
                            }

                            if (response.data.stock == null) {
                                Swal.fire({
                                    title: "Stok produk kosong!",
                                    text: "Silahkan tambahkan stok produk terlebih dahulu",
                                    icon: "error",
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "Ok",
                                });

                                return
                            }

                            let data = response.data;
                            let storage_cart = [];

                            if (localStorage.getItem("cart") != null) {
                                storage_cart = JSON.parse(localStorage.getItem("cart"))
                            }

                            let cart
                            let product_index_in_storage = storage_cart.findIndex(e => e.id ==
                                data.id)
                            console.log(storage_cart);
                            console.log(product_index_in_storage);

                            if (product_index_in_storage <= -1) {
                                if (data.stock.stock <= 0) {
                                    Swal.fire({
                                        title: "Produk habis!",
                                        icon: "error",
                                        confirmButtonColor: "#3085d6",
                                        confirmButtonText: "Ok",
                                    });

                                    return;
                                }

                                cart = {
                                    id: data.id,
                                    name: data.name,
                                    qty: 1,
                                    selling_price: data.selling_price,
                                    remaining_stock: data.stock.stock,
                                };

                                localStorage.setItem("cart", JSON.stringify([...storage_cart,
                                    cart
                                ]))
                            } else {
                                let product_update = storage_cart.find(e => e.id == data.id)
                                let product_qty = product_update.qty
                                product_qty = product_qty + 1

                                // cek apakah qty product masih ada atau tidak
                                if (product_qty > data.stock.stock) {
                                    Swal.fire({
                                        title: "Produk habis!",
                                        icon: "error",
                                        confirmButtonColor: "#3085d6",
                                        confirmButtonText: "Ok",
                                    });

                                    storage_cart[product_index_in_storage].qty = data.stock
                                        .stock
                                    localStorage.setItem("cart", JSON.stringify(storage_cart))
                                    return;
                                }

                                storage_cart[product_index_in_storage].qty++
                                localStorage.setItem("cart", JSON.stringify(storage_cart))
                            }
                            loadCart()
                            calcTotalSales()
                            calcReturn()
                            console.log(JSON.parse(localStorage.getItem("cart")));
                        }
                    });
                }, 200);
            }
        })

        function loadCart() {
            let num = 1
            let row = ``
            let storage_cart = []
            $("#tbl-cart tbody").html()
            if (localStorage.getItem("cart") != null) {
                storage_cart = JSON.parse(localStorage.getItem("cart"))
            }

            for (let i = 0; i < storage_cart.length; i++) {
                row += `
                    <tr class="row-id-${storage_cart[i].id}">
                        <td class="text-center text-wrap">${num}</td>
                        <td class="text-wrap" style="max-width: 200px">${storage_cart[i].name}</td>
                        <td class="text-wrap" style="max-width: 200px">${formatRupiah(storage_cart[i].selling_price)}</td>
                        <td class="text-wrap"style="max-width: 60px">
                            <button class="btn btn-sm btn-primary btn-min-qty-cart" data-id="${storage_cart[i].id}">-</button>
                            <span class="qty">${storage_cart[i].qty}</span>
                            <button class="btn btn-sm btn-primary btn-add-qty-cart" data-id="${storage_cart[i].id}">+</button>
                        </td>
                        <td class="text-wrap total-price" style="max-width: 200px">
                            ${formatRupiah(storage_cart[i].qty * storage_cart[i].selling_price)}
                        </td>
                        <td class="text-center text-wrap" style="max-width: 50px">
                            <button class="btn btn-icon btn-danger btn-wave btn-delete-product-cart" data-id="${storage_cart[i].id}">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                `
                num++
            }

            $("#tbl-cart tbody").html(row)
        }

        function formatRupiah(num) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0
            }).format(num);
        }

        function calcTotalPriceProduct(id, event = null) {
            let storage_cart = []
            if (localStorage.getItem("cart") != null) {
                storage_cart = JSON.parse(localStorage.getItem("cart"))
            }

            let product = storage_cart.find(e => e.id == id)
            if (product == null || product == undefined) {
                let el = event.target.parentElement.parentElement.remove()
                return;
            }

            let total_price = product.qty * product.selling_price
            console.log(storage_cart);

            $(`.row-id-${id} .total-price`).html(formatRupiah(total_price))
            $(`.row-id-${id} .qty`).html(product.qty)
        }

        function addQtyProduct(id) {
            let storage_cart = []
            if (localStorage.getItem("cart") != null) {
                storage_cart = JSON.parse(localStorage.getItem("cart"))
            }

            let product_index = storage_cart.findIndex(e => e.id == id)
            let product = storage_cart[product_index]
            let qty = product.qty + 1
            if (qty > product.remaining_stock) {
                Swal.fire({
                    title: "Produk habis!",
                    icon: "error",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok",
                });

                storage_cart[product_index].qty = storage_cart[product_index].remaining_stock
                localStorage.setItem("cart", JSON.stringify(storage_cart))
                return
            } else {
                product.qty++
                localStorage.setItem("cart", JSON.stringify(storage_cart))
            }
        }

        function minQtyProduct(id) {
            let storage_cart = []
            if (localStorage.getItem("cart") != null) {
                storage_cart = JSON.parse(localStorage.getItem("cart"))
            }

            let product_index = storage_cart.findIndex(e => e.id == id)
            let product = storage_cart[product_index]
            let qty = product.qty - 1
            if (qty <= 0) {
                storage_cart.splice(product_index, 1)
                localStorage.setItem("cart", JSON.stringify(storage_cart))
            } else {
                product.qty--
                localStorage.setItem("cart", JSON.stringify(storage_cart))
            }
        }

        function calcTotalSales() {
            let storage_cart = []
            if (localStorage.getItem("cart") != null) {
                storage_cart = JSON.parse(localStorage.getItem("cart"))
            }

            if (storage_cart != [] || storage_cart != null) {
                let total_price = 0
                for (let i = 0; i < storage_cart.length; i++) {
                    total_price += storage_cart[i].qty * storage_cart[i].selling_price
                }

                $(".total-sales").val(formatRupiah(total_price).replaceAll("Rp", ""))
                $("input[name='total_sales_hide']").val(total_price)
                return
            }

            $(".total-sales").val("0")
            $("input[name='total_sales_hide']").val(0)
        }

        function calcReturn(pay = $("input[name='pay']").val().replaceAll(".", "")) {
            let total_sales = $("input[name='total_sales_hide']").val()
            let return_price = pay - total_sales
            console.log(pay);
            console.log("Return : ", return_price);
            if (return_price <= 0) {
                $(".return-price").val("0")
                return
            }
            $(".return-price").val(formatRupiah(pay - total_sales))
        }

        function deleteProductFromCart(event = null, id) {
            let storage_cart = []
            if (localStorage.getItem("cart") != null) {
                storage_cart = JSON.parse(localStorage.getItem("cart"))
            }

            let product_index = storage_cart.findIndex(e => e.id == id)
            storage_cart.splice(product_index, 1)
            localStorage.setItem("cart", JSON.stringify(storage_cart))
            calcTotalSales()
            calcReturn()
            $(`.row-id-${id}`).remove()
        }

        $(document).on("click", ".btn-add-qty-cart", function(e) {
            let id = $(this).data("id")
            addQtyProduct(id)
            calcTotalPriceProduct(id, e)
            calcTotalSales()
            calcReturn()
        })

        $(document).on("click", ".btn-min-qty-cart", function(e) {
            let id = $(this).data("id")
            minQtyProduct(id)
            calcTotalPriceProduct(id, e)
            calcTotalSales()
            calcReturn()
        })

        $(document).on("click", ".btn-delete-product-cart", function(e) {
            Swal.fire({
                title: "Peringatan",
                text: "Apakah anda yakin ingin menghapus produk dari keranjang?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Iya,Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.close()
                    let id = $(this).data("id")
                    console.log(id);
                    deleteProductFromCart(event, id)
                }
            });
        })

        $(document).on("keyup", "input[name='pay']", function(e) {
            // let value = e.target.value
            calcReturn(e.target.value.replaceAll(".", ""))
        })

        $("input[name='pay']").on("keypress", function(e) {
            if (e.which == 13) {
                let pay = $("input[name='pay']").val()
                Swal.fire({
                    title: "Peringatan!",
                    text: "Apakah anda yakin ingin menyelesaikan transaksi ini?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Iya,Selesaikan",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{{ url('cashier/pay') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                cart: JSON.parse(localStorage.getItem("cart")),
                                pay: pay.replaceAll(".", "")
                            },
                            dataType: "json",
                            success: function(response) {
                                console.log(response);
                                if (response.status == "error") {
                                    Swal.fire({
                                        title: response.title,
                                        text: response.message,
                                        icon: "error",
                                        confirmButtonColor: "#3085d6",
                                        confirmButtonText: "Ok",
                                    });

                                    return
                                }

                                localStorage.setItem("cart", JSON.stringify(response.cart))
                                $("input[name='pay']").val("0")
                                loadCart()
                                calcTotalSales()
                                calcReturn()

                                Swal.fire({
                                    title: response.title,
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "Ok",
                                }).then((response) => {
                                    if (response.isConfirmed) {
                                        window.location.reload()
                                    }
                                });

                                let sales_id = response.sales_id
                                window.open(base_url + `sales/${sales_id}/print`, "_blank")
                            }
                        });
                    }
                });
            }
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
