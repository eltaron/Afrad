@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('app.daily_eligible_for_leave_report') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(empty($eligiblePersonnelReport))
                        <p>{{ __('app.no_data_available') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('app.personnel') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('validation.attributes.military_id') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('validation.attributes.rank') }}/{{ __('validation.attributes.job_title') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('app.leave_types') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($eligiblePersonnelReport as $reportItem)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $reportItem['personnel_name'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $reportItem['military_id'] ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $reportItem['rank'] ?? $reportItem['job_title'] ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                @if(!empty($reportItem['eligible_for_leave_types']))
                                                    {{ implode(', ', $reportItem['eligible_for_leave_types']) }}
                                                @else
                                                    {{ __('app.none') }} {{-- Add 'none' to lang/ar/app.php --}}
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
