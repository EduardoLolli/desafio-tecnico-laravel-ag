<?php

namespace App\Http\Controllers;

use App\Models\ProductFamily;
use Illuminate\Http\Request;

class ProductFamilyController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => "Famílias de produtos disponíveis",
            'data' => ProductFamily::all()
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:product_families,code',
            'name' => 'required|string|max:255',
        ]);

        $family = ProductFamily::create($validated);


        return response()->json($family, 201);
    }
}
