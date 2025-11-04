<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\Cart\CartModelRepository;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{

    protected $cart;
    public function __construct(CartRepository $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ($this->cart->get()->isEmpty()) {
            return redirect()->route('home')->with('info', 'Your cart is empty. Please add items to your cart.');
        }
        return view('front.cart', [
            'cart' => $this->cart
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate Request
        $request->validate([
            'product_id' => ['required', 'int', 'exists:products,id'],
            'quantity' => ['nullable', 'int', 'min:1']
        ]);
        // Add to Cart
        $product = Product::findOrFail($request->post('product_id'));
        $this->cart->add($product, $request->post('quantity'));

        // For AJAX request
        if ($request->ajax()) {
            return response()->json([
                'message' => 'Product added to cart',
            ], 201);
        }
        // For normal request
        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate Request
        $request->validate([
            'quantity' => ['required', 'int', 'min:1']
        ]);
        // Update Cart Item quantity
        $this->cart->update($id, $request->post('quantity'));
        return [
            'message' => 'Cart Updated',
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->cart->delete($id);
        return [
            'message' => 'Item Deleted',
        ];
    }
}
