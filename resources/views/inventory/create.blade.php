<x-app-layout>
    <x-slot name="title">Add New Product</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            
        {{-- Validation errors --}}
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-sm text-red-600">• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-gray-800 font-medium">Add New Product</h3>
                <a href="{{ route('inventory.index') }}"
                   class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>

            <div class="p-6 space-y-4">

                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf

                    {{-- Product Name --}}
                    <div>
                        <label class="text-sm text-gray-600 block mb-1.5">Product Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            placeholder="e.g. Lenovo ThinkPad E14"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-gray-50">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category and Price --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm text-gray-600 block mb-1.5">Category</label>
                            <select name="category"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 bg-gray-50 text-gray-700">
                                <option value="">-- Select Category --</option>
                                @foreach(['CPU', 'GPU', 'Motherboard', 'RAM', 'Storage', 'PSU', 'Case', 'Laptop', 'Desktop', 'Monitor', 'Peripherals', 'Networking'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 block mb-1.5">Price (₱)</label>
                            <input type="number" name="price" value="{{ old('price') }}"
                                placeholder="0" step="0.01"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-gray-50">
                            @error('price')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Branch and Stock --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm text-gray-600 block mb-1.5">Branch</label>
                            <select name="branch_id"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 bg-gray-50 text-gray-700">
                                <option value="">-- Select Branch --</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 block mb-1.5">Stock Quantity</label>
                            <input type="number" name="stock" value="{{ old('stock') }}"
                                placeholder="0"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003087]/20 focus:border-[#003087] bg-gray-50">
                            @error('stock')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="pt-2 flex gap-3 justify-end border-t border-gray-100">
                        <a href="{{ route('inventory.index') }}"
                           class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-[#003087] hover:bg-[#002266] text-white rounded-lg transition-colors">
                            Add Product
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</x-app-layout>