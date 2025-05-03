<?php

namespace App\Http\Controllers;

use App\Models\HPurchase;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function getAll()
    {
        $vendors = Vendor::all();

        return response()->json([
            'message' => 'Success',
            'data' => $vendors
        ], 200);
    }

    public function getDetail($id)
    {
        $vendor = Vendor::find($id);
        $vendorPurchase = HPurchase::where('vendor_id', $id)->get();

        return response()->json([
            'message' => 'Success',
            'data' => $vendor,
            'purchase' => $vendorPurchase
        ], 200);
    }

    public function create(Request $request)
    {
        $vendor = Vendor::create($request->all());

        return response()->json([
            'message' => 'Success',
            'data' => $vendor
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::find($id);
        $vendor->update($request->all());

        return response()->json([
            'message' => 'Success',
            'data' => $vendor
        ], 201);
    }
}
