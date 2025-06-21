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
                <h1>CV.GALLERY AUTO SOLUTION</h1>
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
            <td>
                {{ $customer->name }}
            </td>
        </tr>
        <tr>
            <td>Tgl:</td>
            <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
            <td colspan="2">
                No PO:
            </td>
            <td>
                {{ $invoice->purchase_code }}
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="2">
                No Nota:
            </td>
            <td colspan="2">
                {{ $invoice->code }}
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
                Sub Total
            </td>
            <td colspan="2">
                Rp {{ number_format($invoice->total) }}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                DPP Nilai Lain Lain
            </td>
            <td colspan="2">
                Rp 2.000
            </td>
        </tr>
        <tr>
            <td colspan="4">
                PPN
            </td>
            <td colspan="2">
                Rp{{ number_format($invoice->ppn_value) }}
            </td>
        </tr>
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
        </tr>
        <tr>
            <td colspan="6">
        </tr>
        <tr>
            <td></td>
            <td>Mohon di transfer ke rekening :</td>
            <td></td>
            <td colspan="2">Penerima,</td>
            <td>Hormat Kami,</td>
        </tr>
        <tr>
            <td></td>
            <td>REKENING BCA :</td>
        </tr>
        <tr>
            <td></td>
            <td>A/N GALLERY AUTO SOLUTON CV</td>
        </tr>
        <tr>
            <td></td>
            <td>A/C 782-596-8989</td>
        </tr>
    </table>
</body>

</html>
