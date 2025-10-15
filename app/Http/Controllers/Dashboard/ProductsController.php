<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::filter($request->query())->paginate();

        return view('dashboard.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {

        return view('dashboard.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        // Redirect With Message
        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product Deleted!');
    }

    /**
     * Get all trashed products.
     *
     * This method will return all trashed products.
     *
     * The results can be filtered by using the query string.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function trash(Request $request)
    {
        $products = Product::onlyTrashed()->filter($request->query())->paginate();
        return view('dashboard.products.trash', compact('products'));
    }

    /**
     * Restore a trashed product.
     *
     * This method will restore a trashed product given its id.
     *
     * The method will redirect to the trashed products page with a success message.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, $id)
    {
        $category = Product::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('dashboard.products.trash')->with('success', 'Product Restored Succussfully!');
    }

    public function forceDelete(Request $request, $id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete();
        // Delete Image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        return redirect()->route('dashboard.products.trash')->with('success', 'Product Deleted Forever!');
    }
}
