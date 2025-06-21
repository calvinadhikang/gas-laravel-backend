<?php

namespace App\Http\Controllers;

use App\Exports\InvoicePPNExport;
use App\Exports\InvoiceNonPPNExport;
use App\Models\HInvoice;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function downloadInvoicePPN($id)
    {
        $invoice = HInvoice::find($id);
        return Excel::download(new InvoicePPNExport($invoice), 'invoice-ppn.xlsx');
    }

    public function downloadNonInvoicePPN($id)
    {
        $invoice = HInvoice::find($id);
        return Excel::download(new InvoiceNonPPNExport($invoice), 'non-invoice-ppn.xlsx');
    }
}
