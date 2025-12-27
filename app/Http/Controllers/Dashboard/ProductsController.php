<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with(['category', 'store'])
            ->filter($request->query())
            ->paginate();
        return view('dashboard.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.products.create', [
            'product' => new Product(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        $tags = implode(',', $product->tags()->pluck('name')->toArray());
        return view('dashboard.products.edit', compact('product', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $tag_ids = [];
        // Validation
        $request->validate(Product::rules($product->id));

        // Update the product
        $product->update($request->except('tags'));

        $tags = json_decode($request->post('tags'));
        // Convert the tags array into a collection of strings
        $tags = collect($tags)->pluck('value');

        // Get the slugs of these tags
        $slugs = $tags->map(fn($t) => Str::slug($t));

        // Get the tags in the DB that match with the slugs
        $existingTags = Tag::whereIn('slug', $slugs)->get();

        foreach ($tags as $tagName) {
            $slug = Str::slug($tagName);
            // search if the current tag in the loop exists in the returned ones from DB
            $tag = $existingTags->where('slug', $slug)->first();
            if (!$tag) {
                //Creat new tag if it doesn't exist
                $tag = Tag::create([
                    'name' => $tagName,
                    'slug' => $slug
                ]);
            }
            $tag_ids[] = $tag->id;
        }
        // Save all the tags with deletion process of the old ones
        $product->tags()->sync($tag_ids);


        return redirect()->route('dashboard.products.index')->with('success', 'Product Updated!');
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
        $product = Product::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $product);

        $product->restore();

        return redirect()->route('dashboard.products.trash')->with('success', 'Product Restored Succussfully!');
    }

    public function forceDelete(Request $request, $id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $product);

        $product->forceDelete();
        // Delete Image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        return redirect()->route('dashboard.products.trash')->with('success', 'Product Deleted Forever!');
    }
}
