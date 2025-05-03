<?php

namespace App\Http\Controllers;

use App\Models\DInvoice;
use App\Models\HInvoice;
use App\Models\Inventory;
use App\Models\InvoicePayment;
use App\Models\Product;
use App\Models\ProductProfit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function getAll()
    {
        $invoice = HInvoice::with('customer')->orderBy('code', 'desc')->get();

        return response()->json([
            'message' => 'success',
            'data' => $invoice
        ]);
    }

    public function getDetail($id)
    {
        $invoice = HInvoice::with('customer')->find($id);

        $products = DInvoice::where('invoice_id', $id)->get();
        $products = $products->map(function ($product) {
            $product->product = Product::find($product->product_id);
            $product->name = $product->product->name;
            return $product;
        });

        $invoice->products = $products;
        $invoice->customer = $invoice->customer;

        return response()->json([
            'message' => 'Success',
            'data' => $invoice
        ]);
    }

    public function create(Request $request)
    {
        $type = $request->type;
        $purchase_code = $request->purchase_code;
        $car_type = $request->car_type;
        $car_number = $request->car_number;
        $customer_id = $request->customer_id;
        $payment_due_date = $request->payment_due_date;
        $products = $request->products;

        // stats
        $total = 0;
        $ppn = $type == 'ppn' ? 11 : 0;
        $ppn_multiplier = $type == 'ppn' ? 0.11 : 0;

        $ppn_value = 0;
        $grand_total = 0;

        foreach ($products as $product) {
            $total += $product['price'] * $product['quantity'];
            $ppn_value += $product['price'] * $product['quantity'] * $ppn_multiplier;
            $grand_total += $product['price'] * $product['quantity'] * (1 + $ppn_multiplier);
        }

        $invoice = new HInvoice();

        if ($type == 'ppn') {
            $invoice->code = $this->createPPNInvoiceCode($request->created_at);
            $invoice->purchase_code = $purchase_code;
        } else {
            $invoice->code = $this->createNonPPNInvoiceCode($request->created_at);
        }

        $invoice->type = $type;
        $invoice->car_type = $car_type;
        $invoice->car_number = $car_number;
        $invoice->customer_id = $customer_id;
        $invoice->payment_due_date = $payment_due_date;
        $invoice->total = $total;
        $invoice->ppn = $ppn;
        $invoice->ppn_value = $ppn_value;
        $invoice->grand_total = $grand_total;
        $invoice->paid = 0;

        // set created_at by user
        if ($request->created_at) {
            $invoice->created_at = $request->created_at;
        }

        $invoice->save();

        foreach ($products as $product) {
            $dInvoice = new DInvoice();
            $dInvoice->invoice_id = $invoice->id;
            $dInvoice->product_id = $product['id'];
            $dInvoice->quantity = $product['quantity'];
            $dInvoice->price = $product['price'];
            $dInvoice->total = $product['price'] * $product['quantity'];
            $dInvoice->ppn = $ppn;
            $dInvoice->ppn_value = $product['price'] * $product['quantity'] * $ppn_multiplier;
            $dInvoice->grand_total = $product['price'] * $product['quantity'] * (1 + $ppn_multiplier);
            $dInvoice->save();
        }

        return response()->json([
            'message' => 'Success',
            'data' => $invoice
        ]);
    }

    public function addPayment(Request $request, $id)
    {
        $invoice = HInvoice::find($id);
        $remaining = $invoice->grand_total - $invoice->paid;

        if ($request->amount > $remaining) {
            return response()->json([
                'message' => 'Amount is greater than remaining',
            ], 400);
        }

        $invoice->paid += $request->amount;
        if ($invoice->paid >= $invoice->grand_total) {
            $invoice->payment_status = 'paid';
        }
        $invoice->save();

        $payment = new InvoicePayment();
        $payment->invoice_id = $invoice->id;
        $payment->amount = $request->amount;
        $payment->description = $request->description;
        $payment->save();

        return response()->json([
            'message' => 'Success',
            'data' => $invoice
        ]);
    }

    public function transactionFinished(Request $request, $id)
    {
        $invoice = HInvoice::find($id);
        $invoice->status = 'finished';
        $invoice->save();

        $products = DInvoice::where('invoice_id', $id)->get();
        foreach ($products as $product) {
            $productTarget = Product::find($product->product_id);
            $productTarget->stock -= $product->quantity;
            $productTarget->save();

            // add inventory
            $inventory = new Inventory();
            $inventory->product_id = $product->product_id;
            $inventory->stock = $product->quantity;
            $inventory->base_price = 0;
            $inventory->type = 'invoice';
            $inventory->reference_id = $invoice->id;
            $inventory->save();


            // add product profit & get base price from inventory based on used stock using fifo
            $stockToBeUsed = $product->quantity;

            $inventories = Inventory::where('product_id', $product->product_id)
                ->where('type', '!=', 'invoice')
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($inventories as $inventoryItem) {
                if ($stockToBeUsed <= 0) break; // Stop when enough stock is used

                $availableStock = $inventoryItem->stock - $inventoryItem->stock_used; // Remaining stock
                $usedStock = min($stockToBeUsed, $availableStock); // Take only what's needed

                // Calculate profit
                $unitProfit = $product->price - $inventoryItem->base_price;
                $totalProfit = $unitProfit * $usedStock;

                // Record profit entry
                $productProfit = new ProductProfit();
                $productProfit->invoice_id = $invoice->id;
                $productProfit->product_id = $product->product_id;
                $productProfit->quantity = $usedStock;
                $productProfit->price = $product->price;
                $productProfit->base_price = $inventoryItem->base_price;
                $productProfit->profit = $unitProfit;
                $productProfit->total_profit = $totalProfit;
                $productProfit->save();

                // Update inventory usage
                $inventoryItem->stock_used += $usedStock;
                $inventoryItem->save();

                // Reduce the required stock
                $stockToBeUsed -= $usedStock;
            }
        }

        return response()->json([
            'message' => 'Success',
            'data' => $invoice
        ]);
    }

    function createPPNInvoiceCode($created_at)
    {
        $invoiceDate = Carbon::parse($created_at);

        $month = $invoiceDate->format('m');
        $year = $invoiceDate->format('Y');

        // get purchases created this month
        $purchase = HInvoice::withTrashed()->whereMonth('created_at', $month)->whereYear('created_at', $year)->where('type', '=', 'ppn')->count();
        $purchase += 1;

        $numberCreatedThisYear = 1000 + $purchase;
        $numberCreatedThisYear = str_pad($numberCreatedThisYear, 4, '0', STR_PAD_LEFT);
        $lastThreeDigits = substr($numberCreatedThisYear, -3);

        $yearLastTwoDigits = substr($year, -2);

        $code = 'GAS' . $yearLastTwoDigits . $month . $lastThreeDigits;
        return $code;
    }

    function createNonPPNInvoiceCode($created_at)
    {
        $invoiceDate = Carbon::parse($created_at);

        $month = $invoiceDate->format('m');
        $year = $invoiceDate->format('Y');

        // get purchases created this month
        $purchase = HInvoice::withTrashed()->whereMonth('created_at', $month)->whereYear('created_at', $year)->where('type', '!=', 'ppn')->count();
        $purchase += 1;

        $numberCreatedThisYear = 1000 + $purchase;
        $numberCreatedThisYear = str_pad($numberCreatedThisYear, 4, '0', STR_PAD_LEFT);
        $lastThreeDigits = substr($numberCreatedThisYear, -3);

        $yearLastTwoDigits = substr($year, -2);

        $code = 'SB' . $yearLastTwoDigits . $month . $lastThreeDigits;
        return $code;
    }

    public function delete($id)
    {
        $invoice = HInvoice::find($id);
        $invoice->delete();

        return response()->json([
            'message' => 'Success',
        ]);
    }

    public function update(Request $request, $id)
    {
        $invoice = HInvoice::find($id);
        $invoice->update($request->all());
        $invoice->save();

        return response()->json([
            'message' => 'Success',
            'data' => $invoice
        ]);
    }
}
