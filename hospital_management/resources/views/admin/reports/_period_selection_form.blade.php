<form method="GET" action="{{ $reportRoute }}" class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('app.start_date') }}</label>
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date', \Carbon\Carbon::now()->startOfMonth()->toDateString()) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-gray-200 sm:text-sm" required>
        </div>
        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('app.end_date') }}</label>
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date', \Carbon\Carbon::now()->toDateString()) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-gray-200 sm:text-sm" required>
        </div>

        {{-- Optional additional filters can be passed as a slot --}}
        @isset($additionalFilters)
            {{ $additionalFilters }}
        @endisset

        <div>
            <button type="submit" class="w-full px-4 py-2 bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600 text-white font-semibold rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
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
