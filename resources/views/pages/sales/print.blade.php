<html>

<head>
    <title>Struk Pembayaran</title>
    <style>
        #tabel {
            font-size: 12px;
            border-collapse: collapse;
        }

        #tabel td {
            padding-left: 2px;
            border: 1px solid black;
        }

        hr {
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 1px;
        }
    </style>
</head>

<body style='font-family:tahoma; font-size:8pt;'>
    <center>
        <table style='width:200px; font-size:10pt; font-family:calibri; border-collapse: collapse;' border='0'>
            <tr>
                <td width='100%' align='CENTER' style="vertical-align: top">
                    <span style='color:black;'>
                        <b>POS SYSTEM</b>
                        {{-- JL XXXXXXXXXXX XXXXXXX --}}
                    </span>
                    </br>
                    <span style='font-size:8pt'>No Faktur : {{ $sales->number_invoice }}</span></br>
                    <span style="font-size: 8pt">{{ date('d-m-Y H:i:s', strtotime($sales->created_at)) }}</span>
                </td>
            </tr>
        </table>

        <table cellspacing='0' cellpadding='0'
            style='width:200px; font-size:8pt; font-family:calibri; border-collapse: collapse;' border='0'>
            <tr align='center'>
                <td width='30%'>Produk</td>
                <td width='30%'>Harga</td>
                <td width='10%'>Qty</td>
                <td width='30%'>Total</td>
            </tr>
            <tr>
                <td colspan='4'>
                    <hr>
                </td>
            </tr>
            @php
                $total_sales = 0;
            @endphp
            @foreach ($sale_details as $data)
                <tr>
                    <td style='vertical-align:top'>{{ $data->product->name }}</td>
                    <td style='vertical-align:top; text-align:right;'>{{ number_format($data->price, 0, ',', '.') }}
                    </td>
                    <td style='vertical-align:top; text-align:right;'>
                        {{ number_format($data->qty, 0, ',', '.') }}
                    </td>
                    <td style='text-align:right; vertical-align:top'>
                        @php
                            $total = $data->qty * $data->price;
                            $total_sales += $total;
                        @endphp
                        {{ number_format($total, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td colspan='4'>
                        <hr>
                    </td>
                </tr>
            @endforeach
            @php
                $return_price = $sales->pay - $total_sales;
            @endphp
            <tr>
                <td colspan='3' style='text-align:left;'>Total Belanja : Rp
                    {{ number_format($total_sales, 0, ',', '.') }} </td>
            </tr>
            <tr>
                <td colspan='3' style='text-align:left;'>Bayar : Rp
                    {{ number_format($sales->pay, 0, ',', '.') }} </td>
            </tr>
            <tr>
                <td colspan='3' style='text-align:left;'>Kembali : Rp {{ number_format($return_price, 0, ',', '.') }}
                </td>
            </tr>
        </table>
        <table style='width:200px; font-size:10pt;' cellspacing='2'>
            <tr></br>
                <td align='center'>****** TERIMAKASIH ******</br></td>
            </tr>
        </table>
    </center>

    <script>
        window.print()
    </script>
</body>

</html>
