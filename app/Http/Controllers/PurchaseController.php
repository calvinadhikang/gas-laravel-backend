<?php

namespace App\Http\Controllers;

use App\Models\DPurchase;
use App\Models\HPurchase;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\PurchasePayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function getAll()
    {
        $purchase = HPurchase::with('vendor')->orderBy('code', 'desc')->get();

        return response()->json([
            'message' => 'success',
            'data' => $purchase
        ]);
    }

    public function getDetail($id)
    {
        $purchase = HPurchase::with('vendor')->find($id);

        $products = DPurchase::where('purchase_id', $id)->get();
        $products = $products->map(function ($product) {
            $product->product = Product::find($product->product_id);
            $product->name = $product->product->name;
            return $product;
        });

        $purchase->products = $products;
        return response()->json([
            'message' => 'Success',
            'data' => $purchase
        ]);
    }

    public function create(Request $request)
    {
        $products = $request->products;
        $vendor_id = $request->vendor_id;

        $totalProductPrice = 0;

        $hpurchase = new HPurchase();
        $hpurchase->vendor_id = $vendor_id;
        $hpurchase->code = $this->createPurchaseCode($request->created_at);
        $hpurchase->status = 'created';

        foreach ($products as $product) {
            $totalProductPrice += $product['price'] * $product['quantity'];
        }

        $ppnValue = $totalProductPrice * 0.11;
        $grandTotal = $totalProductPrice + $ppnValue;

        $hpurchase->total = $totalProductPrice;
        $hpurchase->paid = 0;
        $hpurchase->ppn = 11;
        $hpurchase->ppn_value = $ppnValue;
        $hpurchase->grand_total = $grandTotal;
        $hpurchase->payment_due_date = $request->payment_due_date;

        // set created_at by user
        if ($request->created_at) {
            $hpurchase->created_at = $request->created_at;
        }

        $hpurchase->save();

        foreach ($products as $product) {
            $dpurchase = new DPurchase();
            $dpurchase->purchase_id = $hpurchase->id;
            $dpurchase->product_id = $product['id'];
            $dpurchase->price = $product['price'];
            $dpurchase->quantity = $product['quantity'];
            $dpurchase->total = $product['price'] * $product['quantity'];
            $dpurchase->ppn = 11;
            $dpurchase->ppn_value = $product['price'] * $product['quantity'] * 0.11;
            $dpurchase->grand_total = ($product['price'] * $product['quantity']) + ($product['price'] * $product['quantity'] * 0.11);
            $dpurchase->save();
        }

        return response()->json([
            'message' => 'success',
            'data' => $hpurchase
        ]);
    }

    public function update(Request $request, $id)
    {
        $purchase = HPurchase::find($id);
        $purchase->update($request->all());
        $purchase->save();

        return response()->json([
            'message' => 'Success',
            'data' => $purchase
        ], 201);
    }

    public function addPayment(Request $request, $id)
    {
        $purchase = HPurchase::find($id);
        $remaining = $purchase->grand_total - $purchase->paid;

        if ($request->amount > $remaining) {
            return response()->json([
                'message' => 'Amount is greater than remaining',
            ], 400);
        }

        // update purchase status
        $purchase->paid += $request->amount;
        if ($purchase->paid >= $purchase->grand_total) {
            $purchase->payment_status = 'paid';
        }
        $purchase->save();

        // new record
        $payment = new PurchasePayment();
        $payment->purchase_id = $purchase->id;
        $payment->amount = $request->amount;
        $payment->description = $request->description;
        $payment->save();

        return response()->json([
            'message' => 'Success',
            'data' => $purchase
        ], 201);
    }

    public function productArrived(Request $request, $id)
    {
        $purchase = HPurchase::find($id);
        $purchase->status = 'arrived';
        $purchase->save();

        $products = DPurchase::where('purchase_id', $id)->get();
        foreach ($products as $product) {
            // update product stock
            $productTarget = Product::find($product['product_id']);
            $productTarget->stock += $product['quantity'];
            $productTarget->save();

            // create inventory
            $inventory = new Inventory();
            $inventory->product_id = $product['product_id'];
            $inventory->stock = intval($product['quantity']);
            $inventory->base_price = $product['price'];
            $inventory->type = 'purchase';
            $inventory->reference_id = $purchase->id;
            $inventory->save();
        }

        return response()->json([
            'message' => 'Success',
            'data' => $purchase
        ], 201);
    }

    function createPurchaseCode($created_at)
    {
        $purchaseDate = Carbon::parse($created_at);

        $month = $purchaseDate->format('m');
        $year = $purchaseDate->format('Y');

        // get purchases created this month
        $purchase = HPurchase::withTrashed()->whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
        $purchase += 1;

        // use roman number for month
        $monthRoman = '';
        switch ($month) {
            case 1:
                $monthRoman = 'I';
                break;
            case 2:
                $monthRoman = 'II';
                break;
            case 3:
                $monthRoman = 'III';
                break;
            case 4:
                $monthRoman = 'IV';
                break;
            case 5:
                $monthRoman = 'V';
                break;
            case 6:
                $monthRoman = 'VI';
                break;
            case 7:
                $monthRoman = 'VII';
                break;
            case 8:
                $monthRoman = 'VIII';
                break;
            case 9:
                $monthRoman = 'IX';
                break;
            case 10:
                $monthRoman = 'X';
                break;
            case 11:
                $monthRoman = 'XI';
                break;
            case 12:
                $monthRoman = 'XII';
                break;
        }

        $numberCreatedThisYear = 1000 + $purchase;
        $numberCreatedThisYear = str_pad($numberCreatedThisYear, 4, '0', STR_PAD_LEFT);
        $lastThreeDigits = substr($numberCreatedThisYear, -3);

        $code = 'PO.GAS/' . $monthRoman . '/' . $lastThreeDigits;
        return $code;
    }

    public function delete($id)
    {
        $purchase = HPurchase::find($id);
        $purchase->delete();

        return response()->json([
            'message' => 'Success',
        ]);
    }
}
