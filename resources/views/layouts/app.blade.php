<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TTI — {{ $title ?? 'Retail Management System' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f0f4f8] h-screen overflow-hidden">

<div class="flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR ===== --}}
    <aside id="sidebar" class="w-64 transition-all duration-300 bg-[#003087] flex flex-col shrink-0 z-30 hidden md:flex">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-4 py-5 border-b border-white/10">
            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center shrink-0">
                <span class="text-[#003087] font-bold text-sm">TTI</span>
            </div>
            <div class="sidebar-text overflow-hidden">
                <p class="text-white font-semibold text-sm leading-tight whitespace-nowrap">Thinking Tools</p>
                <p class="text-[#EDF7F6] text-xs leading-tight whitespace-nowrap">Incorporated</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 py-6 px-2 space-y-1">

            {{-- Dashboard --}}
            <a href="{{ auth()->user()->isCustomer() ? route('customer.dashboard') : route('staff.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150 group
                   {{ request()->routeIs('customer.dashboard') || request()->routeIs('staff.dashboard')
                       ? 'bg-white/15 text-white'
                       : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="sidebar-text text-sm">Dashboard</span>
                @if(request()->routeIs('*.dashboard'))
                    <span class="sidebar-text ml-auto w-1.5 h-1.5 rounded-full bg-[#CC2229]"></span>
                @endif
            </a>

            {{-- Inventory — staff only --}}
            @if(auth()->user()->isStaff())
                <a href="{{ route('inventory.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150 group
                       {{ request()->routeIs('inventory.*')
                           ? 'bg-white/15 text-white'
                           : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="sidebar-text text-sm">Inventory</span>
                    @if(request()->routeIs('inventory.*'))
                        <span class="sidebar-text ml-auto w-1.5 h-1.5 rounded-full bg-[#CC2229]"></span>
                    @endif
                </a>
            @endif

            {{-- Orders — both roles --}}
            <a href="{{ route('orders.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150 group
                   {{ request()->routeIs('orders.*')
                       ? 'bg-white/15 text-white'
                       : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="sidebar-text text-sm">Orders</span>
                @if(request()->routeIs('orders.*'))
                    <span class="sidebar-text ml-auto w-1.5 h-1.5 rounded-full bg-[#CC2229]"></span>
                @endif
            </a>

            {{-- Tickets — both roles --}}
            <a href="{{ route('tickets.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150 group
                   {{ request()->routeIs('tickets.*')
                       ? 'bg-white/15 text-white'
                       : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <span class="sidebar-text text-sm">Tickets</span>
                @if(request()->routeIs('tickets.*'))
                    <span class="sidebar-text ml-auto w-1.5 h-1.5 rounded-full bg-[#CC2229]"></span>
                @endif
            </a>

            {{-- Audit Log — staff only --}}
            @if(auth()->user()->isStaff())
                <a href="{{ route('audit.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150 group
                       {{ request()->routeIs('audit.*')
                           ? 'bg-white/15 text-white'
                           : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="sidebar-text text-sm">Audit Log</span>
                    @if(request()->routeIs('audit.*'))
                        <span class="sidebar-text ml-auto w-1.5 h-1.5 rounded-full bg-[#CC2229]"></span>
                    @endif
                </a>
            @endif

            {{-- My Activity — customers only --}}
            @if(auth()->user()->isCustomer())
                <a href="{{ route('audit.user-history') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150 group
                       {{ request()->routeIs('audit.user-history')
                           ? 'bg-white/15 text-white'
                           : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="sidebar-text text-sm">My Activity</span>
                    @if(request()->routeIs('audit.user-history'))
                        <span class="sidebar-text ml-auto w-1.5 h-1.5 rounded-full bg-[#CC2229]"></span>
                    @endif
                </a>
            @endif

        </nav>

        {{-- User info at bottom --}}
        <div class="p-3 border-t border-white/10">
            <div class="flex items-center gap-3 px-2 py-2 rounded-lg">
                <div class="w-8 h-8 rounded-full bg-[#CC2229] flex items-center justify-center shrink-0">
                    <span class="text-white text-xs font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(strrchr(auth()->user()->name, ' '), 1, 1)) }}
                    </span>
                </div>
                <div class="sidebar-text flex-1 overflow-hidden">
                    <p class="text-white text-sm leading-tight whitespace-nowrap">{{ auth()->user()->name }}</p>
                    <p class="text-white/50 text-xs whitespace-nowrap">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                </div>
            </div>
        </div>

    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Header --}}
        <header class="bg-white border-b border-gray-200 px-4 md:px-6 py-3.5 flex items-center justify-between shrink-0 z-20">
            <div class="flex items-center gap-3">

                {{-- Mobile logo --}}
                <div class="md:hidden w-8 h-8 bg-[#003087] rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xs">TTI</span>
                </div>

                {{-- Sidebar toggle (desktop) --}}
                <button onclick="toggleSidebar()" class="hidden md:flex p-1.5 rounded-md text-gray-500 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div>
                    <h1 class="text-gray-800 text-base font-medium">{{ $title ?? 'Dashboard' }}</h1>
                    <p class="text-gray-400 text-xs hidden sm:block">{{ now()->isoFormat('dddd, MMMM D, YYYY') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">

                {{-- Notification bell --}}
                <button class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#CC2229] rounded-full"></span>
                </button>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-500 hover:text-[#CC2229] hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>

                {{-- Mobile menu toggle --}}
                <button onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

            </div>
        </header>

        {{-- Mobile nav dropdown --}}
        <div id="mobileMenu" class="hidden md:hidden bg-[#003087] px-3 py-2 space-y-1 z-20">
            <a href="{{ auth()->user()->isCustomer() ? route('customer.dashboard') : route('staff.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('*.dashboard') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                Dashboard
            </a>
            @if(auth()->user()->isStaff())
                <a href="{{ route('inventory.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('inventory.*') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/10' }}">Inventory</a>
            @endif
            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('orders.*') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/10' }}">Orders</a>
            <a href="{{ route('tickets.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('tickets.*') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/10' }}">Tickets</a>
            @if(auth()->user()->isStaff())
                <a href="{{ route('audit.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('audit.*') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/10' }}">Audit Log</a>
            @endif
            @if(auth()->user()->isCustomer())
                <a href="{{ route('audit.user-history') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('audit.user-history') ? 'bg-white/15 text-white' : 'text-white/60 hover:text-white hover:bg-white/10' }}">My Activity</a>
            @endif
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mx-6 mt-4 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-700 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-4 md:p-6">
            {{ $slot }}
        </main>

    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        if (sidebar.classList.contains('w-64')) {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-16');
            sidebarTexts.forEach(el => el.classList.add('hidden'));
        } else {
            sidebar.classList.remove('w-16');
            sidebar.classList.add('w-64');
            sidebarTexts.forEach(el => el.classList.remove('hidden'));
        }
    }
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }
</script>

</body>
</html>
