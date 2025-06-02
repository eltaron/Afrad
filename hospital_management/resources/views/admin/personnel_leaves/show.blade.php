@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('app.personnel_leave') }} {{ __('app.details') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('app.personnel') }}: {{ $personnelLeave->personnel->name ?? __('N/A') }}</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('validation.attributes.military_id') }}: {{ $personnelLeave->personnel->military_id ?? __('N/A') }} |
                            {{ __('app.hospital_force') }}: {{ $personnelLeave->personnel->hospitalForce->name ?? __('N/A') }} |
                            {{ __('app.department') }}: {{ $personnelLeave->personnel->currentDepartment ? $personnelLeave->personnel->currentDepartment->department->name : __('N/A') }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('app.leave_details') }}</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('app.leave_type') }}: {{ $personnelLeave->leaveType->name ?? __('N/A') }}</p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('validation.attributes.start_date') }}: {{ $personnelLeave->start_date->format('Y-m-d') }}</p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('validation.attributes.end_date') }}: {{ $personnelLeave->end_date->format('Y-m-d') }}</p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('validation.attributes.days_taken') }}: {{ $personnelLeave->days_taken }}</p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('validation.attributes.status') }}: <span class="font-semibold">{{ __('app.'.$personnelLeave->status) }}</span></p>
                        @if($personnelLeave->approver)
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('validation.attributes.approved_by') }}: {{ $personnelLeave->approver->name }}</p>
                        @endif
                        @if($personnelLeave->notes)
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('validation.attributes.notes') }}: {{ $personnelLeave->notes }}</p>
                        @endif
                         <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{__('app.requested_on')}}: {{ $personnelLeave->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    @if($personnelLeave->status === 'requested')
                        <div class="mt-6 flex space-x-3">
                            <form action="{{ route('admin.personnel-leaves.approve', $personnelLeave) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-700 text-white font-bold rounded">
                                    {{ __('app.approve') }}
                                </button>
                            </form>
                            <form action="{{ route('admin.personnel-leaves.reject', $personnelLeave) }}" method="POST">
                                @csrf
                                {{-- TODO: Add a field for rejection_reason if desired --}}
                                <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-700 text-white font-bold rounded">
                                    {{ __('app.reject') }}
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('admin.personnel-leaves.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200">
                            &laquo; {{ __('app.back_to_list') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
