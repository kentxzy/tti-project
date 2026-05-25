<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TTI — Retail Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-[#f0f4f8]">

    {{-- Background pattern --}}
    <div class="absolute inset-0 opacity-5" style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23003087' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>

    <div class="relative w-full max-w-md px-4">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

            {{-- Header stripe --}}
            <div class="h-2 bg-gradient-to-r from-[#003087] to-[#CC2229]"></div>

            <div class="p-8">
                {{-- Logo & Brand --}}
                <div class="flex flex-col items-center mb-8">
                    <div class="flex items-center gap-3 mb-3">
                        {{-- TTI Logo --}}
                        <div class="relative w-14 h-14 flex items-center justify-center">
                            <div class="absolute inset-0 bg-[#003087] rounded-xl"></div>
                            <span class="relative text-white font-bold text-xl tracking-tight">TTI</span>
                        </div>
                        <div>
                            <p class="text-[#003087] font-semibold text-lg leading-tight">Thinking Tools</p>
                            <p class="text-[#CC2229] text-sm leading-tight">Incorporated</p>
                        </div>
                    </div>
                    <div class="h-px w-full bg-gray-100 mt-2 mb-4"></div>
                    <h1 class="text-gray-800 text-xl font-medium">Retail Management System</h1>
                    <p class="text-gray-500 text-sm mt-1">Sign in to your account to continue</p>
                </div>

                {{-- Session errors --}}
                @if ($errors->any())
                    <div class="flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-lg mb-5">
                        <svg class="w-4 h-4 text-[#CC2229] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-red-600">{{ $errors->first() }}</p>
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm text-gray-700 mb-1.5">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="staff@tti.com.ph"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#003087]/30 focus:border-[#003087] transition-all text-gray-800 placeholder-gray-400"
                            required
                            autofocus
                        />
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm text-gray-700">Password</label>
                        </div>
                        <input
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#003087]/30 focus:border-[#003087] transition-all text-gray-800 placeholder-gray-400"
                            required
                        />
                    </div>

                    {{-- Sign In Button --}}
                    <button
                        type="submit"
                        class="w-full py-3 bg-[#003087] hover:bg-[#002266] text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg font-medium"
                    >
                        Sign In
                    </button>
                </form>

                <p class="text-center text-xs text-gray-400 mt-6">
                    For authorized TTI personnel only. Unauthorized access is prohibited.
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-4">
            © {{ date('Y') }} Thinking Tools, Inc. All rights reserved.
        </p>
    </div>

</body>
</html>