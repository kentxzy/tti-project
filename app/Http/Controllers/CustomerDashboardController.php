<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Ticket;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $myOrders = Order::with(['orderItems.product', 'branch'])
            ->where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        $myTickets = Ticket::with(['orderItem.product'])
            ->where('user_id', Auth::id())
            ->latest()
            ->take(3)
            ->get();

        $totalOrders  = Order::where('user_id', Auth::id())->count();
        $totalTickets = Ticket::where('user_id', Auth::id())->count();

        return view('customer.dashboard', compact(
            'myOrders',
            'myTickets',
            'totalOrders',
            'totalTickets'
        ));
    }
}