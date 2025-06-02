@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-blue-100 leading-tight">
        {{ __('app.daily_eligible_for_leave_report') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-themeBlue-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-blue-100">
                    @if(empty($eligiblePersonnelReport))
                        <p>{{ __('app.no_data_available') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-themeBlue-700">
                                <thead class="bg-gray-50 dark:bg-themeBlue-800">
                                    <tr>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.personnel') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.military_id') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.rank') }}/{{ __('validation.attributes.job_title') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.leave_types') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-themeBlue-900 divide-y divide-gray-200 dark:divide-themeBlue-700">
                                    @foreach ($eligiblePersonnelReport as $reportItem)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-themeBlue-800">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-blue-100">{{ $reportItem['personnel_name'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $reportItem['military_id'] ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $reportItem['rank'] ?? $reportItem['job_title'] ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">
                                                @if(!empty($reportItem['eligible_for_leave_types']))
                                                    {{ implode(', ', $reportItem['eligible_for_leave_types']) }}
                                                @else
                                                    {{ __('app.none') }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
