@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-blue-100 leading-tight">
        {{ __('app.all_personnel') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-themeBlue-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-blue-100">
                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('admin.personnel.create') }}" class="bg-themeBlue-600 hover:bg-themeBlue-500 dark:bg-themeBlue-500 dark:hover:bg-themeBlue-400 text-white font-bold py-2 px-4 rounded">
                            {{ __('app.create_new') }} {{ __('app.personnel') }}
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-200 dark:bg-green-700 text-green-800 dark:text-green-100 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- TODO: Add Filters (e.g., by department, hospital force) --}}

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-themeBlue-700">
                            <thead class="bg-gray-50 dark:bg-themeBlue-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.name') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.military_id') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.national_id') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.phone_number') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.job_title') }} / {{ __('validation.attributes.rank') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.hospital_force') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.department') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-themeBlue-900 divide-y divide-gray-200 dark:divide-themeBlue-700">
                                @forelse ($personnelList as $personnel)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-themeBlue-800">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-blue-100">{{ $personnel->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $personnel->military_id ?: 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $personnel->national_id ?: 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $personnel->phone_number ?: 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $personnel->job_title ?: $personnel->rank ?: 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $personnel->hospitalForce ? $personnel->hospitalForce->name : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $personnel->currentDepartment ? $personnel->currentDepartment->department->name : __('N/A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.personnel.show', $personnel) }}" class="text-themeBlue-600 dark:text-themeBlue-400 hover:text-themeBlue-900 dark:hover:text-themeBlue-200 me-3">{{ __('app.details') }}</a>
                                            <a href="{{ route('admin.personnel.edit', $personnel) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-200 me-3">{{ __('app.edit') }}</a>
                                            <form action="{{ route('admin.personnel.destroy', $personnel) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('app.confirm_delete') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200">{{ __('app.delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-blue-200">
                                            {{ __('app.no_data_available') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $personnelList->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
