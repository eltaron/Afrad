@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-blue-100 leading-tight">
        {{ __('app.period_violation_report') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('admin.reports._period_selection_form', ['reportRoute' => route('admin.reports.periodViolationReport')])

            <div class="bg-white dark:bg-themeBlue-900 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900 dark:text-blue-100">
                    @if(isset($violations) && $violations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-themeBlue-700">
                                <thead class="bg-gray-50 dark:bg-themeBlue-800">
                                    <tr>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.personnel') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.violation_type') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.violation_date') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.penalty_type') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.penalty_days') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.leave_deduction_days') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-themeBlue-900 divide-y divide-gray-200 dark:divide-themeBlue-700">
                                    @foreach ($violations as $violation)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-themeBlue-800">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-blue-100">{{ $violation->personnel->name ?? __('N/A') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $violation->violationType->name ?? __('N/A') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $violation->violation_date->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $violation->penalty_type }}</td> {{-- Consider translating enum --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $violation->penalty_days ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $violation->leave_deduction_days ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                             @if ($violations instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $violations->appends(request()->query())->links() }}
                            @endif
                        </div>
                    @elseif(isset($violations))  {{-- Means form submitted but no results --}}
                        <p>{{ __('app.no_data_available') }} {{ __('app.for_selected_period') }}</p>
                    @else
                        <p>{{ __('app.please_select_period_to_generate_report') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
