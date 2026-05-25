<x-app-layout>
    <x-slot name="title">My Activity</x-slot>

    <div class="space-y-5">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-gray-800">My Activity History</h2>
                <p class="text-gray-500 text-sm mt-0.5">Your orders, repair tickets, and account actions</p>
            </div>
        </div>

        {{-- Identity card --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 bg-[#CC2229]/10">
                <span class="font-semibold text-sm text-[#CC2229]">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(strrchr(auth()->user()->name, ' '), 1, 1)) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-gray-800 text-sm">{{ auth()->user()->name }}</p>
                <p class="text-gray-400 text-xs">{{ auth()->user()->email }}</p>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs bg-[#CC2229]/10 text-[#CC2229] shrink-0">Customer</span>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('audit.user-history') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex flex-col lg:flex-row gap-3">
                <div class="relative flex-1 min-w-0">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by description..."
                           class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087]">
                </div>
                <div class="flex flex-wrap gap-2">
                    <select name="model_type" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-white">
                        <option value="">All Types</option>
                        @foreach($modelTypes as $type)
                            <option value="{{ $type }}" {{ request('model_type') === $type ? 'selected' : '' }}>
                                {{ class_basename($type) }}
                            </option>
                        @endforeach
                    </select>
                    <select name="action" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-white">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-white">
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-white">
                    <button type="submit" class="px-4 py-2 bg-[#003087] hover:bg-[#002266] text-white text-sm rounded-lg transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['search','model_type','action','start_date','end_date']))
                        <a href="{{ route('audit.user-history') }}"
                           class="px-3 py-2 text-sm text-gray-500 hover:text-[#CC2229] hover:bg-red-50 rounded-lg transition-colors border border-gray-200">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Module chips --}}
        @php
            $moduleConfig = [
                'App\Models\Order'     => ['color' => 'text-blue-700',   'bg' => 'bg-blue-50'],
                'App\Models\Ticket'    => ['color' => 'text-orange-700', 'bg' => 'bg-orange-50'],
                'App\Models\OrderItem' => ['color' => 'text-indigo-700', 'bg' => 'bg-indigo-50'],
            ];
        @endphp
        <div class="flex flex-wrap gap-2">
            {{-- All chip --}}
            <a href="{{ route('audit.user-history', array_merge(request()->query(), ['model_type' => null])) }}"
               class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs border transition-all
                   {{ !request('model_type')
                       ? 'bg-gray-800 text-white border-gray-800'
                       : 'bg-white text-gray-500 border-gray-200 hover:border-gray-300' }}">
                All
            </a>
            @foreach($modelTypes as $type)
                @php $cfg = $moduleConfig[$type] ?? ['color'=>'text-gray-600','bg'=>'bg-gray-100']; @endphp
                <a href="{{ route('audit.user-history', array_merge(request()->query(), ['model_type' => request('model_type') === $type ? null : $type])) }}"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs border transition-all
                       {{ request('model_type') === $type
                           ? $cfg['bg'].' '.$cfg['color'].' border-current'
                           : 'bg-white text-gray-500 border-gray-200 hover:border-gray-300' }}">
                    {{ class_basename($type) }}
                </a>
            @endforeach
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide whitespace-nowrap">Date</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide whitespace-nowrap hidden sm:table-cell">Type</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide whitespace-nowrap hidden sm:table-cell">Action</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide">Description</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($auditLogs as $log)
                            @php
                                $typeCfg = $moduleConfig[$log->model_type] ?? ['color'=>'text-gray-600','bg'=>'bg-gray-100'];
                                $actionColors = [
                                    'created'    => 'text-emerald-700 bg-emerald-50',
                                    'updated'    => 'text-blue-700 bg-blue-50',
                                    'deleted'    => 'text-red-700 bg-red-50',
                                    'logged_in'  => 'text-gray-600 bg-gray-100',
                                    'logged_out' => 'text-gray-500 bg-gray-50',
                                ];
                                $actionClass = $actionColors[$log->action] ?? 'text-gray-600 bg-gray-100';
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">

                                {{-- Date --}}
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <p class="text-gray-800 text-xs">{{ $log->created_at->format('Y-m-d') }}</p>
                                    <p class="text-gray-400 text-xs">{{ $log->created_at->format('H:i:s') }}</p>
                                </td>

                                {{-- Type (model) --}}
                                <td class="px-4 py-3 hidden sm:table-cell">
                                    @if($log->model_type)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $typeCfg['bg'] }} {{ $typeCfg['color'] }}">
                                            {{ class_basename($log->model_type) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>

                                {{-- Action --}}
                                <td class="px-4 py-3 hidden sm:table-cell">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs {{ $actionClass }}">
                                        {{ ucfirst(str_replace('_',' ',$log->action)) }}
                                    </span>
                                </td>

                                {{-- Description --}}
                                <td class="px-4 py-3 max-w-xs">
                                    <p class="text-gray-700 text-xs truncate" title="{{ $log->description }}">
                                        {{ $log->description ?? '—' }}
                                    </p>
                                </td>

                                {{-- View --}}
                                <td class="px-4 py-3">
                                    <a href="{{ route('audit.show', $log) }}"
                                       class="p-1.5 text-gray-400 hover:text-[#003087] hover:bg-blue-50 rounded-md transition-colors inline-flex">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-gray-400">
                                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    No activity found for the selected filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                <p class="text-xs text-gray-400">
                    Showing {{ $auditLogs->firstItem() }}–{{ $auditLogs->lastItem() }} of {{ $auditLogs->total() }} records
                </p>
                <div class="text-xs">
                    {{ $auditLogs->withQueryString()->links() }}
                </div>
            </div>
        </div>

    </div>
</x-app-layout>