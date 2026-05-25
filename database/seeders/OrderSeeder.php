<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Helper: create an order + its single order item
        $makeOrder = function (int $userId, int $branchId, int $productId, int $qty, string $status) {
            $product = Product::find($productId);

            $order = Order::create([
                'user_id'   => $userId,
                'branch_id' => $branchId,
                'price'     => $product->price * $qty,
                'status'    => $status,
            ]);

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $productId,
                'quantity'   => $qty,
                'unit_price' => $product->price,
            ]);

            return $order;
        };

        // ---------------------------------------------------------------
        // Customer (user_id = 1) orders — covers all 4 order statuses
        // ---------------------------------------------------------------

        // pending — just placed, no action yet
        $makeOrder(1, 1, 1, 1, 'pending');          // Laptop x1 @ Davao

        // verified — sales rep has confirmed it
        $makeOrder(1, 1, 2, 2, 'verified');         // Printer x2 @ Davao

        // dispatched — on its way
        $makeOrder(1, 2, 1, 1, 'dispatched');       // Laptop x1 @ Cebu

        // delivered — completed order (can raise a ticket against this)
        $makeOrder(1, 1, 3, 1, 'delivered');        // Office Chair x1 @ Davao

        // delivered — second delivered order so there are two ticket candidates
        $makeOrder(1, 3, 2, 1, 'delivered');        // Printer x1 @ Cagayan
    }
}