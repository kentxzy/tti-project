<x-app-layout>
    <x-slot name="title">Order Management</x-slot>

    <div class="space-y-5">

        {{-- Status Summary Pills --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @php
                $statusConfig = [
                    'pending'    => ['dot' => 'bg-yellow-400', 'text' => 'text-yellow-700', 'bg' => 'bg-yellow-50 border-yellow-200',   'label' => 'Pending'],
                    'verified'   => ['dot' => 'bg-blue-500',   'text' => 'text-blue-700',   'bg' => 'bg-blue-50 border-blue-200',       'label' => 'Verified'],
                    'dispatched' => ['dot' => 'bg-purple-500', 'text' => 'text-purple-700', 'bg' => 'bg-purple-50 border-purple-200',   'label' => 'Dispatched'],
                    'delivered'  => ['dot' => 'bg-emerald-500','text' => 'text-emerald-700','bg' => 'bg-emerald-50 border-emerald-200', 'label' => 'Delivered'],
                ];
            @endphp

            @foreach($statusConfig as $key => $cfg)
                <div class="flex items-center gap-2 px-4 py-3 rounded-xl border bg-white border-gray-100 shadow-sm">
                    <span class="w-2.5 h-2.5 rounded-full shrink-0 {{ $cfg['dot'] }}"></span>
                    <div>
                        <p class="text-sm text-gray-700">{{ $cfg['label'] }}</p>
                        <p class="text-lg leading-tight text-gray-800">
                            {{ $orders->where('status', $key)->count() }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm text-emerald-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Filters --}}
        <div class="bg-white rounded-xl p-4 shadow-sm flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="searchInput" placeholder="Search orders, customers, products..."
                    class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-gray-50" />
            </div>
            <select id="branchFilter" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 bg-gray-50 text-gray-700">
                <option value="">All Branches</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                @endforeach
            </select>
            <select id="statusFilter" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 bg-gray-50 text-gray-700">
                <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="verified">Verified</option>
                <option value="dispatched">Dispatched</option>
                <option value="delivered">Delivered</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full" id="ordersTable">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide">Order</th>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide">Customer</th>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide hidden sm:table-cell">Product</th>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide hidden md:table-cell">Branch</th>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide">Price</th>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-right px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" id="ordersBody">
                        @forelse($orders as $order)
                            @php
                                $cfg = $statusConfig[$order->status] ?? $statusConfig['pending'];
                                $firstItem = $order->orderItems->first();
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition-colors order-row"
                                data-branch="{{ $order->branch->name ?? '' }}"
                                data-status="{{ $order->status }}"
                                data-search="{{ strtolower($order->user->name ?? '') }} {{ strtolower($firstItem->product->name ?? '') }} ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}">
                                <td class="px-5 py-3.5">
                                    <p class="text-sm text-[#003087] font-medium">ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    <p class="text-xs text-gray-400">{{ $order->created_at->format('Y-m-d') }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-sm text-gray-800">
                                    {{ $order->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-3.5 text-sm text-gray-600 hidden sm:table-cell max-w-[200px] truncate">
                                    {{ $firstItem->product->name ?? 'N/A' }}
                                    @if($order->orderItems->count() > 1)
                                        <span class="text-xs text-gray-400">+{{ $order->orderItems->count() - 1 }} more</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-sm text-gray-600 hidden md:table-cell">
                                    {{ $order->branch->name ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-3.5 text-sm text-gray-800">
                                    ₱{{ number_format($order->price, 2) }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs border {{ $cfg['bg'] }} {{ $cfg['text'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-2">
                                        
                                        @if(Auth::user()->role !== 'customer')
                                            {{-- Staff: show Update Status button --}}
                                            @if(!in_array($order->status, ['delivered', 'cancelled']))
                                                <a href="{{ route('orders.edit', $order->id) }}"
                                                    class="px-3 py-1.5 text-xs bg-[#003087] hover:bg-[#002266] text-white rounded-md transition-colors">
                                                        Update Status
                                                </a>
                                            @endif
                                        @endif

                                        @if($order->status === 'pending')
                                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline"
                                                    onsubmit="return confirm('Cancel this order?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1.5 text-xs text-[#CC2229] hover:bg-red-50 border border-[#CC2229]/30 rounded-md transition-colors">
                                                    Cancel
                                                </button>
                                            </form>
                                        @elseif(in_array($order->status, ['delivered', 'cancelled']))
                                            <span class="text-xs text-gray-400 italic">No actions</span>
                                        @else
                                            @if(Auth::user()->role === 'customer')
                                            <span class="text-xs text-gray-400 italic">In progress</span>
                                            @endif
                                        @endif
                                     </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs text-gray-400" id="orderCount">{{ $orders->count() }} orders</p>
            </div>
        </div>

    </div>

    {{-- Live search + filter script --}}
    <script>
        const searchInput  = document.getElementById('searchInput');
        const branchFilter = document.getElementById('branchFilter');
        const statusFilter = document.getElementById('statusFilter');
        const rows         = document.querySelectorAll('.order-row');
        const countEl      = document.getElementById('orderCount');

        function filterRows() {
            const search = searchInput.value.toLowerCase();
            const branch = branchFilter.value.toLowerCase();
            const status = statusFilter.value.toLowerCase();
            let visible  = 0;

            rows.forEach(row => {
                const matchSearch = row.dataset.search.includes(search);
                const matchBranch = !branch || row.dataset.branch.toLowerCase() === branch;
                const matchStatus = !status || row.dataset.status === status;
                const show = matchSearch && matchBranch && matchStatus;
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            countEl.textContent = visible + ' of {{ $orders->count() }} orders';
        }

        searchInput.addEventListener('input', filterRows);
        branchFilter.addEventListener('change', filterRows);
        statusFilter.addEventListener('change', filterRows);
    </script>

</x-app-layout>