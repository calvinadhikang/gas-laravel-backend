<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAll()
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Success',
            'data' => $products
        ], 200);
    }

    public function getDetail($id)
    {
        $product = Product::find($id);

        return response()->json([
            'message' => 'Success',
            'data' => $product
        ], 200);
    }

    public function create(Request $request)
    {
        $newName = $request->name;
        $existingProduct = Product::where('name', $newName)->first();

        if ($existingProduct) {
            return response()->json([
                'message' => 'Produk dengan nama yang sama sudah ada',
            ], 400);
        }

        $product = Product::create($request->all());

        return response()->json([
            'message' => 'Success',
            'data' => $product
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->update($request->all());

        return response()->json([
            'message' => 'Success',
            'data' => $product
        ], 201);
    }

    public function getStock($id)
    {
        $stocks = Inventory::where('product_id', $id)->get();

        return response()->json([
            'message' => 'Success',
            'data' => $stocks
        ], 200);
    }

    public function addStockManual(Request $request, $id)
    {
        $product = Product::find($id);
        $product->stock += $request->stock;
        $product->save();

        Inventory::create([
            'product_id' => $id,
            'stock' => $request->stock,
            'base_price' => $request->base_price,
            'type' => 'in',
            'description' => 'ADMIN Tambah stok manual',
        ]);

        return response()->json([
            'message' => 'Success',
            'data' => $request->stock,
        ], 201);
    }
}
