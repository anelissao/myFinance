@extends('layouts.app')

@section('title', 'Mon Profil Public')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
            <h2 class="text-2xl font-bold mb-4 text-indigo-700">Mon Profil Public</h2>
            <div class="mb-4">
                <div class="text-lg font-semibold text-gray-900">{{ $advisor->name }}</div>
                <div class="text-sm text-indigo-600 mb-2">{{ $advisor->specialization }}</div>
                <div class="flex items-center mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="h-5 w-5 {{ $i <= $advisor->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                    <span class="ml-2 text-xs text-gray-500">{{ number_format($advisor->rating, 1) }}/5</span>
                </div>
                <div class="text-gray-700 text-sm">{{ $advisor->description }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
