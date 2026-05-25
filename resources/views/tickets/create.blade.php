<x-app-layout>
    <x-slot name="title">Submit Warranty Ticket</x-slot>

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
                <h2 class="text-gray-800 text-base">Submit Warranty / Repair Ticket</h2>
                <p class="text-xs text-gray-400 mt-0.5">Fill in the details below</p>
            </div>

            <div class="p-5">
                <form action="{{ route('tickets.store') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Order Item selector --}}
                    <div>
                        <label class="text-sm text-gray-600 block mb-1.5">Select Ordered Item</label>
                        <select name="order_item_id"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-gray-50">
                            <option value="">-- Select a product you ordered --</option>
                            @foreach($orderItems as $item)
                                <option value="{{ $item->id }}" {{ old('order_item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->product->name ?? 'N/A' }} —
                                    ORD-{{ str_pad($item->order->id, 4, '0', STR_PAD_LEFT) }} —
                                    {{ $item->order->created_at->format('M d, Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('order_item_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Issue Description --}}
                    <div>
                        <label class="text-sm text-gray-600 block mb-1.5">Describe the Issue</label>
                        <textarea name="issue_description" rows="4"
                            placeholder="Describe the problem in detail..."
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-gray-50 resize-none">{{ old('issue_description') }}</textarea>
                        @error('issue_description')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Contact Number --}}
                    <div>
                        <label class="text-sm text-gray-600 block mb-1.5">Contact Number</label>
                        <input type="tel" name="contact_number"
                            value="{{ old('contact_number') }}"
                            placeholder="09XXXXXXXXX"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-gray-50" />
                        @error('contact_number')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info note --}}
                    <div class="flex items-start gap-2 p-3 bg-blue-50 rounded-lg border border-blue-100">
                        <svg class="w-4 h-4 text-[#003087] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-blue-700">
                            Please bring your unit and proof of purchase to any TTI branch. Our technicians will diagnose your device within 24 hours.
                        </p>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-3 justify-end pt-2">
                        <a href="{{ route('tickets.index') }}"
                           class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-[#003087] hover:bg-[#002266] text-white rounded-lg transition-colors">
                            Submit Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>