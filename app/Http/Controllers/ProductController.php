<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $product = Product::query()
            ->when($request->filled('name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            })->get();

        return response()->json([
            'message' => "Produtos disponíveis",
            'data' => $product
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'          => 'required|string|unique:products,code',
            'name'          => 'required|string|max:130',
            'description'   => 'nullable|string|max:450',
            'price'         => 'required|numeric|min:0',
            'stock_qtt'     => 'required|integer',
            'barcode'       => 'nullable|string|max:100|unique:products,barcode',
            'family_code'   => [
                'required',
                'string',
                Rule::exists('product_families', 'code')
            ],
        ]);
        $product = Product::create($validated);
        return response()->json([
            'message' => 'Produto criado com sucesso',
            'data' => $product
        ], 201);
    }

    public function destroy(string $identifier)
    {
        $product = Product::query()
            ->where('code', $identifier)
            ->orWhere('barcode', $identifier)
            ->firstOrFail();

        $product->delete();

        return response()->json([
            'message' => 'Produto removido com sucesso.'
        ], 200);
    }
}
