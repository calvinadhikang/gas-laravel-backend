<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table>
        <tr>
            <td colspan="6">
                <h1>GAS BAN</h1>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <h2>Jl.Mayjend Sutoyo RT.44, Balikpapan HP.081347462030 / 08125309669</h2>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <h2>Email: galleryautosolution@gmail.com</h2>
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>INVOICE</td>
            <td colspan="2">
                Kepada Yth.
            </td>
        </tr>
        <tr>
            <td>Tgl:</td>
            <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
            <td colspan="2">
                Type Mobil
            </td>
            <td>
                {{ $invoice->car_type }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                No Nota: <b>{{ $invoice->code }}</b>
            </td>
            <td colspan="2">
                No. Polisi:
            </td>
            <td colspan="2">
                {{ $invoice->car_number }}
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        {{-- table --}}
        <tr>
            <td>No</td>
            <td>Keterangan</td>
            <td colspan="2">Qty</td>
            <td>Harga</td>
            <td>Jumlah</td>
        </tr>
        @foreach ($invoice->details as $detail)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ $detail->product->unit }}</td>
                <td>Rp {{ number_format($detail->price) }}</td>
                <td>Rp {{ number_format($detail->total) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4">
                Grand Total
            </td>
            <td colspan="2">
                Rp {{ number_format($invoice->grand_total) }}
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        {{-- Footer --}}
        <tr>
            <td colspan="2">Perhatian: Barang sudah dibeli tidak bisa ditukar/dikembalikan</td>
            <td colspan="2"></td>
            <td>GARANSI</td>
            <td>BULAN</td>
        </tr>
        <tr>
            <td colspan="6">
        </tr>
        <tr>
            <td></td>
            <td>REKENING BCA</td>
            <td></td>
            <td colspan="2">Penerima,</td>
            <td>Hormat Kami,</td>
        </tr>
        <tr>
            <td></td>
            <td>A/N Hendy Kuncoro A/C 191.2855.788</td>
        </tr>
        <tr>
            <td></td>
            <td>REKENING MANDIRI</td>
        </tr>
        <tr>
            <td></td>
            <td>A/N Hendy Kuncoro A/C 149-000-878887-1</td>
        </tr>
    </table>
</body>

</html>
