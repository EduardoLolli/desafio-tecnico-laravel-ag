<?php

namespace App\Http\Controllers;

use App\Models\ProductFamily;
use Illuminate\Http\Request;

class ProductFamilyController extends Controller
{
    public function index(Request $request) 
    {
        $family = ProductFamily::query()
        ->when($request->filled('name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            })->get();

        return response()->json([
            'message' => "Famílias de produtos disponíveis",
            'data' => $family
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
