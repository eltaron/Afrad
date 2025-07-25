@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-blue-100 leading-tight">
        {{ __('app.violation_types') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-themeBlue-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-blue-100">
                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('admin.violation-types.create') }}" class="bg-themeBlue-600 hover:bg-themeBlue-500 dark:bg-themeBlue-500 dark:hover:bg-themeBlue-400 text-white font-bold py-2 px-4 rounded">
                            {{ __('app.create_new') }} {{ __('app.violation_type') }}
                        </a>
                    </div>

                     @if(session('success'))
                        <div class="mb-4 p-4 bg-green-200 dark:bg-green-700 text-green-800 dark:text-green-100 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-themeBlue-700">
                            <thead class="bg-gray-50 dark:bg-themeBlue-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.name') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('validation.attributes.description') }}</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-blue-200 uppercase tracking-wider">{{ __('app.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-themeBlue-900 divide-y divide-gray-200 dark:divide-themeBlue-700">
                                @forelse ($violationTypes as $violationType)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-themeBlue-800">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-blue-100">{{ $violationType->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ $violationType->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-blue-200">{{ Str::limit($violationType->description, 50) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.violation-types.edit', $violationType) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-200 me-3">{{ __('app.edit') }}</a>
                                            <form action="{{ route('admin.violation-types.destroy', $violationType) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('app.confirm_delete') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200">{{ __('app.delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-blue-200">
                                            {{ __('app.no_data_available') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $violationTypes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
