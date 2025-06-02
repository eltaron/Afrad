@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-blue-100 leading-tight">
        {{ __('app.personnel_leaves') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-themeBlue-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-blue-100">
                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('admin.personnel-leaves.create') }}" class="bg-themeBlue-600 hover:bg-themeBlue-500 dark:bg-themeBlue-500 dark:hover:bg-themeBlue-400 text-white font-bold py-2 px-4 rounded">
                            {{ __('app.create_new') }} {{ __('app.personnel_leave') }}
                        </a>
                    </div>

                     @if(session('success'))
                        <div class="mb-4 p-4 bg-green-200 dark:bg-green-700 text-green-800 dark:text-green-100 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-200 dark:bg-red-700 text-red-800 dark:text-red-100 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- TODO: Add Filters (e.g., by status, personnel, leave type) --}}

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-themeBlue-700">
                            <thead class="bg-gray-50 dark:bg-themeBlue-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.personnel') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.leave_type') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.start_date') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.end_date') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.days_taken') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.status') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-themeBlue-900 divide-y divide-gray-200 dark:divide-themeBlue-700">
                                @forelse ($personnelLeaves as $leave)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-themeBlue-800">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-blue-100">{{ $leave->personnel->name ?? __('N/A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $leave->leaveType->name ?? __('N/A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $leave->start_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $leave->end_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $leave->days_taken }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ __('app.'.$leave->status) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.personnel-leaves.show', $leave) }}" class="text-themeBlue-600 dark:text-themeBlue-400 hover:text-themeBlue-900 dark:hover:text-themeBlue-200 me-3">{{ __('app.details') }}</a>
                                            @if($leave->status === 'requested')
                                                <form action="{{ route('admin.personnel-leaves.approve', $leave) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 me-3">{{ __('app.approve') }}</button>
                                                </form>
                                                <form action="{{ route('admin.personnel-leaves.reject', $leave) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200">{{ __('app.reject') }}</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-blue-200">
                                            {{ __('app.no_data_available') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $personnelLeaves->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
