<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Portal') — Grand Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="flex h-screen overflow-hidden" style="background:#f5f0e8" x-data="{ sidebarOpen: false }">

{{-- ========== MOBILE OVERLAY ========== --}}
<div
    x-show="sidebarOpen"
    x-transition:enter="transition-opacity duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="sidebarOpen = false"
    class="fixed inset-0 z-30 bg-black/60 lg:hidden"
    style="display:none"
></div>

{{-- ========== SIDEBAR ========== --}}
<aside
    class="fixed inset-y-0 left-0 z-40 w-60 flex flex-col text-white flex-shrink-0 transition-transform duration-300 ease-in-out
           lg:relative lg:translate-x-0 lg:z-auto lg:w-56"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    style="background:#1c1c1c"
>
    {{-- Brand --}}
    <div class="px-5 py-5 border-b border-gray-700 flex items-center justify-between">
        <div>
            <div class="w-9 h-9 rounded-full border border-[#b8972e] flex items-center justify-center text-[#b8972e] font-semibold text-sm mb-2">H</div>
            <p class="text-white text-sm font-semibold leading-none">Grand Hotel</p>
            <p class="text-[#b8972e] text-[9px] uppercase tracking-widest mt-1">Staff Portal</p>
        </div>
        {{-- Close button (mobile only) --}}
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white p-1 rounded-lg hover:bg-gray-800 transition self-start mt-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <nav class="flex-1 px-2.5 py-3 space-y-0.5 overflow-y-auto">

        {{-- Dashboard --}}
        <p class="text-[9px] uppercase tracking-widest text-gray-500 px-2.5 pt-1 pb-1">Main</p>
        <a href="{{ route('staff.dashboard') }}"
            @click="sidebarOpen = false"
            class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-medium transition
            {{ request()->routeIs('staff.dashboard') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}"
            style="{{ request()->routeIs('staff.dashboard') ? 'background:#b8972e' : '' }}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- Hotel Group --}}
        <p class="text-[9px] uppercase tracking-widest text-gray-500 px-2.5 pt-3 pb-1">Hotel</p>

        <div x-data="{ open: {{ request()->routeIs('staff.reservations*') || request()->routeIs('staff.walkins*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="flex items-center justify-between w-full px-2.5 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white hover:bg-gray-800 transition">
                <span class="flex items-center gap-2.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Reservations
                </span>
                <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="open" class="pl-7 mt-0.5 space-y-0.5">
                <a href="{{ route('staff.reservations.index') }}" @click="sidebarOpen = false"
                    class="block px-2.5 py-1.5 rounded-lg text-xs transition
                    {{ request()->routeIs('staff.reservations*') ? 'text-white bg-gray-800' : 'text-gray-500 hover:text-white hover:bg-gray-800' }}">
                    All Reservations
                </a>
                <a href="{{ route('staff.walkins.index') }}" @click="sidebarOpen = false"
                    class="block px-2.5 py-1.5 rounded-lg text-xs transition
                    {{ request()->routeIs('staff.walkins*') ? 'text-white bg-gray-800' : 'text-gray-500 hover:text-white hover:bg-gray-800' }}">
                    Walk-Ins
                </a>
            </div>
        </div>

        {{-- Finance Group --}}
        <p class="text-[9px] uppercase tracking-widest text-gray-500 px-2.5 pt-3 pb-1">Finance</p>

        <div x-data="{ open: {{ request()->routeIs('staff.payments*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="flex items-center justify-between w-full px-2.5 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white hover:bg-gray-800 transition">
                <span class="flex items-center gap-2.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Payments
                </span>
                <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="open" class="pl-7 mt-0.5 space-y-0.5">
                <a href="{{ route('staff.payments.index') }}" @click="sidebarOpen = false"
                    class="block px-2.5 py-1.5 rounded-lg text-xs transition
                    {{ request()->routeIs('staff.payments*') ? 'text-white bg-gray-800' : 'text-gray-500 hover:text-white hover:bg-gray-800' }}">
                    All Payments
                </a>
            </div>
        </div>


    </nav>

    {{-- Sign Out --}}
    <div class="px-2.5 py-3 border-t border-gray-700">
        <form method="POST" action="{{ route('staff.logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-2.5 text-xs text-gray-400 hover:text-white transition w-full px-2.5 py-2 rounded-lg hover:bg-gray-800">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>

{{-- ========== MAIN CONTENT ========== --}}
<main class="flex-1 overflow-y-auto min-w-0">
    {{-- Top bar --}}
    <div class="bg-white border-b border-gray-200 px-4 sm:px-8 py-3 sm:py-4 flex justify-between items-center sticky top-0 z-20">
        <div class="flex items-center gap-3">
            {{-- Hamburger (mobile only) --}}
            <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-800 p-1.5 rounded-lg hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="text-base sm:text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="flex items-center gap-3 sm:gap-6">
            <div class="hidden sm:block text-sm text-gray-500 font-medium">{{ now()->format('F j, Y') }}</div>

            <div class="flex items-center gap-2 sm:gap-3 bg-[#1c1c1c] rounded-full pr-3 sm:pr-5 pl-1.5 py-1.5 shadow-sm">
                <div class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-full border-[1.5px] border-[#b8972e] bg-[#2a2a2a] text-[#b8972e] font-semibold text-[12px] sm:text-[13px] flex-shrink-0">
                    {{ strtoupper(substr(session('staff_name') ?? 'S', 0, 1)) }}
                </div>
                <div class="flex flex-col">
                    <span class="text-white text-[11px] sm:text-[13px] font-medium leading-none mb-0.5 sm:mb-1 truncate max-w-[80px] sm:max-w-none">{{ session('staff_name') }}</span>
                    <span class="text-[#b8972e] text-[9px] sm:text-[10px] uppercase tracking-widest leading-none">{{ session('staff_role') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="p-4 sm:p-8">
        @yield('content')
    </div>
</main>

</body>
</html>