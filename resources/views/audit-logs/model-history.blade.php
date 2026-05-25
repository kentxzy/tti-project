<x-app-layout>
    <x-slot name="title">{{ class_basename($modelType) }} History</x-slot>

    <div class="space-y-5">

        {{-- Back --}}
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-[#003087] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back
        </a>

        {{-- Header --}}
        <div>
            <h2 class="text-gray-800">{{ class_basename($modelType) }} #{{ $modelId }} — Full History</h2>
            <p class="text-gray-500 text-sm mt-0.5">Every recorded action on this record</p>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide whitespace-nowrap">Timestamp</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide whitespace-nowrap hidden sm:table-cell">User</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide whitespace-nowrap">Action</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide">Description</th>
                            <th class="text-left px-4 py-3 text-xs text-gray-500 uppercase tracking-wide whitespace-nowrap hidden xl:table-cell">IP</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @php
                            $actionConfig = [
                                'created'    => ['color' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'dot' => 'bg-emerald-500'],
                                'updated'    => ['color' => 'text-blue-700',    'bg' => 'bg-blue-50',    'dot' => 'bg-blue-500'],
                                'deleted'    => ['color' => 'text-red-700',     'bg' => 'bg-red-50',     'dot' => 'bg-red-500'],
                                'restored'   => ['color' => 'text-amber-700',  'bg' => 'bg-amber-50',   'dot' => 'bg-amber-400'],
                            ];
                        @endphp
                        @forelse($auditLogs as $log)
                            @php $cfg = $actionConfig[$log->action] ?? ['color'=>'text-gray-600','bg'=>'bg-gray-100','dot'=>'bg-gray-400']; @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <p class="text-gray-800 text-xs">{{ $log->created_at->format('Y-m-d') }}</p>
                                    <p class="text-gray-400 text-xs">{{ $log->created_at->format('H:i:s') }}</p>
                                </td>
                                <td class="px-4 py-3 hidden sm:table-cell">
                                    <p class="text-gray-700 text-xs">{{ $log->user?->name ?? 'System' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs {{ $cfg['bg'] }} {{ $cfg['color'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                        {{ ucfirst(str_replace('_',' ',$log->action)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 max-w-xs">
                                    <p class="text-gray-700 text-xs truncate" title="{{ $log->description }}">
                                        {{ $log->description ?? '—' }}
                                    </p>
                                </td>
                                <td class="px-4 py-3 hidden xl:table-cell">
                                    <span class="text-gray-400 text-xs font-mono">{{ $log->ip_address ?? '—' }}</span>
                                </td>
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
                                <td colspan="6" class="text-center py-12 text-gray-400">
                                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    No history found for this record.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                <p class="text-xs text-gray-400">
                    {{ $auditLogs->total() }} total {{ Str::plural('entry', $auditLogs->total()) }}
                </p>
                <div class="text-xs">
                    {{ $auditLogs->links() }}
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
