<?php

namespace App\Http\Controllers\Front;

use App\Events\OrderCreated;
use App\Exceptions\InvalidOrderException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Intl\Countries;
use Throwable;

class CheckoutController extends Controller
{
    public function create(CartRepository $cart)
    {
        if ($cart->get()->isEmpty()) {
            throw new InvalidOrderException('Cart is empty');
        }
        return view('front.checkout', [
            'cart' => $cart,
            'countries' => Countries::getNames(),
        ]);
    }

    public function store(Request $request, CartRepository $cart)
    {
        // Validate Request
        $request->validate([
            'addr.billing.first_name' => ['required', 'string', 'max:255'],
            'addr.billing.last_name' => ['required', 'string', 'max:255'],
            'addr.billing.email' => ['required', 'string', 'max:255'],
            'addr.billing.phone_number' => ['required', 'string', 'max:255'],
            'addr.billing.city' => ['required', 'string', 'max:255'],
        ]);

        // Group cart items by store
        $items = $cart->get()->groupBy('product.store_id')->all();

        // Begin Transaction
        DB::beginTransaction();

        try {
            // Create orders per store
            foreach ($items as $store_id => $cart_items) {
                // Create Order
                $order = Order::create([
                    'store_id' => $store_id,
                    'user_id' => Auth::id(),
                    'payment_method' => 'cod'
                ]);
                // Create Order Items
                foreach ($cart_items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'price' => $item->product->price,
                        'quantity' => $item->quantity
                    ]);
                }
                // Create Order Addresses
                foreach ($request->post('addr') as $type => $address) {
                    $address['type'] = $type;
                    $order->addresses()->create($address);
                }
            }
            // Commit Transaction
            DB::commit();

            // Fire Order Created Event
            event(new OrderCreated($order));
        } catch (Throwable $e) {
            // Rollback Transaction on Error
            DB::rollBack();
            throw $e;
        }
        // return redirect()->route('home')
        //     ->with('success', 'Your order has been placed successfully.');
    }
}
