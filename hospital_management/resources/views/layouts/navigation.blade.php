<nav x-data="{ open: false }" class="bg-white dark:bg-themeBlue-900 border-b border-gray-100 dark:border-themeBlue-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Hamburger toggle for sidebar (visible on mobile/tablet, or always if sidebar is overlay) -->
                <div class="flex items-center md:hidden">
                    <button @click="$dispatch('toggle-sidebar')" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-blue-300 hover:text-gray-500 dark:hover:text-blue-200 hover:bg-gray-100 dark:hover:bg-themeBlue-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-themeBlue-800 focus:text-gray-500 dark:focus:text-blue-200 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Logo (Optional here if also in sidebar, or for mobile view) -->
                <div class="shrink-0 flex items-center md:hidden"> {{-- Hidden on md and up, shown on mobile --}}
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-blue-100" />
                    </a>
                </div>

                <!-- Top Navigation Links (if any are kept here) -->
                {{-- Example:
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
                --}}
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-blue-200 bg-white dark:bg-themeBlue-900 hover:text-gray-700 dark:hover:text-blue-100 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- Ensure dropdown content has appropriate dark mode styles too --}}
                        <div class="bg-white dark:bg-themeBlue-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1">
                            <x-dropdown-link :href="route('profile.edit')" class="dark:text-blue-100 dark:hover:bg-themeBlue-700">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();"
                                        class="dark:text-blue-100 dark:hover:bg-themeBlue-700">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-blue-200 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">{{__('Log in')}}</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-blue-200 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">{{__('Register')}}</a>
                    @endif
                @endauth
            </div>

            <!-- Hamburger (for original top-nav responsive menu, not sidebar) -->
            {{-- This hamburger is for the dropdown of the top-nav itself on small screens,
                 distinct from the sidebar toggle. We might not need it if all nav is in sidebar.
                 For now, I'll keep it but it might be redundant if sidebar has all links.
            --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-blue-300 hover:text-gray-500 dark:hover:text-blue-200 hover:bg-gray-100 dark:hover:bg-themeBlue-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-themeBlue-800 focus:text-gray-500 dark:focus:text-blue-200 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (for top-bar items like profile/logout on mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        {{-- Removed dashboard link from here as it's in sidebar --}}
        {{-- <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div> --}}

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-themeBlue-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-blue-100">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 dark:text-blue-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="dark:text-blue-100 dark:hover:bg-themeBlue-700 dark:border-themeBlue-600">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="dark:text-blue-100 dark:hover:bg-themeBlue-700 dark:border-themeBlue-600">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
            <div class="py-1 border-t border-gray-200 dark:border-themeBlue-600">
                 <x-responsive-nav-link :href="route('login')" class="dark:text-blue-100 dark:hover:bg-themeBlue-700">
                    {{ __('Log in') }}
                </x-responsive-nav-link>
                @if (Route::has('register'))
                    <x-responsive-nav-link :href="route('register')" class="dark:text-blue-100 dark:hover:bg-themeBlue-700">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                @endif
            </div>
        @endauth
    </div>
</nav>
