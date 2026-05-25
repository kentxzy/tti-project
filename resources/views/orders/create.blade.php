<x-app-layout>
    <x-slot name="title">Place Order</x-slot>

    <div class="max-w-lg mx-auto space-y-5">

        {{-- Back button --}}
        <a href="{{ route('orders.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#003087] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Orders
        </a>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-gray-800 text-base">Place New Order</h2>
                <p class="text-xs text-gray-400 mt-0.5">Select a product and branch to place your order</p>
            </div>

            <div class="p-5">
                <form action="{{ route('orders.store') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Product --}}
                    <div>
                        <label class="text-sm text-gray-600 block mb-1.5">Product</label>
                        <select name="product_id" id="productSelect"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-gray-50">
                            <option value="">-- Select a product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    data-price="{{ $product->price }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} — ₱{{ number_format($product->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Branch --}}
                    <div>
                        <label class="text-sm text-gray-600 block mb-1.5">Branch</label>
                        <select name="branch_id"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-gray-50">
                            <option value="">-- Select a branch --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }} — {{ $branch->city }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Quantity --}}
                    <div>
                        <label class="text-sm text-gray-600 block mb-1.5">Quantity</label>
                        <input type="number" name="quantity" id="quantityInput" min="1"
                            value="{{ old('quantity', 1) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-gray-50" />
                        @error('quantity')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Order summary preview --}}
                    <div id="orderSummary" class="hidden p-4 bg-gray-50 rounded-lg border border-gray-100 space-y-2">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Order Summary</p>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Unit Price</span>
                            <span class="text-gray-800" id="unitPriceDisplay">₱0.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Quantity</span>
                            <span class="text-gray-800" id="quantityDisplay">1</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2 flex justify-between text-sm font-medium">
                            <span class="text-gray-700">Total</span>
                            <span class="text-[#003087]" id="totalDisplay">₱0.00</span>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-3 justify-end pt-2">
                        <a href="{{ route('orders.index') }}"
                           class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-[#003087] hover:bg-[#002266] text-white rounded-lg transition-colors">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Live order summary calculator --}}
    <script>
        const productSelect  = document.getElementById('productSelect');
        const quantityInput  = document.getElementById('quantityInput');
        const orderSummary   = document.getElementById('orderSummary');
        const unitPriceEl    = document.getElementById('unitPriceDisplay');
        const quantityEl     = document.getElementById('quantityDisplay');
        const totalEl        = document.getElementById('totalDisplay');

        function updateSummary() {
            const selected = productSelect.options[productSelect.selectedIndex];
            const price    = parseFloat(selected?.dataset.price || 0);
            const qty      = parseInt(quantityInput.value || 1);

            if (price > 0) {
                orderSummary.classList.remove('hidden');
                unitPriceEl.textContent  = '₱' + price.toLocaleString('en-PH', { minimumFractionDigits: 2 });
                quantityEl.textContent   = qty;
                totalEl.textContent      = '₱' + (price * qty).toLocaleString('en-PH', { minimumFractionDigits: 2 });
            } else {
                orderSummary.classList.add('hidden');
            }
        }

        productSelect.addEventListener('change', updateSummary);
        quantityInput.addEventListener('input', updateSummary);
    </script>

</x-app-layout>