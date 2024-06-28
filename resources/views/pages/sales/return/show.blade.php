@extends('layouts.main', [
    'page_title' => 'Retur Penjualan',
    'breadcumbs' => [
        [
            'title' => 'Retur Penjualan',
            'link' => '#',
        ],
        [
            'title' => 'Detail',
            'link' => '#',
        ],
    ],
    'last_breadcumb' => 'Detail',
    'title' => 'Detail retur penjualan',
])
@section('content')
    <div class="row">
        <div class="col-12 card custom-card">
            <div class="card-header">
                <div class="card-title w-100 d-flex justify-content-end align-items-center">
                    <a href="{{ url('/sales/return') }}" class="btn btn-primary label-btn">
                        <i class="ri-arrow-left-line label-btn-icon"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Detail retur produk</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-1 col-md-3 col-lg-2 form-label">No Invoice </div>
                        <div class="col-sm-3 col-md-3 col-lg-6">{{ $sale_return->sale_details->sales->number_invoice }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-1 col-md-3 col-lg-2 form-label">Tanggal retur </div>
                        <div class="col-sm-3 col-md-3 col-lg-6">
                            {{ date('d-m-Y H:i:s', strtotime($sale_return->created_at)) }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-1 col-md-3 col-lg-2 form-label">Produk </div>
                        <div class="col-sm-3 col-md-3 col-lg-6">{{ $sale_return->sale_details->product->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-1 col-md-3 col-lg-2 form-label">Qty retur </div>
                        <div class="col-sm-3 col-md-3 col-lg-6">{{ number_format($sale_return->qty, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-1 col-md-3 col-lg-2 form-label">Harga </div>
                        <div class="col-sm-3 col-md-3 col-lg-6">Rp {{ number_format($sale_return->price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-1 col-md-3 col-lg-2 form-label">Total </div>
                        <div class="col-sm-3 col-md-3 col-lg-6">Rp
                            {{ number_format($sale_return->qty * $sale_return->price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-label">Pesan retur</div>
                        <div class="col-12">
                            @if ($sale_return->return_message == null)
                                -
                            @else
                                {{ $sale_return->return_message }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
