@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-blue-100 leading-tight">
        {{ __('app.edit') }} {{ __('app.violation_type') }}: {{ $violationType->getTranslation('name', 'ar') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-themeBlue-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-blue-100">
                    <form action="{{ route('admin.violation-types.update', $violationType->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Name (Arabic) --}}
                        <div class="mb-4">
                            <label for="name_ar" class="block text-sm font-medium text-gray-700 dark:text-blue-200">{{ __('app.name') }} ({{ __('app.arabic') }})</label>
                            <input type="text" name="name_ar" id="name_ar"
                                   value="{{ old('name_ar', $violationType->getTranslation('name', 'ar')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-themeBlue-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-themeBlue-800 dark:text-blue-100 sm:text-sm @error('name_ar') border-red-500 @enderror"
                                   required>
                            @error('name_ar') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Description (Arabic) --}}
                        <div class="mb-4">
                            <label for="description_ar" class="block text-sm font-medium text-gray-700 dark:text-blue-200">{{ __('validation.attributes.description') }} ({{ __('app.arabic') }})</label>
                            <textarea name="description_ar" id="description_ar" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-themeBlue-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-themeBlue-800 dark:text-blue-100 sm:text-sm @error('description_ar') border-red-500 @enderror"
                                      >{{ old('description_ar', $violationType->getTranslation('description', 'ar')) }}</textarea>
                            @error('description_ar') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Add fields for other languages if needed --}}

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.violation-types.index') }}" class="text-sm text-gray-600 dark:text-blue-300 hover:text-gray-900 dark:hover:text-blue-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-themeBlue-800 me-4">
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
