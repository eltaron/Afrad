@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-blue-100 leading-tight">
        {{ __('app.edit') }} {{ __('app.leave_type') }}: {{ $leaveType->getTranslation('name', 'ar') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-themeBlue-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-blue-100">
                    <form action="{{ route('admin.leave-types.update', $leaveType->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Name (Arabic) --}}
                        <div class="mb-4">
                            <label for="name_ar" class="block text-sm font-medium text-gray-700 dark:text-blue-200">{{ __('app.name') }} ({{ __('app.arabic') }})</label>
                            <input type="text" name="name_ar" id="name_ar"
                                   value="{{ old('name_ar', $leaveType->getTranslation('name', 'ar')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-themeBlue-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-themeBlue-800 dark:text-blue-100 sm:text-sm @error('name_ar') border-red-500 @enderror"
                                   required>
                            @error('name_ar') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Default Days --}}
                        <div class="mb-4">
                            <label for="default_days" class="block text-sm font-medium text-gray-700 dark:text-blue-200">{{ __('validation.attributes.default_days') }}</label>
                            <input type="number" name="default_days" id="default_days"
                                   value="{{ old('default_days', $leaveType->default_days) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-themeBlue-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-themeBlue-800 dark:text-blue-100 sm:text-sm @error('default_days') border-red-500 @enderror"
                                   required min="0">
                            @error('default_days') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Applies To --}}
                        <div class="mb-4">
                            <label for="applies_to" class="block text-sm font-medium text-gray-700 dark:text-blue-200">{{ __('validation.attributes.applies_to') }}</label>
                            <select name="applies_to" id="applies_to" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-themeBlue-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-themeBlue-800 dark:text-blue-100 sm:text-sm @error('applies_to') border-red-500 @enderror">
                                <option value="all" {{ old('applies_to', $leaveType->applies_to) == 'all' ? 'selected' : '' }}>{{ __('app.all') }}</option>
                                <option value="military" {{ old('applies_to', $leaveType->applies_to) == 'military' ? 'selected' : '' }}>{{ __('app.military') }}</option>
                                <option value="civilian" {{ old('applies_to', $leaveType->applies_to) == 'civilian' ? 'selected' : '' }}>{{ __('app.civilian') }}</option>
                                <option value="specific_rank" {{ old('applies_to', $leaveType->applies_to) == 'specific_rank' ? 'selected' : '' }}>{{ __('app.specific_rank') }}</option>
                                <option value="specific_job_title" {{ old('applies_to', $leaveType->applies_to) == 'specific_job_title' ? 'selected' : '' }}>{{ __('app.specific_job_title') }}</option>
                            </select>
                            @error('applies_to') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Specific Rank or Title --}}
                        <div class="mb-4">
                            <label for="specific_rank_or_title" class="block text-sm font-medium text-gray-700 dark:text-blue-200">{{ __('validation.attributes.specific_rank_or_title') }}</label>
                            <input type="text" name="specific_rank_or_title" id="specific_rank_or_title"
                                   value="{{ old('specific_rank_or_title', $leaveType->specific_rank_or_title) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-themeBlue-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-themeBlue-800 dark:text-blue-100 sm:text-sm @error('specific_rank_or_title') border-red-500 @enderror">
                             <small class="text-gray-500 dark:text-blue-300">{{__('app.leave_type_specific_rank_or_title_hint')}}</small>
                            @error('specific_rank_or_title') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Is Permission --}}
                        <div class="mb-4">
                            <label for="is_permission" class="flex items-center">
                                <input type="checkbox" name="is_permission" id="is_permission" value="1" {{ old('is_permission', $leaveType->is_permission) ? 'checked' : '' }}
                                       class="rounded border-gray-300 dark:border-themeBlue-600 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-themeBlue-700 dark:focus:ring-indigo-600 dark:ring-offset-themeBlue-800">
                                <span class="ms-2 text-sm text-gray-600 dark:text-blue-200">{{ __('validation.attributes.is_permission') }} ({{__('app.is_permission_hint')}})</span>
                            </label>
                            @error('is_permission') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.leave-types.index') }}" class="text-sm text-gray-600 dark:text-blue-300 hover:text-gray-900 dark:hover:text-blue-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-themeBlue-800 me-4">
                                {{ __('app.cancel') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-themeBlue-600 dark:bg-themeBlue-500 hover:bg-themeBlue-700 dark:hover:bg-themeBlue-400 text-white font-semibold rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-themeBlue-500 dark:focus:ring-offset-themeBlue-900">
                                {{ __('app.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
