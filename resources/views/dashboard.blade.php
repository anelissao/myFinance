@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard">
    <!-- Welcome Section -->
    <div class="welcome-bar">
        <h1>Welcome, {{ Auth::user()->name }}</h1>
        <div class="date">{{ now()->format('F d, Y') }}</div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Balance</div>
            <div class="stat-value">{{ number_format($totalBalance ?? 0, 2) }} €</div>
            <div class="stat-label">Across all accounts</div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Monthly Income</div>
            <div class="stat-value income">{{ number_format($monthlyIncome ?? 0, 2) }} €</div>
            <div class="stat-label">This month</div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Monthly Expenses</div>
            <div class="stat-value expense">{{ number_format($monthlyExpenses ?? 0, 2) }} €</div>
            <div class="stat-label">This month</div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Budget Status</div>
            <div class="stat-value">{{ $budgetUsagePercentage ?? 0 }}%</div>
            <div class="stat-label">Of monthly budget used</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h2>Quick Actions</h2>
        <div class="action-buttons">
            <a href="{{ route('transactions.create') }}" class="action-btn">
                <span>➕</span> Add Transaction
            </a>
            <a href="{{ route('transactions.index') }}" class="action-btn">
                <span>📊</span> View Transactions
            </a>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="recent-section">
        <div class="section-header">
            <h2>Recent Transactions</h2>
            <a href="{{ route('transactions.index') }}" class="view-all">View All</a>
        </div>
        
        @if(isset($recentTransactions) && $recentTransactions->count() > 0)
            <div class="transactions-list">
                @foreach($recentTransactions as $transaction)
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-title">{{ $transaction->description ?: 'Unnamed Transaction' }}</div>
                            <div class="transaction-category">{{ $transaction->category->name }}</div>
                        </div>
                        <div class="transaction-amount {{ $transaction->amount > 0 ? 'income' : 'expense' }}">
                            {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount, 2) }} €
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p>No recent transactions</p>
                <a href="{{ route('transactions.create') }}" class="btn btn-secondary">Add your first transaction</a>
            </div>
        @endif
    </div>
</div>

<style>
    .dashboard {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .welcome-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .welcome-bar h1 {
        font-size: 1.5rem;
        color: var(--primary);
    }

    .date {
        color: var(--text-muted);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--surface);
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .stat-title {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .quick-actions {
        margin-bottom: 2rem;
    }

    .quick-actions h2 {
        font-size: 1.25rem;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: var(--surface);
        border: 1px solid #eee;
        border-radius: 8px;
        color: var(--text-main);
        text-decoration: none;
        transition: all 0.2s;
    }

    .action-btn:hover {
        border-color: var(--secondary);
        color: var(--secondary);
    }

    .recent-section {
        background: var(--surface);
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .section-header h2 {
        font-size: 1.25rem;
        color: var(--primary);
    }

    .view-all {
        color: var(--secondary);
        text-decoration: none;
    }

    .transactions-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .transaction-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #eee;
    }

    .transaction-item:last-child {
        border-bottom: none;
    }

    .transaction-title {
        font-weight: 500;
        color: var(--text-main);
    }

    .transaction-category {
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .transaction-amount {
        font-family: monospace;
        font-weight: 500;
    }

    .income {
        color: var(--success);
    }

    .expense {
        color: var(--error);
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: var(--text-muted);
    }

    .empty-state .btn {
        display: inline-block;
        margin-top: 1rem;
    }

    .btn-secondary {
        background-color: var(--accent);
        color: var(--primary);
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .btn-secondary:hover {
        opacity: 0.9;
    }

    @media (max-width: 768px) {
        .dashboard {
            padding: 1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .welcome-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>
@endsection
