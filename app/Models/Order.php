<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['store_id', 'user_id', 'payment_method', 'status', 'payment_status'];

    protected static function booted()
    {
        static::creating(function (Order $order) {
            // 20250001 , 20250002, ......
            $order->number = Order::getNextOrderNumber();
        });
    }

    /**
     * Returns the next order number for the current year.
     * If there are no orders for the current year, it returns the year followed by '0001'.
     * Otherwise, it returns the maximum order number for the current year plus one.
     *
     * @return string
     */
    public static function getNextOrderNumber()
    {
        $year = Carbon::now()->year;
        $number = Order::whereYear('created_at', $year)->max('number');

        if ($number) {
            return $number + 1;
        }
        return $year . '0001';
    }


    /**
     * Returns a many-to-many relationship between orders and products.
     *
     * This relationship uses the `order_items` pivot table and returns a collection of products that are associated with the order.
     * The pivot table also includes additional information, such as the quantity of each product and any options that were chosen.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items', 'order_id', 'product_id', 'id', 'id')
            ->using(OrderItem::class)
            ->withPivot([
                'product_name',
                'price',
                'quantity',
                'options'
            ]);
    }

    /**
     * Returns a has-many relationship between orders and addresses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    /*******  d3534b6e-db04-4c37-97b6-05700726a2d1  *******/
    public function addresses()
    {
        return $this->hasMany(OrderAddress::class);
    }

    /**
     * Returns a has-one relationship between orders and billing addresses.
     *
     * This relationship links an order to its billing address.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function billingAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'id')
            ->where('type', 'billing');
    }


    /**
     * Returns a has-one relationship between orders and shipping addresses.
     *
     * This relationship links an order to its shipping address.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shippingAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'id')
            ->where('type', 'shipping');
    }
    /**
     * Returns a belongs-to relationship between orders and stores.
     * This relationship links an order to the store that it was placed in.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Returns a belongs-to relationship between orders and users.
     * If the order does not belong to a user (e.g. a guest customer), it returns a user model with a name of 'Guest Customer'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Guest Customer'
        ]);
    }
}
