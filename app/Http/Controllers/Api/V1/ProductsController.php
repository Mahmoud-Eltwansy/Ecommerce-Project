<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ProductsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products =  Product::filter($request->query())
            ->with('category:id,name', 'store:id,name', 'tags:id,name')
            ->paginate();

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user->tokenCan('products.create')) {
            return response([
                'message' => 'Not Allowd'
            ], 403);
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3', 'unique:products,name'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'status' => ['in:active,archived,draft'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'gt:price'],
            'image' => ['nullable', 'image', 'max:1024'],
        ]);
        $product = Product::create($request->all());

        return Response::json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $user = $request->user();
        if (!$user->tokenCan('products.update')) {
            return response([
                'message' => 'Not Allowd'
            ], 403);
        }
        $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', 'min:3', 'unique:products,name,' . $product->id],
            'category_id' => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'status' => ['in:active,archived,draft'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'gt:price'],
            'image' => ['nullable', 'image', 'max:1024'],
        ]);
        $product->update($request->all());
        return Response::json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user->tokenCan('products.delete')) {
            return response([
                'message' => 'Not Allowd'
            ], 403);
        }
        try {
            $product = Product::findOrFail($id);
            $deleted = $product->delete();

            if (!$deleted) {
                return response()->json([
                    'message' => 'Deletion failed.'
                ], 500);
            }

            return response()->json([
                'message' => 'Product deleted successfully..',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Unable to delete a product.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
