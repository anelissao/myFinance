@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="page-container">
    <div class="header">
        <h1>Transactions</h1>
        <a href="{{ url('/transactions/create') }}" class="btn btn-primary">
            <span>+</span> New Transaction
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        @if($transactions->isEmpty())
            <div class="empty-state">
                <p>No transactions found</p>
                <a href="{{ url('/transactions/create') }}" class="btn btn-secondary">Add your first transaction</a>
            </div>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Account</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th class="amount-column">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->date->format('M d, Y') }}</td>
                                <td>{{ $transaction->account->name }}</td>
                                <td>{{ $transaction->category->name }}</td>
                                <td>{{ $transaction->description ?: '-' }}</td>
                                <td class="amount-column {{ $transaction->amount > 0 ? 'income' : 'expense' }}">
                                    {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount, 2) }} €
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
    .page-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
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
        overflow: hidden;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-muted);
    }

    .empty-state .btn {
        margin-top: 1rem;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    th {
        background-color: var(--primary);
        color: white;
        font-weight: 500;
    }

    tr:hover {
        background-color: var(--background);
    }

    .amount-column {
        text-align: right;
        font-family: monospace;
        font-weight: 500;
    }

    .income {
        color: var(--success);
    }

    .expense {
        color: var(--error);
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .alert-success {
        background-color: rgba(52, 168, 83, 0.1);
        border: 1px solid var(--success);
        color: var(--success);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: 500;
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .btn:hover {
        opacity: 0.9;
    }

    .btn-primary {
        background-color: var(--secondary);
        color: white;
    }

    .btn-secondary {
        background-color: var(--accent);
        color: var(--primary);
    }
</style>
@endsection
