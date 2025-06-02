<!-- Sidebar -->
<div class="fixed inset-y-0 start-0 w-64 bg-gray-100 dark:bg-themeBlue-900 text-gray-700 dark:text-blue-100 p-4 space-y-6 overflow-y-auto transform md:translate-x-0 transition-transform duration-200 ease-in-out z-30 border-e border-gray-200 dark:border-themeBlue-800"
     :class="{'translate-x-0': open, '-translate-x-full': !open}"
     x-data="{ open: true }"
     x-on:toggle-sidebar.window="open = !open">

    <!-- Logo -->
    <div class="shrink-0 flex items-center justify-center mb-6">
        <a href="{{ route('dashboard') }}">
            {{-- Ensure logo component handles dark/light text or use specific classes --}}
            <x-application-logo class="block h-12 w-auto fill-current text-gray-800 dark:text-blue-100" />
        </a>
    </div>

    <nav class="space-y-2">
        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
            class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
            {{ __('Dashboard') }}
        </x-responsive-nav-link>

        @if(Auth::check())
            @if(Auth::user()->isAdmin())
                <h3 class="mt-4 mb-2 px-3 text-xs font-semibold text-gray-400 dark:text-blue-300 uppercase tracking-wider">
                    {{ __('app.admin') }}
                </h3>
                <x-responsive-nav-link :href="route('admin.hospital-forces.index')" :active="request()->routeIs('admin.hospital-forces.*')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                    {{ __('app.hospital_forces') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.departments.index')" :active="request()->routeIs('admin.departments.*')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                    {{ __('app.departments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.personnel.index')" :active="request()->routeIs('admin.personnel.*')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                    {{ __('app.all_personnel') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.violation-types.index')" :active="request()->routeIs('admin.violation-types.*')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                    {{ __('app.violation_types') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.leave-types.index')" :active="request()->routeIs('admin.leave-types.*')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                    {{ __('app.leave_types') }}
                </x-responsive-nav-link>
                 <x-responsive-nav-link :href="route('admin.personnel-leaves.index')" :active="request()->routeIs('admin.personnel-leaves.*')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                    {{ __('app.personnel_leaves') }}
                </x-responsive-nav-link>
                {{-- Admin Reports --}}
                <x-responsive-nav-link :href="route('admin.reports.dailyEligibleForLeave')" :active="request()->routeIs('admin.reports.dailyEligibleForLeave')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                    {{ __('app.daily_eligible_for_leave_report') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reports.periodLeaveReport')" :active="request()->routeIs('admin.reports.periodLeaveReport')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                     {{ __('app.period_leave_report') }}
                </x-responsive-nav-link>
                 <x-responsive-nav-link :href="route('admin.reports.periodViolationReport')" :active="request()->routeIs('admin.reports.periodViolationReport')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                     {{ __('app.period_violation_report') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->isMilitaryAffairsOfficer())
                 <h3 class="mt-4 mb-2 px-3 text-xs font-semibold text-gray-400 dark:text-blue-300 uppercase tracking-wider">
                    {{ __('app.military_affairs_officer') }}
                </h3>
                <x-responsive-nav-link :href="route('military_affairs.personnel.index')" :active="request()->routeIs('military_affairs.personnel.*')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                    {{ __('app.personnel') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('military_affairs.personnel-leaves.index')" :active="request()->routeIs('military_affairs.personnel-leaves.*')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                    {{ __('app.personnel_leaves') }}
                </x-responsive-nav-link>
                {{-- Military Reports --}}
                 <x-responsive-nav-link :href="route('military_affairs.reports.periodLeaveReport')" :active="request()->routeIs('military_affairs.reports.periodLeaveReport')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                     {{ __('app.period_leave_report') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->isCivilianAffairsOfficer())
                <h3 class="mt-4 mb-2 px-3 text-xs font-semibold text-gray-400 dark:text-blue-300 uppercase tracking-wider">
                    {{ __('app.civilian_affairs_officer') }}
                </h3>
                 <x-responsive-nav-link :href="route('civilian_affairs.personnel.index')" :active="request()->routeIs('civilian_affairs.personnel.*')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                    {{ __('app.personnel') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('civilian_affairs.personnel-leaves.index')" :active="request()->routeIs('civilian_affairs.personnel-leaves.*')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                    {{ __('app.personnel_leaves') }}
                </x-responsive-nav-link>
                 {{-- Civilian Reports --}}
                 <x-responsive-nav-link :href="route('civilian_affairs.reports.periodLeaveReport')" :active="request()->routeIs('civilian_affairs.reports.periodLeaveReport')"
                    class="dark:hover:bg-themeBlue-800 dark:focus:bg-themeBlue-800">
                     {{ __('app.period_leave_report') }}
                </x-responsive-nav-link>
            @endif
        @endif
    </nav>
</div>

<!-- TODO: Add a hamburger button to toggle sidebar on mobile, which would dispatch 'toggle-sidebar' event -->
{{-- Example of a toggle button that could be placed in navigation.blade.php or app.blade.php's header
<button @click="$dispatch('toggle-sidebar')" class="md:hidden ...">
    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>
--}}
