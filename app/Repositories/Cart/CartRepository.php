<?php

namespace App\Repositories\Cart;

use App\Models\Product;
use Illuminate\Support\Collection;

interface CartRepository
{
    /**
     * Get the cart contents.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection;

    /**
     * Add a product to the cart.
     *
     * @param \App\Models\Product $product
     */
    public function add(Product $product, $quantity = 1);

    /**
     * Update the quantity of a product in the cart.
     *
     * @param $id
     * @param int $quantity
     */
    public function update($id, int $quantity);

    /**
     * Remove a product from the cart.
     *
     * @param \App\Models\Product $product
     */
    public function delete($id);

    /**
     * Empty the cart.
     */
    public function empty();

    /**
     * Get the total of the cart.
     *
     * @return float
     */
    public function total(): float;
}
