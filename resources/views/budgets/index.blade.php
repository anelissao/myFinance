@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Budgets</h1>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">{{ session('success') }}</div>
    @endif
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-4 py-2">Category</th>
                <th class="border px-4 py-2">Amount</th>
                <th class="border px-4 py-2">Month</th>
            </tr>
        </thead>
        <tbody>
            @foreach($budgets as $budget)
                <tr>
                    <td class="border px-4 py-2">{{ $budget->category->name ?? 'N/A' }}</td>
                    <td class="border px-4 py-2">{{ $budget->amount ?? $budget->planned_amount }}</td>
                    <td class="border px-4 py-2">{{ $budget->month }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h2 class="text-xl font-semibold mt-8 mb-2">Categories</h2>
    <ul class="list-disc pl-5">
        @foreach($categories as $category)
            <li>{{ $category->name }} ({{ $category->type }})</li>
        @endforeach
    </ul>
</div>
@endsection
