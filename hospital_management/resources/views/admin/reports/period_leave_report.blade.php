@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-blue-100 leading-tight">
        {{ __('app.period_leave_report') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('admin.reports._period_selection_form', ['reportRoute' => route('admin.reports.periodLeaveReport')])

            <div class="bg-white dark:bg-themeBlue-900 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900 dark:text-blue-100">
                    @if(isset($leaves) && $leaves->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-themeBlue-700">
                                <thead class="bg-gray-50 dark:bg-themeBlue-800">
                                    <tr>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.personnel') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.leave_type') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.start_date') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.end_date') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.days_taken') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.status') }}</th>
                                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.approved_by') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-themeBlue-900 divide-y divide-gray-200 dark:divide-themeBlue-700">
                                    @foreach ($leaves as $leave)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-themeBlue-800">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-blue-100">{{ $leave->personnel->name ?? __('N/A') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $leave->leaveType->name ?? __('N/A') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $leave->start_date->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $leave->end_date->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $leave->days_taken }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ __('app.'.$leave->status) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $leave->approver->name ?? __('N/A') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{-- Check if $leaves is paginated before calling links() --}}
                            @if ($leaves instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $leaves->appends(request()->query())->links() }}
                            @endif
                        </div>
                    @elseif(isset($leaves)) {{-- Means form submitted but no results --}}
                        <p>{{ __('app.no_data_available') }} {{ __('app.for_selected_period') }}</p>
                    @else
                         <p>{{ __('app.please_select_period_to_generate_report') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
