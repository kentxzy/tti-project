<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Product;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $totalProducts   = Product::count();
        $totalOrders     = Order::count();
        $activeTickets   = Ticket::whereNotIn('status', ['released'])->count();

        $recentOrders = Order::with(['user', 'orderItems.product', 'branch'])
            ->latest()
            ->take(5)
            ->get();

        $activeTicketsList = Ticket::with(['user', 'orderItem.product'])
            ->whereNotIn('status', ['released'])
            ->latest()
            ->take(3)
            ->get();

        return view('staff.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'activeTickets',
            'recentOrders',
            'activeTicketsList'
        ));
    }
}