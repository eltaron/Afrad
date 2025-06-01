<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(Auth::check())
                        @if(Auth::user()->isAdmin())
                            <x-nav-link :href="route('admin.hospital-forces.index')" :active="request()->routeIs('admin.hospital-forces.*')">
                                {{ __('app.hospital_forces') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.departments.index')" :active="request()->routeIs('admin.departments.*')">
                                {{ __('app.departments') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.personnel.index')" :active="request()->routeIs('admin.personnel.*')">
                                {{ __('app.all_personnel') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.violation-types.index')" :active="request()->routeIs('admin.violation-types.*')">
                                {{ __('app.violation_types') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.leave-types.index')" :active="request()->routeIs('admin.leave-types.*')">
                                {{ __('app.leave_types') }}
                            </x-nav-link>
                             <x-nav-link :href="route('admin.personnel-leaves.index')" :active="request()->routeIs('admin.personnel-leaves.*')">
                                {{ __('app.personnel_leaves') }} {{-- (Admin View) --}}
                            </x-nav-link>
                            {{-- Add Users link if user management is needed --}}
                            {{-- <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                {{ __('app.users') }}
                            </x-nav-link> --}}
                        @endif

                        @if(Auth::user()->isMilitaryAffairsOfficer())
                            <x-nav-link :href="route('military_affairs.personnel.index')" :active="request()->routeIs('military_affairs.personnel.*')">
                                {{ __('app.personnel') }} {{-- (Military) --}}
                            </x-nav-link>
                            <x-nav-link :href="route('military_affairs.personnel-leaves.index')" :active="request()->routeIs('military_affairs.personnel-leaves.*')">
                                {{ __('app.personnel_leaves') }}
                            </x-nav-link>
                            {{-- Reports link could be a dropdown or specific report links --}}
                            {{-- <x-nav-link :href="route('military_affairs.reports.periodLeaveReport')" :active="request()->routeIs('military_affairs.reports.*')">
                                {{ __('Reports') }}
                            </x-nav-link> --}}
                        @endif

                        @if(Auth::user()->isCivilianAffairsOfficer())
                             <x-nav-link :href="route('civilian_affairs.personnel.index')" :active="request()->routeIs('civilian_affairs.personnel.*')">
                                {{ __('app.personnel') }} {{-- (Civilian) --}}
                            </x-nav-link>
                            <x-nav-link :href="route('civilian_affairs.personnel-leaves.index')" :active="request()->routeIs('civilian_affairs.personnel-leaves.*')">
                                {{ __('app.personnel_leaves') }}
                            </x-nav-link>
                            {{-- Reports link --}}
                            {{-- <x-nav-link :href="route('civilian_affairs.reports.periodLeaveReport')" :active="request()->routeIs('civilian_affairs.reports.*')">
                                {{ __('Reports') }}
                            </x-nav-link> --}}
                        @endif
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">{{__('Log in')}}</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">{{__('Register')}}</a>
                    @endif
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(Auth::check())
                @if(Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.hospital-forces.index')" :active="request()->routeIs('admin.hospital-forces.*')">
                        {{ __('app.hospital_forces') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.departments.index')" :active="request()->routeIs('admin.departments.*')">
                        {{ __('app.departments') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.personnel.index')" :active="request()->routeIs('admin.personnel.*')">
                        {{ __('app.all_personnel') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.violation-types.index')" :active="request()->routeIs('admin.violation-types.*')">
                        {{ __('app.violation_types') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.leave-types.index')" :active="request()->routeIs('admin.leave-types.*')">
                        {{ __('app.leave_types') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.personnel-leaves.index')" :active="request()->routeIs('admin.personnel-leaves.*')">
                        {{ __('app.personnel_leaves') }}
                    </x-responsive-nav-link>
                @endif
                @if(Auth::user()->isMilitaryAffairsOfficer())
                    <x-responsive-nav-link :href="route('military_affairs.personnel.index')" :active="request()->routeIs('military_affairs.personnel.*')">
                        {{ __('app.personnel') }} (Military)
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('military_affairs.personnel-leaves.index')" :active="request()->routeIs('military_affairs.personnel-leaves.*')">
                        {{ __('app.personnel_leaves') }}
                    </x-responsive-nav-link>
                @endif
                @if(Auth::user()->isCivilianAffairsOfficer())
                    <x-responsive-nav-link :href="route('civilian_affairs.personnel.index')" :active="request()->routeIs('civilian_affairs.personnel.*')">
                        {{ __('app.personnel') }} (Civilian)
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('civilian_affairs.personnel-leaves.index')" :active="request()->routeIs('civilian_affairs.personnel-leaves.*')">
                        {{ __('app.personnel_leaves') }}
                    </x-responsive-nav-link>
                @endif
            @endif
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>
