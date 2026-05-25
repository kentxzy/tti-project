<x-app-layout>
    <x-slot name="title">Warranty & Repair Tickets</x-slot>

    <div class="space-y-5">

        {{-- Status Summary Pills --}}
        @php
            $statusConfig = [
                'received'          => ['dot' => 'bg-gray-400',   'text' => 'text-gray-600',   'bg' => 'bg-gray-100 border-gray-200',     'label' => 'Received'],
                'diagnosing'        => ['dot' => 'bg-blue-500',   'text' => 'text-blue-700',   'bg' => 'bg-blue-50 border-blue-200',       'label' => 'Diagnosing'],
                'repaired'          => ['dot' => 'bg-emerald-500','text' => 'text-emerald-700','bg' => 'bg-emerald-50 border-emerald-200', 'label' => 'Repaired'],
                'released'          => ['dot' => 'bg-purple-500', 'text' => 'text-purple-700', 'bg' => 'bg-purple-50 border-purple-200',   'label' => 'Released'],
            ];
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            @foreach($statusConfig as $key => $cfg)
                <div class="flex items-center gap-2 px-3 py-3 rounded-xl border bg-white border-gray-100 shadow-sm">
                    <span class="w-2.5 h-2.5 rounded-full shrink-0 {{ $cfg['dot'] }}"></span>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 truncate">{{ $cfg['label'] }}</p>
                        <p class="text-lg leading-tight text-gray-800">
                            {{ $tickets->where('status', $key)->count() }}
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

        {{-- Search + Submit Button --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="searchInput" placeholder="Search tickets, customers, products..."
                    class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-white shadow-sm" />
            </div>
            @if(Auth::user()->role === 'customer')
                <a href="{{ route('tickets.create') }}"
                   class="flex items-center gap-2 px-4 py-2 bg-[#003087] hover:bg-[#002266] text-white text-sm rounded-lg transition-colors shadow-sm shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Submit Ticket
                </a>
            @endif
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide">Ticket</th>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide">Customer</th>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide hidden sm:table-cell">Product</th>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide hidden md:table-cell">Issue</th>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide hidden lg:table-cell">Contact</th>
                            <th class="text-left px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-right px-5 py-3.5 text-xs text-gray-500 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" id="ticketsBody">
                        @forelse($tickets as $ticket)
                            @php $cfg = $statusConfig[$ticket->status] ?? $statusConfig['received']; @endphp
                            <tr class="hover:bg-gray-50/60 transition-colors ticket-row"
                                data-search="{{ strtolower($ticket->user->name ?? '') }} {{ strtolower($ticket->orderItem->product->name ?? '') }} TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}">
                                <td class="px-5 py-3.5">
                                    <p class="text-sm text-[#003087] font-medium">TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</p>
                                    <p class="text-xs text-gray-400">{{ $ticket->created_at->format('Y-m-d') }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-sm text-gray-800">
                                    {{ $ticket->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-3.5 text-sm text-gray-600 hidden sm:table-cell max-w-[150px] truncate">
                                    {{ $ticket->orderItem->product->name ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-3.5 hidden md:table-cell max-w-[200px]">
                                    <p class="text-sm text-gray-600 truncate">{{ $ticket->issue_description }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-sm text-gray-600 hidden lg:table-cell">
                                    {{ $ticket->contact_number }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs border {{ $cfg['bg'] }} {{ $cfg['text'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                        {{ $cfg['label'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-2">
                                      @if($ticket->status === 'released')
    <span class="text-xs text-gray-400 italic">Completed</span>
@elseif($ticket->status === 'repaired')
    <span class="text-xs text-gray-400 italic">Ready for Pickup</span>
@elseif($ticket->status === 'diagnosing')
    <span class="text-xs text-gray-400 italic">In Progress</span>
@else
    @if(Auth::user()->role !== 'customer')
        <a href="{{ route('tickets.edit', $ticket->id) }}"
           class="px-3 py-1.5 text-xs bg-[#003087] hover:bg-[#002266] text-white rounded-md transition-colors whitespace-nowrap">
            Update Status
        </a>
    @endif
    @if(Auth::user()->role === 'customer' && $ticket->status === 'received')
        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline"
              onsubmit="return confirm('Delete this ticket?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="px-3 py-1.5 text-xs text-[#CC2229] hover:bg-red-50 border border-[#CC2229]/30 rounded-md transition-colors">
                Delete
            </button>
        </form>
    @endif
@endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">
                                    No tickets found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-gray-100">
                <p class="text-xs text-gray-400" id="ticketCount">{{ $tickets->count() }} tickets</p>
            </div>
        </div>

    </div>

    {{-- Live search script --}}
    <script>
        const searchInput = document.getElementById('searchInput');
        const rows        = document.querySelectorAll('.ticket-row');
        const countEl     = document.getElementById('ticketCount');

        searchInput.addEventListener('input', () => {
            const search = searchInput.value.toLowerCase();
            let visible  = 0;
            rows.forEach(row => {
                const show = row.dataset.search.includes(search);
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            countEl.textContent = visible + ' of {{ $tickets->count() }} tickets';
        });
    </script>

</x-app-layout>