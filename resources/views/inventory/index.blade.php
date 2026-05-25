<x-app-layout>
    <x-slot name="title">Inventory Management</x-slot>

    <div class="space-y-5">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 justify-between">
            <p class="text-gray-500 text-sm">{{ $inventories->count() }} records found</p>
            <a href="{{ route('inventory.create') }}"
               class="flex items-center gap-2 px-4 py-2 bg-[#003087] hover:bg-[#002266] text-white text-sm rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Product
            </a>
        </div>

        {{-- Success message --}}
        @if(session('success'))
            <div class="flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm text-emerald-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide">Product</th>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide hidden sm:table-cell">Category</th>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide">Price</th>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide hidden md:table-cell">Branch</th>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide">Stock</th>
                            <th class="text-right px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($inventories as $inventory)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-5 py-3.5">
                                    <p class="text-sm text-gray-800">{{ $inventory->product->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400">PRD-{{ str_pad($inventory->product->id ?? 0, 3, '0', STR_PAD_LEFT) }}</p>
                                </td>
                                <td class="px-5 py-3.5 hidden sm:table-cell">
                                    <span class="text-xs px-2 py-1 bg-blue-50 text-[#003087] rounded-md">
                                        {{ $inventory->product->category ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-sm text-gray-800">
                                    ₱{{ number_format($inventory->product->price ?? 0, 2) }}
                                </td>
                                <td class="px-5 py-3.5 text-sm text-gray-600 hidden md:table-cell">
                                    {{ $inventory->branch->name ?? 'N/A' }}
                                    <span class="text-xs text-gray-400">— {{ $inventory->branch->city ?? '' }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    @php
                                        $stock = $inventory->stock;
                                        $badgeClass = $stock == 0
                                            ? 'bg-red-50 text-red-600 border-red-200'
                                            : ($stock <= 3
                                                ? 'bg-yellow-50 text-yellow-700 border-yellow-200'
                                                : 'bg-emerald-50 text-emerald-700 border-emerald-200');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs border {{ $badgeClass }}">
                                        {{ $stock == 0 ? 'Out of Stock' : $stock . ' units' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('inventory.edit', $inventory->id) }}"
                                           class="p-1.5 text-[#003087] hover:bg-blue-50 rounded-md transition-colors"
                                           title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Delete this inventory record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-1.5 text-[#CC2229] hover:bg-red-50 rounded-md transition-colors"
                                                    title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-gray-400 text-sm">
                                    No inventory records found. Add your first product!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>