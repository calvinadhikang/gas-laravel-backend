<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\HInvoice;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function getAll()
    {
        $data = Customer::all();

        return response()->json([
            'message' => 'Get all customers',
            'data' => $data
        ]);
    }

    public function getDetail($id)
    {
        $data = Customer::find($id);
        $customerInvoice = HInvoice::where('customer_id', $id)->get();

        if ($data) {
            return response()->json([
                'message' => 'Get customer detail',
                'data' => $data,
                'invoice' => $customerInvoice
            ]);
        } else {
            return response()->json([
                'message' => 'Customer not found'
            ], 404);
        }
    }

    public function create(Request $request)
    {
        $customer = Customer::create($request->all());

        return response()->json([
            'message' => 'Success',
            'data' => $customer
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Customer::find($id);
        $product->update($request->all());

        return response()->json([
            'message' => 'Success',
            'data' => $product
        ], 201);
    }
}
