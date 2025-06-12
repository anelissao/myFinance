@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="card">
    <h1>Register</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="list-style: none; margin: 0; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ url('/register') }}">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <label>Select Your Role</label>
            <div class="radio-group">
                <div class="radio-option">
                    <input type="radio" id="role_utilisateur" name="role" value="UTILISATEUR" checked>
                    <label for="role_utilisateur">Particulier</label>
                </div>
                <div class="radio-option">
                    <input type="radio" id="role_conseiller" name="role" value="CONSEILLER_FINANCIER">
                    <label for="role_conseiller">Conseiller Financier</label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>

    <a href="{{ url('/login') }}" class="link">Already have an account? Login here</a>
</div>
@endsection
