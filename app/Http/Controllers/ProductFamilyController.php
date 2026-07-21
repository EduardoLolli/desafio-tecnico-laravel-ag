<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFamilyRequest;
use App\Http\Resources\ProductFamilyResource;
use App\Models\ProductFamily;
use Exception;
use Illuminate\Http\Request;

class ProductFamilyController extends Controller
{
    public function index(Request $request)
    {
        try {
            $family = ProductFamily::query()
                ->when($request->filled('name'), function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->name . '%');
                })->get();

            return response()->json([
                'success' => true,
                'message' => "Famílias de produtos disponíveis",
                'data' => $family
            ], 200);
        } catch (Exception $e) {
            response()->json([
                'success' => false,
                'message' => "Falha ao lista Familias de produtos",
                'data' => []
            ], 404);
        }
    }

    public function store(StoreFamilyRequest $request)
    {
        try {
            $validated = $request->validated();
            $family = ProductFamily::create($validated);
            return response()->json([
                'success' => true,
                'message' => 'Família criada com sucesso',
                'data' => new ProductFamilyResource($family)
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao criar Família: ' . $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function destroy(string $identifier)
    {
        try {
            $family = ProductFamily::query()
                ->where('code', $identifier)
                ->first();

            if (!$family) {
                throw new Exception("Familia não encontrado");
            }

            $family->delete();

            return response()->json([
                'success' => true,
                'message' => 'Família removido com sucesso.',
                'data' => []
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao excluir família: ' . $e->getMessage(),
                'data' => []
            ], 404);
        }
    }
}
