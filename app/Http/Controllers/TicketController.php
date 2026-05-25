<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\OrderItem;

class TicketController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'customer') {
            $tickets = Ticket::with(['user', 'orderItem.product'])->get();
        } else {
            $tickets = Ticket::with(['user', 'orderItem.product'])
                ->where('user_id', Auth::id())
                ->get();
        }

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        // Only show order items that belong to the logged-in customer
        $orderItems = OrderItem::with('product')
            ->whereHas('order', fn($q) => $q->where('user_id', Auth::id()))
            ->get();

        return view('tickets.create', compact('orderItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_item_id'     => 'required|exists:order_items,id',
            'issue_description' => 'required|string',
            'contact_number'    => 'required|string|max:20',
        ]);

        Ticket::create([
            'user_id'           => Auth::id(),
            'order_item_id'     => $request->order_item_id,
            'issue_description' => $request->issue_description,
            'contact_number'    => $request->contact_number,
            'status'            => 'received',
        ]);

        return redirect()->route('tickets.index')
            ->with('success', 'Warranty ticket submitted successfully!');
    }

    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:received,diagnosing,repaired,released',
        ]);

        $ticket->update(['status' => $request->status]);

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket status updated successfully!');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully!');
    }
}