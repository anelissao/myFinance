@extends('layouts.app')

@section('title', 'Welcome to MyFinance')

@section('content')
<div class="hero">
    <div class="hero-content">
        <h1>Smart Personal Finance Management</h1>
        <p>Take control of your finances with our simple and intuitive tools</p>
        @guest
            <div class="hero-buttons">
                <a href="{{ url('/register') }}" class="btn btn-primary">Get Started</a>
                <a href="{{ url('/login') }}" class="btn btn-secondary">Login</a>
            </div>
        @endguest
    </div>
</div>

<div class="features">
    <div class="feature-card">
        <div class="feature-icon">📊</div>
        <h3>Track Expenses</h3>
        <p>Monitor your income and expenses with easy categorization</p>
    </div>

    <div class="feature-card">
        <div class="feature-icon">🎯</div>
        <h3>Set Goals</h3>
        <p>Create and track your financial goals with progress tracking</p>
    </div>

    <div class="feature-card">
        <div class="feature-icon">📅</div>
        <h3>Monthly Budgets</h3>
        <p>Set budgets by category and get alerts when you exceed them</p>
    </div>

    <div class="feature-card">
        <div class="feature-icon">📈</div>
        <h3>Analytics</h3>
        <p>View your financial health with simple, interactive charts</p>
    </div>
</div>

<div class="cta-section">
    <div class="advisor-info">
        <h2>Need Professional Guidance?</h2>
        <p>Connect with our financial advisors for personalized advice</p>
        <a href="{{ url('/register') }}?role=CONSEILLER_FINANCIER" class="btn btn-secondary">Join as Advisor</a>
    </div>
</div>

<style>
    .hero {
        background-color: var(--primary);
        color: white;
        padding: 4rem 2rem;
        text-align: center;
    }

    .hero-content {
        max-width: 800px;
        margin: 0 auto;
    }

    .hero h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .hero p {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .hero-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .features {
        max-width: 1200px;
        margin: 4rem auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .feature-card {
        background: var(--surface);
        padding: 2rem;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .feature-card h3 {
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .feature-card p {
        color: var(--text-muted);
    }

    .cta-section {
        background-color: var(--background);
        padding: 4rem 2rem;
        text-align: center;
        margin-top: 2rem;
    }

    .advisor-info {
        max-width: 600px;
        margin: 0 auto;
    }

    .advisor-info h2 {
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .advisor-info p {
        color: var(--text-muted);
        margin-bottom: 2rem;
    }

    .btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
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

    @media (max-width: 768px) {
        .hero h1 {
            font-size: 2rem;
        }

        .hero p {
            font-size: 1rem;
        }

        .features {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
