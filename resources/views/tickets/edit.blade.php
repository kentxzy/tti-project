<x-app-layout>
    <x-slot name="title">Update Ticket Status</x-slot>

    <div class="max-w-lg mx-auto space-y-5">

        {{-- Back button --}}
        <a href="{{ route('tickets.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#003087] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Tickets
        </a>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-gray-800 text-base">Update Ticket Status</h2>
                <p class="text-xs text-gray-400 mt-0.5">TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>

            <div class="p-5 space-y-4">

                {{-- Ticket details --}}
                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Customer</span>
                        <span class="text-gray-800">{{ $ticket->user->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Product</span>
                        <span class="text-gray-800">{{ $ticket->orderItem->product->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Contact</span>
                        <span class="text-gray-800">{{ $ticket->contact_number }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Submitted</span>
                        <span class="text-gray-800">{{ $ticket->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                {{-- Issue description --}}
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-3">
                    <p class="text-xs text-gray-500 mb-1">Issue Description</p>
                    <p class="text-sm text-gray-700">{{ $ticket->issue_description }}</p>
                </div>

                {{-- Status update form --}}
                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @php
                        $statusFlow = [
                            'received'          => ['dot' => 'bg-gray-400',   'text' => 'text-gray-600',   'bg' => 'bg-gray-100',   'label' => 'Received'],
                            'diagnosing'        => ['dot' => 'bg-blue-500',   'text' => 'text-blue-700',   'bg' => 'bg-blue-50',    'label' => 'Diagnosing'],
                            'repaired'          => ['dot' => 'bg-emerald-500','text' => 'text-emerald-700','bg' => 'bg-emerald-50', 'label' => 'Repaired'],
                            'released'          => ['dot' => 'bg-purple-500', 'text' => 'text-purple-700', 'bg' => 'bg-purple-50',  'label' => 'Released'],
                        ];
                    @endphp

                    <p class="text-sm text-gray-600 mb-3">Select new status:</p>

                    {{-- Progress line container --}}
                    <div class="relative">
                        <div class="absolute left-[1.35rem] top-4 bottom-4 w-px bg-gray-200 z-0"></div>

                        <div class="space-y-1 relative z-10">
                            @foreach($statusFlow as $key => $cfg)
                                <label class="flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer transition-all
                                    {{ $ticket->status === $key ? $cfg['bg'] : 'hover:bg-gray-50' }}">
                                    <input type="radio" name="status" value="{{ $key }}"
                                        {{ $ticket->status === $key ? 'checked' : '' }}
                                        class="sr-only" />

                                    {{-- Circle indicator --}}
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 border-2 transition-all z-10
                                        {{ $ticket->status === $key
                                            ? $cfg['dot'] . ' border-transparent'
                                            : 'bg-white border-gray-200' }}
                                        status-circle" data-key="{{ $key }}">
                                        @if($ticket->status === $key)
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @else
                                            <span class="text-xs text-gray-400">{{ $loop->iteration }}</span>
                                        @endif
                                    </div>

                                    <span class="text-sm {{ $ticket->status === $key ? $cfg['text'] : 'text-gray-700' }}">
                                        {{ $cfg['label'] }}
                                    </span>

                                    @if($ticket->status === $key)
                                        <span class="ml-auto text-xs text-gray-400 bg-white px-1.5 py-0.5 rounded">Current</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>

                    @error('status')
                        <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                    @enderror

                    <div class="flex gap-3 justify-end mt-5">
                        <a href="{{ route('tickets.index') }}"
                           class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-[#003087] hover:bg-[#002266] text-white rounded-lg transition-colors">
                            Confirm Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // @ts-nocheck
        @php
            $statusBgs = [
                'received'          => 'bg-gray-100',
                'diagnosing'        => 'bg-blue-50',
                'repaired'          => 'bg-emerald-50',
                'released'          => 'bg-purple-50',
            ];
            $statusDots = [
                'received'          => 'bg-gray-400',
                'diagnosing'        => 'bg-blue-500',
                'repaired'          => 'bg-emerald-500',
                'released'          => 'bg-purple-500',
            ];
            $statusTexts = [
                'received'          => 'text-gray-600',
                'diagnosing'        => 'text-blue-700',
                'repaired'          => 'text-emerald-700',
                'released'          => 'text-purple-700',
            ];
        @endphp

        const statusBgs   = @json($statusBgs);
        const statusDots  = @json($statusDots);
        const statusTexts = @json($statusTexts);

        document.querySelectorAll('input[name="status"]').forEach(radio => {
            radio.addEventListener('change', () => {
                const selected = radio.value;

                document.querySelectorAll('label').forEach(label => {
                    const input = label.querySelector('input[name="status"]');
                    const key   = input?.value;
                    const circle = label.querySelector('.status-circle');

                    // Reset label bg
                    Object.values(statusBgs).forEach(c => label.classList.remove(c));
                    label.classList.add('hover:bg-gray-50');

                    // Reset circle
                    if (circle) {
                        Object.values(statusDots).forEach(c => circle.classList.remove(c));
                        circle.classList.remove('border-transparent');
                        circle.classList.add('bg-white', 'border-gray-200');
                        circle.innerHTML = `<span class="text-xs text-gray-400">${Array.from(document.querySelectorAll('input[name="status"]')).indexOf(input) + 1}</span>`;
                    }

                    // Reset text
                    const span = label.querySelector('span:not(.ml-auto)');
                    if (span) {
                        Object.values(statusTexts).forEach(c => span.classList.remove(c));
                        span.classList.add('text-gray-700');
                    }
                });

                // Apply selected styles
                const selectedLabel  = radio.closest('label');
                const selectedCircle = selectedLabel.querySelector('.status-circle');

                selectedLabel.classList.remove('hover:bg-gray-50');
                selectedLabel.classList.add(statusBgs[selected]);

                if (selectedCircle) {
                    selectedCircle.classList.remove('bg-white', 'border-gray-200');
                    selectedCircle.classList.add(statusDots[selected], 'border-transparent');
                    selectedCircle.innerHTML = `<svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>`;
                }

                const selectedSpan = selectedLabel.querySelector('span:not(.ml-auto)');
                if (selectedSpan) {
                    Object.values(statusTexts).forEach(c => selectedSpan.classList.remove(c));
                    selectedSpan.classList.remove('text-gray-700');
                    selectedSpan.classList.add(statusTexts[selected]);
                }
            });
        });
    </script>

</x-app-layout>