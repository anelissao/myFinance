@extends('layouts.app')

@section('title', 'Add Transaction')

@section('content')
<div class="page-container">
    <div class="header">
        <h1>Add Transaction</h1>
    </div>

    <div class="card">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/transactions') }}">
            @csrf
            
            <div class="form-group">
                <label for="account_id">Account</label>
                <select id="account_id" name="account_id" class="form-control" required>
                    <option value="">Select an account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->name }} ({{ number_format($account->balance, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <option value="">Select a category</option>
                    <optgroup label="Income">
                        @foreach($categories->where('type', 'income') as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Expense">
                        @foreach($categories->where('type', 'expense') as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" step="0.01" id="amount" name="amount" value="{{ old('amount') }}" class="form-control" required>
                <small class="text-muted">Use positive numbers for income and negative for expenses</small>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" value="{{ old('description') }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="form-control" required>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Save Transaction</button>
                <a href="{{ url('/transactions') }}" class="btn btn-link">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
    .page-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
    }

    .header {
        margin-bottom: 2rem;
    }

    .header h1 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary);
    }

    .card {
        background: var(--surface);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-main);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--secondary);
        box-shadow: 0 0 0 3px rgba(12, 124, 89, 0.1);
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%236C757D' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }

    .alert {
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1.5rem;
    }

    .alert-danger {
        background-color: rgba(217, 48, 37, 0.1);
        border: 1px solid var(--error);
        color: var(--error);
    }

    .alert ul {
        margin: 0;
        padding-left: 1.5rem;
    }

    .form-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        font-weight: 500;
        text-decoration: none;
        transition: opacity 0.2s;
        cursor: pointer;
        border: none;
    }

    .btn-primary {
        background-color: var(--secondary);
        color: white;
    }

    .btn-link {
        background: none;
        color: var(--text-muted);
    }

    .btn:hover {
        opacity: 0.9;
    }

    .text-muted {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    optgroup {
        font-weight: 600;
        color: var(--text-muted);
    }
</style>
@endsection
