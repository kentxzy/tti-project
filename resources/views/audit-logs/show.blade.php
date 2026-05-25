<x-app-layout>
    <x-slot name="title">Audit Log Detail</x-slot>

    <div class="max-w-2xl mx-auto space-y-4">

        {{-- Back link --}}
        <a href="{{ route('audit.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-[#003087] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Audit Log
        </a>

        {{-- Detail card --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

            {{-- Card header --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                <div class="w-9 h-9 rounded-lg bg-[#003087]/10 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#003087]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-gray-800 text-sm font-medium">Audit Log Details</h3>
                    <p class="text-gray-400 text-xs">Entry #{{ $auditLog->id }}</p>
                </div>
            </div>

            {{-- Meta grid --}}
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Timestamp</p>
                        <p class="text-sm text-gray-800">{{ $auditLog->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">IP Address</p>
                        <p class="text-sm text-gray-800 font-mono">{{ $auditLog->ip_address ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">User</p>
                        <p class="text-sm text-gray-800">{{ $auditLog->user?->name ?? 'System' }}</p>
                        @if($auditLog->user)
                            <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_',' ',$auditLog->user->role)) }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Module</p>
                        <p class="text-sm text-gray-800">{{ $auditLog->model_type ? class_basename($auditLog->model_type) : '—' }}</p>
                        @if($auditLog->model_id)
                            <p class="text-xs text-gray-400">ID: {{ $auditLog->model_id }}</p>
                        @endif
                    </div>
                </div>

                {{-- Action badge --}}
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
                    $cfg = $actionConfig[$auditLog->action] ?? ['color'=>'text-gray-600','bg'=>'bg-gray-100','dot'=>'bg-gray-400'];
                @endphp
                <div>
                    <p class="text-xs text-gray-400 mb-1">Action</p>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs {{ $cfg['bg'] }} {{ $cfg['color'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                        {{ ucfirst(str_replace('_', ' ', $auditLog->action)) }}
                    </span>
                </div>

                {{-- Description --}}
                <div>
                    <p class="text-xs text-gray-400 mb-1">Description</p>
                    <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3 leading-relaxed">
                        {{ $auditLog->description ?? '—' }}
                    </p>
                </div>

                {{-- User agent --}}
                @if($auditLog->user_agent)
                    <div>
                        <p class="text-xs text-gray-400 mb-1">User Agent</p>
                        <p class="text-xs text-gray-500 bg-gray-50 rounded-lg p-3 font-mono break-all leading-relaxed">
                            {{ $auditLog->user_agent }}
                        </p>
                    </div>
                @endif

                {{-- Changed fields diff --}}
                @if($auditLog->old_values || $auditLog->new_values)
                    @php $changes = $auditLog->getChangedFields(); @endphp
                    @if(!empty($changes))
                        <div>
                            <p class="text-xs text-gray-400 mb-2">Changed Fields</p>
                            <div class="rounded-lg border border-gray-200 overflow-hidden divide-y divide-gray-100">
                                @foreach($changes as $field => $change)
                                    <div class="grid grid-cols-3 text-xs px-3 py-2.5 bg-white">
                                        <span class="text-gray-500 font-medium">{{ ucfirst(str_replace('_',' ',$field)) }}</span>
                                        <span class="text-red-600 line-through truncate pr-2">{{ $change['old'] ?? '—' }}</span>
                                        <span class="text-emerald-600 truncate">{{ $change['new'] ?? '—' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif

            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                @if($auditLog->model_type && $auditLog->model_id)
                    <a href="{{ route('audit.model-history', ['modelType' => $auditLog->model_type, 'modelId' => $auditLog->model_id]) }}"
                       class="text-sm text-[#003087] hover:underline">
                        View full history for this {{ class_basename($auditLog->model_type) }}
                    </a>
                @else
                    <span></span>
                @endif
                <a href="{{ route('audit.index') }}"
                   class="px-4 py-2 bg-[#003087] hover:bg-[#002266] text-white text-sm rounded-lg transition-colors">
                    Back to List
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
