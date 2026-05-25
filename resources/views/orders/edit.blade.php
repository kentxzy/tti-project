<x-app-layout>
    <x-slot name="title">Update Order Status</x-slot>

    <div class="max-w-lg mx-auto space-y-5">

        {{-- Back button --}}
        <a href="{{ route('orders.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#003087] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Orders
        </a>

        {{-- Order summary card --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-gray-800 text-base">Update Order Status</h2>
                <p class="text-xs text-gray-400 mt-0.5">ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>

            {{-- Order details --}}
            <div class="p-5 space-y-4">
                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Customer</span>
                        <span class="text-gray-800">{{ $order->user->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Branch</span>
                        <span class="text-gray-800">{{ $order->branch->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Price</span>
                        <span class="text-gray-800 font-medium">₱{{ number_format($order->price, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Date Placed</span>
                        <span class="text-gray-800">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                {{-- Order items --}}
                @if($order->orderItems->count())
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Items Ordered</p>
                        <div class="space-y-2">
                            @foreach($order->orderItems as $item)
                                <div class="flex items-center justify-between text-sm bg-gray-50 px-3 py-2 rounded-lg">
                                    <span class="text-gray-700">{{ $item->product->name ?? 'N/A' }}</span>
                                    <span class="text-gray-500">x{{ $item->quantity }} · ₱{{ number_format($item->unit_price, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Status update form --}}
                <form action="{{ route('orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <p class="text-sm text-gray-600 mb-3">Select new status:</p>

                    @php
                        $statusFlow = [
                            'pending'    => ['dot' => 'bg-yellow-400', 'text' => 'text-yellow-700', 'bg' => 'bg-yellow-50',  'label' => 'Pending'],
                            'verified'   => ['dot' => 'bg-blue-500',   'text' => 'text-blue-700',   'bg' => 'bg-blue-50',    'label' => 'Verified'],
                            'dispatched' => ['dot' => 'bg-purple-500', 'text' => 'text-purple-700', 'bg' => 'bg-purple-50',  'label' => 'Dispatched'],
                            'delivered'  => ['dot' => 'bg-emerald-500','text' => 'text-emerald-700','bg' => 'bg-emerald-50', 'label' => 'Delivered'],
                        ];
                    @endphp

                    <div class="space-y-2">
                        @foreach($statusFlow as $key => $cfg)
                            <label class="flex items-center gap-3 px-4 py-3 rounded-lg border cursor-pointer transition-all
                                {{ $order->status === $key ? $cfg['bg'] . ' border-current' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="status" value="{{ $key }}"
                                    {{ $order->status === $key ? 'checked' : '' }}
                                    class="sr-only peer" />
                                <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0
                                    {{ $order->status === $key ? 'border-current' : 'border-gray-300' }}">
                                    <span class="w-2 h-2 rounded-full {{ $cfg['dot'] }}
                                        {{ $order->status === $key ? 'block' : 'hidden' }} status-dot"></span>
                                </span>
                                <span class="text-sm {{ $order->status === $key ? $cfg['text'] : 'text-gray-700' }}">
                                    {{ $cfg['label'] }}
                                </span>
                                @if($order->status === $key)
                                    <span class="ml-auto text-xs text-gray-400">Current</span>
                                @endif
                            </label>
                        @endforeach
                    </div>

                    @error('status')
                        <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                    @enderror

                    <div class="flex gap-3 justify-end mt-5">
                        <a href="{{ route('orders.index') }}"
                           class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-[#003087] hover:bg-[#002266] text-white rounded-lg transition-colors">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- Highlight selected radio visually --}}
    <script>
        document.querySelectorAll('input[name="status"]').forEach(radio => {
            radio.addEventListener('change', () => {
                document.querySelectorAll('label').forEach(label => {
                    label.classList.remove('bg-yellow-50','bg-blue-50','bg-purple-50','bg-emerald-50','border-current');
                    label.classList.add('border-gray-200');
                    label.querySelector('.status-dot')?.classList.add('hidden');
                });
                const selected = radio.closest('label');
                selected.classList.remove('border-gray-200');
                selected.classList.add('border-current');
                selected.querySelector('.status-dot')?.classList.remove('hidden');
            });
        });
    </script>

</x-app-layout>