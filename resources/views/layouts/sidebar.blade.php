<aside class="app-sidebar sticky" id="sidebar">
    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="{{ url('cashier') }}" class="header-logo">
            <img src="{{ asset('assets/img/logo-pos.png') }}" alt="logo"
                style="max-width: 100px; height: 100px; object-fit: cover" class="desktop-logo">
            <img src="{{ asset('assets/img/logo.png') }}" alt="logo"
                style="max-width: 80px; height: 80px; object-fit: cover" class="toggle-logo">
        </a>
    </div>
    <!-- End::main-sidebar-header -->
    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll" data-simplebar="init">
        <div class="simplebar-wrapper" style="margin: -11.2px 0px -80px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content"
                        style="height: 100%; overflow: hidden scroll;">
                        <div class="simplebar-content" style="padding: 11.2px 0px 80px;">
                            <!-- Start::nav -->
                            <nav class="main-menu-container nav nav-pills flex-column sub-open active">
                                <div class="slide-left active d-none" id="slide-left">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                                        viewBox="0 0 24 24">
                                        <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z">
                                        </path>
                                    </svg>
                                </div>
                                <ul class="main-menu active" style="margin-left: 0px; margin-right: 0px;">
                                    <li class="slide__category">
                                        <span class="category-name">Data Master</span>
                                    </li>
                                    <li
                                        class="slide has-sub {{ Request::is('product', 'product-category', 'unit', 'product/create', 'product/*/edit', 'product-category/*', 'unit/*') ? 'open active' : '' }} ">
                                        <a href="javascript:void(0);"
                                            class="side-menu__item {{ Request::is('product', 'product-category', 'unit', 'product/create', 'product/*/edit', 'product-category/*', 'unit/*') ? 'active' : '' }}">
                                            <i class="ri-box-3-line side-menu__icon custom-sidebar-icon"></i>
                                            <span class="side-menu__label">Master Produk</span>
                                            <i class="fe fe-chevron-right side-menu__angle"></i>
                                        </a>

                                        <ul class="slide-menu child1" data-popper-placement="bottom">
                                            <li class="slide side-menu__label1">
                                                <a href="javascript:void(0);">Master Produk</a>
                                            </li>
                                            <li
                                                class="slide {{ Request::is('product', 'product/create', 'product/*/edit') ? 'active' : '' }}">
                                                <a href="{{ route('product.index') }}"
                                                    class="side-menu__item {{ Request::is('product', 'product/create', 'product/*/edit') ? 'active' : '' }}">Produk</a>
                                            </li>
                                            <li
                                                class="slide {{ Request::is('product-category', 'product-category/*') ? 'active' : '' }}">
                                                <a href="{{ route('product-category.index') }}"
                                                    class="side-menu__item {{ Request::is('product-category', 'product-category/*') ? 'active' : '' }}">Kategori
                                                    Produk</a>
                                            </li>
                                            <li class="slide {{ Request::is('unit', 'unit/*') ? 'active' : '' }}">
                                                <a href="{{ url('unit') }}"
                                                    class="side-menu__item {{ Request::is('unit', 'unit/*') ? 'active' : '' }}">Satuan
                                                    Produk</a>
                                            </li>
                                        </ul>

                                    </li>
                                    </li>
                                    <li class="slide__category"><span class="category-name">Data Transaksi</span>
                                    </li>
                                    <li class="slide {{ Request::is('cashier', 'cashier/*') ? 'active' : '' }}">
                                        <a href="{{ url('cashier') }}"
                                            class="side-menu__item {{ Request::is('cashier', 'cashier/*') ? 'active' : '' }}">
                                            <i class="ri-macbook-line side-menu__icon custom-sidebar-icon"></i>
                                            <span class="side-menu__label">Kasir
                                            </span>
                                        </a>
                                    </li>
                                    <li class="slide {{ Request::is('stock', 'stock/*') ? 'active' : '' }}">
                                        <a href="{{ url('stock') }}"
                                            class="side-menu__item {{ Request::is('stock', 'stock/*') ? 'active' : '' }}">
                                            <i
                                                class="ri-checkbox-multiple-blank-line side-menu__icon custom-sidebar-icon"></i>
                                            <span class="side-menu__label">Stok Barang
                                            </span>
                                        </a>
                                    </li>
                                    <li
                                        class="slide has-sub {{ Request::is('report/sales', 'report/sales/*', 'sales/*/return') ? 'open active' : '' }}">
                                        <a href="javascript:void(0);"
                                            class="side-menu__item {{ Request::is('report/sales', 'report/sales/*', 'sales/*/return') ? 'active' : '' }}">
                                            <i class="ri-file-list-3-line side-menu__icon custom-sidebar-icon"></i>
                                            <span class="side-menu__label">Laporan</span>
                                            <i class="fe fe-chevron-right side-menu__angle"></i>
                                        </a>
                                        <ul class="slide-menu child1" data-popper-placement="bottom">
                                            <li class="slide side-menu__label1">
                                                <a href="javascript:void(0);">Laporan</a>
                                            </li>
                                            <li
                                                class="slide {{ Request::is('report/sales', 'report/sales/*', 'sales/*/return') ? 'active' : '' }}">
                                                <a href="{{ url('report/sales') }}"
                                                    class="side-menu__item {{ Request::is('report/sales', 'report/sales/*', 'sales/*/return') ? 'active' : '' }}">Laporan
                                                    Penjualan</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li
                                        class="slide {{ Request::is('sales/return', 'sales/return/*') ? 'active' : '' }}">
                                        <a href="{{ url('sales/return') }}"
                                            class="side-menu__item {{ Request::is('sales/return', 'sales/return/*') ? 'active' : '' }}">
                                            <i class="ri-text-wrap side-menu__icon custom-sidebar-icon"></i>
                                            <span class="side-menu__label">Retur Penjualan
                                            </span>
                                        </a>
                                    </li>
                                    <li
                                        class="slide {{ Request::is('product/best-selling', 'product/best-selling/*') ? 'active' : '' }}">
                                        <a href="{{ url('product/best-selling') }}"
                                            class="side-menu__item  {{ Request::is('product/best-selling', 'product/best-selling/*') ? 'active' : '' }}">
                                            <i class="ri-line-chart-line side-menu__icon custom-sidebar-icon"></i>
                                            <span class="side-menu__label">Produk Terlaris
                                            </span>
                                        </a>
                                    </li>
                                    <li
                                        class="slide {{ Request::is('product/litte-stock', 'product/litte-stock/*') ? 'active' : '' }}">
                                        <a href="{{ url('product/litte-stock') }}"
                                            class="side-menu__item {{ Request::is('product/litte-stock', 'product/litte-stock/*') ? 'active' : '' }}">
                                            <i class="ri-alarm-warning-line side-menu__icon custom-sidebar-icon"></i>
                                            <span class="side-menu__label">Produk Akan Habis
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="slide-right d-none" id="slide-right">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
                                        height="24" viewBox="0 0 24 24">
                                        <path
                                            d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z">
                                        </path>
                                    </svg>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="simplebar-placeholder" style="width: auto; height: 900px;"></div>
        </div>
        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
        </div>
        <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
            <div class="simplebar-scrollbar"
                style="height: 605px; display: block; transform: translate3d(0px, 0px, 0px);"></div>
        </div>
    </div>
    <!-- End::main-sidebar -->
</aside>
