<x-app-layout>
    <x-slot name="title">My Dashboard</x-slot>

    <div class="space-y-6">

        {{-- Welcome banner --}}
        <div class="bg-gradient-to-r from-[#003087] to-[#00409a] rounded-xl p-5 flex items-center justify-between overflow-hidden relative">
            <div class="absolute right-0 top-0 w-64 h-full opacity-10">
                <svg viewBox="0 0 200 200" fill="none" class="w-full h-full">
                    <circle cx="160" cy="40" r="80" fill="white" />
                    <circle cx="40" cy="160" r="60" fill="white" />
                </svg>
            </div>
            <div class="relative">
                <p class="text-blue-200 text-sm">Welcome back 👋</p>
                <h2 class="text-white text-xl mt-0.5">{{ Auth::user()->name }}</h2>
                <p class="text-blue-300 text-sm mt-1">Customer Account</p>
            </div>
            <div class="relative hidden sm:flex gap-2">
                <a href="{{ route('orders.create') }}"
                   class="px-4 py-2 bg-white/15 hover:bg-white/25 text-white text-sm rounded-lg transition-colors border border-white/20">
                    Place Order
                </a>
                <a href="{{ route('tickets.create') }}"
                   class="px-4 py-2 bg-[#CC2229] hover:bg-[#b81e24] text-white text-sm rounded-lg transition-colors">
                    Submit Ticket
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl p-5 border-l-4 border-[#003087] shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">My Orders</p>
                        <p class="text-gray-900 text-2xl mt-1">{{ $totalOrders }}</p>
                    </div>
                    <div class="p-2.5 rounded-lg bg-blue-50 text-[#003087]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 border-l-4 border-orange-400 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">My Tickets</p>
                        <p class="text-gray-900 text-2xl mt-1">{{ $totalTickets }}</p>
                    </div>
                    <div class="p-2.5 rounded-lg bg-orange-50 text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- Recent Orders --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-gray-800">My Recent Orders</h3>
                    <a href="{{ route('orders.index') }}" class="text-[#003087] text-sm hover:underline">View all →</a>
                </div>
                @php
                    $statusConfig = [
                        'pending'    => ['text' => 'text-yellow-700', 'bg' => 'bg-yellow-50 border-yellow-200',   'dot' => 'bg-yellow-400'],
                        'verified'   => ['text' => 'text-blue-700',   'bg' => 'bg-blue-50 border-blue-200',       'dot' => 'bg-blue-500'],
                        'dispatched' => ['text' => 'text-purple-700', 'bg' => 'bg-purple-50 border-purple-200',   'dot' => 'bg-purple-500'],
                        'delivered'  => ['text' => 'text-emerald-700','bg' => 'bg-emerald-50 border-emerald-200', 'dot' => 'bg-emerald-500'],
                    ];
                @endphp
                <div class="divide-y divide-gray-50">
                    @forelse($myOrders as $order)
                        @php $cfg = $statusConfig[$order->status] ?? $statusConfig['pending']; @endphp
                        <div class="px-5 py-3.5 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#003087] font-medium">ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $order->orderItems->first()->product->name ?? 'N/A' }}
                                    @if($order->orderItems->count() > 1)
                                        <span class="text-gray-400">+{{ $order->orderItems->count() - 1 }} more</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs border {{ $cfg['bg'] }} {{ $cfg['text'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                    {{ ucfirst($order->status) }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">₱{{ number_format($order->price, 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-gray-400 text-sm">
                            No orders yet.
                            <a href="{{ route('orders.create') }}" class="text-[#003087] hover:underline ml-1">Place your first order</a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Tickets --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-gray-800">My Tickets</h3>
                    <a href="{{ route('tickets.index') }}" class="text-[#003087] text-sm hover:underline">View all →</a>
                </div>
                @php
                    $ticketConfig = [
                        'received'          => ['text' => 'text-gray-600',   'bg' => 'bg-gray-100 border-gray-200',     'dot' => 'bg-gray-400'],
                        'diagnosing'        => ['text' => 'text-blue-700',   'bg' => 'bg-blue-50 border-blue-200',       'dot' => 'bg-blue-500'],
                        'waiting_for_parts' => ['text' => 'text-orange-700', 'bg' => 'bg-orange-50 border-orange-200',   'dot' => 'bg-orange-400'],
                        'repaired'          => ['text' => 'text-emerald-700','bg' => 'bg-emerald-50 border-emerald-200', 'dot' => 'bg-emerald-500'],
                        'released'          => ['text' => 'text-purple-700', 'bg' => 'bg-purple-50 border-purple-200',   'dot' => 'bg-purple-500'],
                    ];
                @endphp
                <div class="divide-y divide-gray-50">
                    @forelse($myTickets as $ticket)
                        @php $cfg = $ticketConfig[$ticket->status] ?? $ticketConfig['received']; @endphp
                        <div class="px-5 py-3.5 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#003087] font-medium">TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $ticket->orderItem->product->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5 truncate max-w-[200px]">{{ $ticket->issue_description }}</p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs border {{ $cfg['bg'] }} {{ $cfg['text'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-gray-400 text-sm">
                            No tickets yet.
                            <a href="{{ route('tickets.create') }}" class="text-[#003087] hover:underline ml-1">Submit a ticket</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('orders.create') }}"
               class="bg-white rounded-xl p-5 border border-transparent hover:border-[#003087] transition-all shadow-sm hover:shadow-md">
                <div class="text-2xl mb-2">🛒</div>
                <p class="text-gray-800 text-sm">Place New Order</p>
                <p class="text-gray-400 text-xs mt-0.5">Browse and order products</p>
            </a>
            <a href="{{ route('tickets.create') }}"
               class="bg-white rounded-xl p-5 border border-transparent hover:border-orange-400 transition-all shadow-sm hover:shadow-md">
                <div class="text-2xl mb-2">🔧</div>
                <p class="text-gray-800 text-sm">Submit Warranty Ticket</p>
                <p class="text-gray-400 text-xs mt-0.5">Report an issue with your purchase</p>
            </a>
        </div>

    </div>
</x-app-layout>