@extends('layouts.app')

@section('title', 'Trouver un Conseiller')

@section('content')
<div class="page-container">
    <div class="header">
        <h1>Trouver un Conseiller Financier</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="advisor-list">
            @if($advisors->isEmpty())
                <div class="empty-state">
                    <p>Aucun conseiller financier disponible pour le moment.</p>
                </div>
            @else
                @foreach($advisors as $advisor)
                    <div class="advisor-card">
                        <div class="advisor-info">
                            <h3>{{ $advisor->name }}</h3>
                            <p class="text-muted">{{ $advisor->email }}</p>
                            @if($advisor->bio)
                                <p class="advisor-bio">{{ $advisor->bio }}</p>
                            @endif
                            <div class="advisor-stats">
                                <div class="stat">
                                    <span class="stat-label">Clients</span>
                                    <span class="stat-value">{{ $advisor->connections()->where('status', 'ACTIVE')->count() }}</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-label">Membre depuis</span>
                                    <span class="stat-value">{{ $advisor->created_at->format('m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="advisor-actions">
                            <form method="POST" action="{{ url('/connections') }}" class="inline">
                                @csrf
                                <input type="hidden" name="advisor_id" value="{{ $advisor->id }}">
                                <button type="submit" class="btn btn-primary">Envoyer une demande</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<style>
.advisor-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.advisor-card {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.advisor-info h3 {
    margin: 0;
    font-size: 1.2rem;
    color: var(--text-main);
}

.advisor-bio {
    margin: 1rem 0;
    font-size: 0.95rem;
    color: var(--text-main);
    line-height: 1.5;
}

.advisor-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-top: 1rem;
}

.stat {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.stat-label {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.stat-value {
    font-weight: 500;
    color: var(--text-main);
}

.advisor-actions {
    margin-top: auto;
}

.advisor-actions .btn {
    width: 100%;
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--text-muted);
}
</style>
@endsection
