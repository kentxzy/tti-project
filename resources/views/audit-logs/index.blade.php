<x-app-layout>
    <x-slot name="title">Audit Log</x-slot>

    <div class="space-y-5">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-gray-800">Audit Log</h2>
                <p class="text-gray-500 text-sm mt-0.5">All system activity across every user and module</p>
            </div>
        </div>

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('audit.index') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex flex-col lg:flex-row gap-3">

                {{-- Search --}}
                <div class="relative flex-1 min-w-0">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by description or user..."
                           class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087]">
                </div>

                <div class="flex flex-wrap gap-2">

                    {{-- Action filter --}}
                    <select name="action" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-white">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Model type filter --}}
                    <select name="model_type" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-white">
                        <option value="">All Modules</option>
                        @foreach($modelTypes as $type)
                            <option value="{{ $type }}" {{ request('model_type') === $type ? 'selected' : '' }}>
                                {{ class_basename($type) }}
                            </option>
                        @endforeach
                    </select>

                    {{-- User filter --}}
                    <select name="user_id" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-white">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Date from --}}
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-white">

                    {{-- Date to --}}
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-white">

                    <button type="submit"
                            class="px-4 py-2 bg-[#003087] hover:bg-[#002266] text-white text-sm rounded-lg transition-colors">
                        Filter
                    </button>

                    @if(request()->hasAny(['search','action','model_type','user_id','start_date','end_date']))
                        <a href="{{ route('audit.index') }}"
                           class="px-3 py-2 text-sm text-gray-500 hover:text-[#CC2229] hover:bg-red-50 rounded-lg transition-colors border border-gray-200">
                            Clear
                        </a>
                    @endif

                </div>
            </div>
        </form>

        {{-- Action quick-filter chips --}}
        @php
            $actionConfig = [
                'created'    => ['color' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'dot' => 'bg-emerald-500'],
                'updated'    => ['color' => 'text-blue-700',    'bg' => 'bg-blue-50',    'dot' => 'bg-blue-500'],
                'deleted'    => ['color' => 'text-red-700',     'bg' => 'bg-red-50',     'dot' => 'bg-red-500'],
                'logged_in'  => ['color' => 'text-gray-600',   'bg' => 'bg-gray-100',   'dot' => 'bg-gray-400'],
                'logged_out' => ['color' => 'text-gray-500',   'bg' => 'bg-gray-50',    'dot' => 'bg-gray-300'],
                'exported'   => ['color' => 'text-purple-700', 'bg' => 'bg-purple-50',  'dot' => 'bg-purple-500'],
                'restored'   => ['color' => 'text-amber-700',  'bg' => 'bg-amber-50',   'dot' => 'bg-amber-400'],
            ];
        @endphp
        <div class="flex flex-wrap gap-2">
            @foreach($actions as $action)
                @php $cfg = $actionConfig[$action] ?? ['color'=>'text-gray-600','bg'=>'bg-gray-100','dot'=>'bg-gray-400']; @endphp
                <a href="{{ route('audit.index', array_merge(request()->query(), ['action' => request('action') === $action ? null : $action])) }}"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs border transition-all
                       {{ request('action') === $action
                           ? $cfg['bg'].' '.$cfg['color'].' border-current'
                           : 'bg-white text-gray-500 border-gray-200 hover:border-gray-300' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                    {{ ucfirst(str_replace('_', ' ', $action)) }}
                </a>
            @endforeach
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide whitespace-nowrap">Timestamp</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide whitespace-nowrap hidden sm:table-cell">User</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide whitespace-nowrap hidden sm:table-cell">Action</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide whitespace-nowrap hidden md:table-cell">Module</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide">Description</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide whitespace-nowrap hidden xl:table-cell">IP Address</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($auditLogs as $log)
                            @php $cfg = $actionConfig[$log->action] ?? ['color'=>'text-gray-600','bg'=>'bg-gray-100','dot'=>'bg-gray-400']; @endphp
                            <tr class="hover:bg-gray-50 transition-colors">

                                {{-- Timestamp --}}
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <p class="text-gray-800 text-xs">{{ $log->created_at->format('Y-m-d') }}</p>
                                    <p class="text-gray-400 text-xs">{{ $log->created_at->format('H:i:s') }}</p>
                                </td>

                                {{-- User --}}
                                <td class="px-4 py-3 hidden sm:table-cell whitespace-nowrap">
                                    <p class="text-gray-700 text-xs">{{ $log->user?->name ?? 'System' }}</p>
                                    <p class="text-gray-400 text-xs">{{ $log->user ? ucfirst(str_replace('_',' ',$log->user->role)) : '—' }}</p>
                                </td>

                                {{-- Action badge --}}
                                <td class="px-4 py-3 hidden sm:table-cell">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs {{ $cfg['bg'] }} {{ $cfg['color'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                    </span>
                                </td>

                                {{-- Module --}}
                                <td class="px-4 py-3 hidden md:table-cell">
                                    <span class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-md">
                                        {{ $log->model_type ? class_basename($log->model_type) : '—' }}
                                    </span>
                                </td>

                                {{-- Description --}}
                                <td class="px-4 py-3 max-w-xs">
                                    <p class="text-gray-700 text-xs truncate" title="{{ $log->description }}">
                                        {{ $log->description ?? '—' }}
                                    </p>
                                </td>

                                {{-- IP --}}
                                <td class="px-4 py-3 hidden xl:table-cell whitespace-nowrap">
                                    <span class="text-gray-400 text-xs font-mono">{{ $log->ip_address ?? '—' }}</span>
                                </td>

                                {{-- View button --}}
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
                                <td colspan="7" class="text-center py-12 text-gray-400">
                                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    No audit logs found for the selected filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer: count + pagination --}}
            <div class="px-4 py-3 border-t border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                <p class="text-xs text-gray-400">
                    Showing {{ $auditLogs->firstItem() }}–{{ $auditLogs->lastItem() }} of {{ $auditLogs->total() }} entries
                </p>
                <div class="text-xs">
                    {{ $auditLogs->withQueryString()->links() }}
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
