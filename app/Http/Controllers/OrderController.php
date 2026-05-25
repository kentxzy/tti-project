<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Branch;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isCustomer()) {
            $orders = Order::with(['orderItems.product', 'branch', 'user'])
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $orders = Order::with(['orderItems.product', 'branch', 'user'])->get();
        }

        $branches = Branch::all();

        return view('orders.index', compact('orders', 'branches'));
    }

    public function create()
    {
        $products = Product::all();
        $branches = Branch::all();
        return view('orders.create', compact('products', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'branch_id'  => 'required|exists:branches,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $order = Order::create([
            'user_id'   => Auth::id(),
            'branch_id' => $request->branch_id,
            'price'     => $product->price * $request->quantity,
            'status'    => 'pending',
        ]);

        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $request->product_id,
            'quantity'   => $request->quantity,
            'unit_price' => $product->price,
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order placed successfully!');
    }

    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,dispatched,delivered',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('orders.index')
            ->with('success', 'Order status updated successfully!');
    }

    /**
     * Customer cancel — only allowed if order is still pending
     * and belongs to the logged-in customer.
     */
    public function destroy(Order $order)
    {
        // Make sure this order belongs to the customer
        if ($order->user_id !== Auth::id()) {
            abort(403, 'You can only cancel your own orders.');
        }

        // Only pending orders can be cancelled
        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')
                ->with('error', 'Only pending orders can be cancelled. This order is already ' . $order->status . '.');
        }

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order cancelled successfully.');
    }

    /**
     * Staff force-delete — no status restriction.
     */
    public function forceDestroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}