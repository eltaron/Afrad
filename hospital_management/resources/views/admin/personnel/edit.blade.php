@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('app.edit') }} {{ __('app.personnel') }}: {{ $personnel->name }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.personnel.update', $personnel->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('app.name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $personnel->name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('name') border-red-500 @enderror"
                                   required>
                            @error('name') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Hospital Force --}}
                        <div class="mb-4">
                            <label for="hospital_force_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('app.hospital_force') }}</label>
                            <select name="hospital_force_id" id="hospital_force_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('hospital_force_id') border-red-500 @enderror">
                                <option value="">{{ __('app.select_option') }}</option>
                                @foreach($hospitalForces as $id => $name)
                                    <option value="{{ $id }}" {{ old('hospital_force_id', $personnel->hospital_force_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('hospital_force_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="military_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('validation.attributes.military_id') }}</label>
                            <input type="text" name="military_id" id="military_id" value="{{ old('military_id', $personnel->military_id) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('military_id') border-red-500 @enderror">
                            @error('military_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="rank" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('validation.attributes.rank') }}</label>
                            <input type="text" name="rank" id="rank" value="{{ old('rank', $personnel->rank) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('rank') border-red-500 @enderror">
                            @error('rank') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="job_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('validation.attributes.job_title') }}</label>
                            <input type="text" name="job_title" id="job_title" value="{{ old('job_title', $personnel->job_title) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('job_title') border-red-500 @enderror">
                            @error('job_title') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="national_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('validation.attributes.national_id') }}</label>
                            <input type="text" name="national_id" id="national_id" value="{{ old('national_id', $personnel->national_id) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('national_id') border-red-500 @enderror">
                            @error('national_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('validation.attributes.phone_number') }}</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $personnel->phone_number) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('phone_number') border-red-500 @enderror">
                            @error('phone_number') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="recruitment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('validation.attributes.recruitment_date') }}</label>
                            <input type="date" name="recruitment_date" id="recruitment_date" value="{{ old('recruitment_date', $personnel->recruitment_date ? $personnel->recruitment_date->format('Y-m-d') : '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('recruitment_date') border-red-500 @enderror">
                            @error('recruitment_date') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="termination_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('validation.attributes.termination_date') }}</label>
                            <input type="date" name="termination_date" id="termination_date" value="{{ old('termination_date', $personnel->termination_date ? $personnel->termination_date->format('Y-m-d') : '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('termination_date') border-red-500 @enderror">
                            @error('termination_date') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('app.department') }} ({{ __('app.current') }})</label>
                            <select name="department_id" id="department_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('department_id') border-red-500 @enderror">
                                <option value="">{{ __('app.select_option_optional') }}</option>
                                @php $currentDepartmentId = $personnel->currentDepartment()->first()->department_id ?? null; @endphp
                                @foreach($departments as $id => $name)
                                    <option value="{{ $id }}" {{ old('department_id', $currentDepartmentId) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                             @error('department_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- User ID --}}
                        {{-- <div class="mb-4">
                             <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('User Account (Optional)') }}</label>
                            <select name="user_id" id="user_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm @error('user_id') border-red-500 @enderror">
                                <option value="">{{ __('app.select_option_optional') }}</option>
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}" {{ old('user_id', $personnel->user_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div> --}}


                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.personnel.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 me-4">
                                {{ __('app.cancel') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600 text-white font-semibold rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                                {{ __('app.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
