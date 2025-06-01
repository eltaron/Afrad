@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('app.create_new') }} {{ __('app.personnel_leave') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.personnel-leaves.store') }}" method="POST">
                        @csrf

                        {{-- Personnel --}}
                        <div class="mb-4">
                            <label for="personnel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('app.personnel') }}</label>
                            <select name="personnel_id" id="personnel_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('personnel_id') border-red-500 @enderror">
                                <option value="">{{ __('app.select_option') }}</option>
                                @foreach($personnelList as $person)
                                    <option value="{{ $person->id }}" {{ old('personnel_id') == $person->id ? 'selected' : '' }}>
                                        {{ $person->name }} ({{ $person->military_id ?? $person->national_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('personnel_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Leave Type --}}
                        <div class="mb-4">
                            <label for="leave_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('app.leave_type') }}</label>
                            <select name="leave_type_id" id="leave_type_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('leave_type_id') border-red-500 @enderror">
                                <option value="">{{ __('app.select_option') }}</option>
                                @foreach($leaveTypes as $leaveType)
                                    <option value="{{ $leaveType->id }}" {{ old('leave_type_id') == $leaveType->id ? 'selected' : '' }}>
                                        {{ $leaveType->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Start Date --}}
                        <div class="mb-4">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('validation.attributes.start_date') }}</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('start_date') border-red-500 @enderror"
                                   required>
                            @error('start_date') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- End Date --}}
                        <div class="mb-4">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('validation.attributes.end_date') }}</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('end_date') border-red-500 @enderror"
                                   required>
                            @error('end_date') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Notes --}}
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('validation.attributes.notes') }}</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('notes') border-red-500 @enderror"
                                      >{{ old('notes') }}</textarea>
                            @error('notes') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.personnel-leaves.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 me-4">
                                {{ __('app.cancel') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600 text-white font-semibold rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                                {{ __('app.save') }} {{-- Or 'Request Leave' --}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
