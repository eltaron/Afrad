<form method="GET" action="{{ $reportRoute }}" class="mb-6 p-4 bg-gray-100 dark:bg-themeBlue-800 rounded-lg shadow">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-blue-200">{{ __('app.start_date') }}</label>
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date', \Carbon\Carbon::now()->startOfMonth()->toDateString()) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-themeBlue-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-themeBlue-900 dark:text-blue-100 sm:text-sm" required>
        </div>
        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-blue-200">{{ __('app.end_date') }}</label>
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date', \Carbon\Carbon::now()->toDateString()) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-themeBlue-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-themeBlue-900 dark:text-blue-100 sm:text-sm" required>
        </div>

        {{-- Optional additional filters can be passed as a slot --}}
        @isset($additionalFilters)
            {{ $additionalFilters }}
        @endisset

        <div>
            <button type="submit" class="w-full px-4 py-2 bg-themeBlue-600 dark:bg-themeBlue-500 hover:bg-themeBlue-700 dark:hover:bg-themeBlue-400 text-white font-semibold rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-themeBlue-500 dark:focus:ring-offset-themeBlue-900">
                {{ __('app.generate_report') }}
            </button>
        </div>
    </div>
    @if ($errors->has('start_date'))
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $errors->first('start_date') }}</p>
    @endif
    @if ($errors->has('end_date'))
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $errors->first('end_date') }}</p>
    @endif
</form>
