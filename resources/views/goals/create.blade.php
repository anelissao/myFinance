@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Financial Goal</h2>
    <form method="POST" action="{{ route('goals.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Goal Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="target_amount">Target Amount</label>
            <input type="number" class="form-control" id="target_amount" name="target_amount" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="due_date">Due Date</label>
            <input type="date" class="form-control" id="due_date" name="due_date" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Create Goal</button>
    </form>
</div>
@endsection
