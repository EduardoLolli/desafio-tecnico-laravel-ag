<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $products = Product::with('family')
                ->filter($request->only(['name', 'min_price', 'max_price', 'min_qtt', 'max_qtt', 'family']))
                ->paginate(10);

            if ($products->isEmpty()) {
                throw new Exception("Nenhum produto foi encontrado");
            }

            return response()->json([
                'success' => true,
                'message' => "Produtos disponíveis",
                'data' => ProductResource::collection($products)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 404);
        }
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $validated = $request->validated();
            $product = Product::create($validated);
            $product->load('family');

            return response()->json([
                'success' => true,
                'message' => 'Produto criado com sucesso',
                'data' => new ProductResource($product)
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao criar produto: ' . $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function destroy(string $identifier)
    {
        try {
            $product = Product::query()
                ->where('code', $identifier)
                ->orWhere('barcode', $identifier)
                ->first();

            if (!$product) {
                throw new Exception("Produto não encontrado");
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produto removido com sucesso.',
                'data' => []
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao excluir produto: ' . $e->getMessage(),
                'data' => []
            ], 404);
        }
    }

    public function show(string $code)
    {
        try {
            $product = Product::where('code', $code)
                ->with('family')->first();

            if (!$product) {
                throw new Exception("Produto não encontrado");
            }

            return response()->json([
                'success' => true,
                'message' => "",
                'data' => new ProductResource($product)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao exibir produtos: ' . $e->getMessage(),
                'data' => []
            ], 404);
        }
    }

    public function update(UpdateProductRequest $request, string $identifier)
    {
        try {
            $newdata = $request->validated();
            $product = Product::where('code', $identifier)->first();

            if (!$product) {
                throw new Exception('Produto não encontrado.');
            }

            $product->update($newdata);

            return response()->json([
                'success' => true,
                'message' => 'Produto atualizado com sucesso',
                'data'    => $product
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao atualizar produto: ' . $e->getMessage(),
                'data' => []
            ], 404);
        }
    }
}
