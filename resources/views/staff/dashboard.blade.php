<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="space-y-6">

        {{-- Welcome Banner --}}
        <div class="bg-gradient-to-r from-[#003087] to-[#00409a] rounded-xl p-5 flex items-center justify-between overflow-hidden relative">
            <div class="absolute right-0 top-0 w-64 h-full opacity-10">
                <svg viewBox="0 0 200 200" fill="none" class="w-full h-full">
                    <circle cx="160" cy="40" r="80" fill="white" />
                    <circle cx="40" cy="160" r="60" fill="white" />
                </svg>
            </div>
            <div class="relative">
                <p class="text-blue-200 text-sm">Welcome back 👋</p>
                <h2 class="text-white text-xl mt-0.5">{{ auth()->user()->name }}</h2>
                <p class="text-blue-300 text-sm mt-1">
                    {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                    @if(auth()->user()->branch)
                        · {{ auth()->user()->branch->name }}
                    @endif
                </p>
            </div>
            <div class="relative hidden sm:flex gap-2">
                <a href="{{ route('orders.index') }}"
                   class="px-4 py-2 bg-white/15 hover:bg-white/25 text-white text-sm rounded-lg transition-colors border border-white/20">
                    View Orders
                </a>
                <a href="{{ route('inventory.index') }}"
                   class="px-4 py-2 bg-[#CC2229] hover:bg-[#b81e24] text-white text-sm rounded-lg transition-colors">
                    Manage Inventory
                </a>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">

            {{-- Total Products --}}
            <div class="bg-white rounded-xl p-5 border-l-4 border-[#003087] shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Products</p>
                        <p class="text-gray-900 text-2xl mt-1">{{ $totalProducts }}</p>
                    </div>
                    <div class="p-2.5 rounded-lg bg-blue-50 text-[#003087]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">Total items in catalog</p>
            </div>

            {{-- Total Orders --}}
            <div class="bg-white rounded-xl p-5 border-l-4 border-emerald-500 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Orders</p>
                        <p class="text-gray-900 text-2xl mt-1">{{ $totalOrders }}</p>
                    </div>
                    <div class="p-2.5 rounded-lg bg-emerald-50 text-emerald-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">All time orders placed</p>
            </div>

            {{-- Active Tickets --}}
            <div class="bg-white rounded-xl p-5 border-l-4 border-orange-400 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Active Tickets</p>
                        <p class="text-gray-900 text-2xl mt-1">{{ $activeTickets }}</p>
                    </div>
                    <div class="p-2.5 rounded-lg bg-orange-50 text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">Tickets pending resolution</p>
            </div>

        </div>

        {{-- Recent Orders + Active Tickets --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Recent Orders Table --}}
            <div class="xl:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-gray-800 font-medium">Recent Orders</h3>
                    <a href="{{ route('orders.index') }}" class="text-[#003087] text-sm hover:underline">View all →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-5 py-3 text-xs text-gray-500 uppercase tracking-wide">Customer</th>
                                <th class="text-left px-5 py-3 text-xs text-gray-500 uppercase tracking-wide hidden sm:table-cell">Product</th>
                                <th class="text-left px-5 py-3 text-xs text-gray-500 uppercase tracking-wide hidden md:table-cell">Price</th>
                                <th class="text-left px-5 py-3 text-xs text-gray-500 uppercase tracking-wide">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-5 py-3.5 text-sm text-gray-800">{{ $order->user->name ?? 'N/A' }}</td>
                                    <td class="px-5 py-3.5 text-sm text-gray-600 hidden sm:table-cell">{{ $order->product->name ?? 'N/A' }}</td>
                                    <td class="px-5 py-3.5 text-sm text-gray-800 hidden md:table-cell">₱{{ number_format($order->price, 2) }}</td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs border
                                            {{ $order->status == 'pending' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                                            {{ $order->status == 'verified' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                            {{ $order->status == 'dispatched' ? 'bg-purple-50 text-purple-700 border-purple-200' : '' }}
                                            {{ $order->status == 'delivered' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-8 text-center text-gray-400 text-sm">No orders yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Active Tickets --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-gray-800 font-medium">Active Tickets</h3>
                    <a href="{{ route('tickets.index') }}" class="text-[#003087] text-sm hover:underline">View all →</a>
                </div>
                <div class="p-4 space-y-3">
                    @forelse($activeTicketsList as $ticket)
                        <div class="p-3 rounded-lg border border-gray-100 hover:border-gray-200 transition-colors">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <span class="text-xs text-[#003087]">TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-xs px-1.5 py-0.5 rounded
                                    {{ $ticket->status == 'received' ? 'bg-gray-100 text-gray-500' : '' }}
                                    {{ $ticket->status == 'diagnosing' ? 'bg-yellow-50 text-yellow-600' : '' }}
                                    {{ $ticket->status == 'waiting_for_parts' ? 'bg-red-50 text-red-600' : '' }}
                                    {{ $ticket->status == 'repaired' ? 'bg-emerald-50 text-emerald-600' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-800">{{ $ticket->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($ticket->issue_description, 40) }}</p>
                        </div>
                    @empty
                        <p class="text-center text-gray-400 text-sm py-4">No active tickets</p>
                    @endforelse

                    <a href="{{ route('tickets.index') }}"
                       class="block w-full py-2 text-sm text-center text-[#003087] hover:bg-blue-50 rounded-lg transition-colors border border-dashed border-[#003087]/30">
                        View All Tickets
                    </a>
                </div>
            </div>

        </div>

        {{-- Quick Links --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('inventory.index') }}"
               class="bg-white rounded-xl p-5 text-left border border-transparent hover:border-[#003087] transition-all shadow-sm hover:shadow-md">
                <div class="text-2xl mb-2">📦</div>
                <p class="text-gray-800 text-sm font-medium">Manage Inventory</p>
                <p class="text-gray-400 text-xs mt-0.5">Add, edit, or remove products</p>
            </a>
            <a href="{{ route('orders.index') }}"
               class="bg-white rounded-xl p-5 text-left border border-transparent hover:border-emerald-500 transition-all shadow-sm hover:shadow-md">
                <div class="text-2xl mb-2">🛒</div>
                <p class="text-gray-800 text-sm font-medium">Process Orders</p>
                <p class="text-gray-400 text-xs mt-0.5">View and update order statuses</p>
            </a>
            <a href="{{ route('tickets.index') }}"
               class="bg-white rounded-xl p-5 text-left border border-transparent hover:border-orange-400 transition-all shadow-sm hover:shadow-md">
                <div class="text-2xl mb-2">🔧</div>
                <p class="text-gray-800 text-sm font-medium">Repair Tickets</p>
                <p class="text-gray-400 text-xs mt-0.5">Manage warranty and repairs</p>
            </a>
        </div>

    </div>
</x-app-layout>