<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Style\Border;

class InvoicePPNExport implements FromView, WithStyles, WithColumnWidths
{
    protected $invoice;
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    public function view(): View
    {
        return view('documents.invoice-ppn', [
            'invoice' => $this->invoice,
            'customer' => $this->invoice->customer
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setSize(18);
        $sheet->getStyle('B5:B5')->getFont()->setBold(true)->setSize(12);

        $invoiceTableHeaderStart = 9;
        $sheet->getStyle('A' . $invoiceTableHeaderStart . ':F' . $invoiceTableHeaderStart)->getAlignment()->setHorizontal('center');

        $invoiceDetailsCount = count($this->invoice->details) - 1;
        $invoiceItemsTableStart = 10;
        $invoiceItemsTableEnd = $invoiceItemsTableStart + $invoiceDetailsCount;
        // table price alignment
        $sheet->getStyle('A' . $invoiceItemsTableStart . ':A' . $invoiceItemsTableEnd)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C' . $invoiceItemsTableStart . ':D' . $invoiceItemsTableEnd)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('E' . $invoiceItemsTableStart . ':F' . $invoiceItemsTableEnd)->getAlignment()->setHorizontal('right');

        // bagian subtotal, dpp, ppn, grand total
        $totalSectionStart = $invoiceItemsTableEnd + 1;
        $totalSectionEnd = $totalSectionStart + 3;
        $sheet->getStyle('A' . $totalSectionStart . ':D' . $totalSectionEnd)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('E' . $totalSectionStart . ':F' . $totalSectionEnd)->getAlignment()->setHorizontal('right');

        // table and total section border
        $sheet->getStyle('A' . $invoiceItemsTableStart - 1 . ':F' . $totalSectionEnd)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // bagian perhatian
        $perhatianSectionStart = $totalSectionEnd + 2;
        $perhatianSectionEnd = $perhatianSectionStart;
        $sheet->getStyle('A' . $perhatianSectionStart . ':B' . $perhatianSectionEnd)->getFont()->setBold(true);
        $sheet->getStyle('A' . $perhatianSectionStart . ':B' . $perhatianSectionEnd)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // bagian rekening
        $rekeningSectionStart = $totalSectionEnd + 4;
        $rekeningSectionEnd = $rekeningSectionStart + 3;
        $sheet->getStyle('B' . $rekeningSectionStart . ':B' . $rekeningSectionEnd)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B' . $rekeningSectionStart . ':B' . $rekeningSectionEnd)->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('B' . $rekeningSectionStart . ':B' . $rekeningSectionStart)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('B' . $rekeningSectionStart + 1 . ':B' . $rekeningSectionEnd)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 47.5,
            'C' => 6,
            'D' => 6,
            'E' => 18.6,
            'F' => 18.6,
        ];
    }
}
