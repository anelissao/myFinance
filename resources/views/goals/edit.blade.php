@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Financial Goal</h2>
    <form method="POST" action="{{ route('goals.update', $goal) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Goal Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $goal->name) }}" required>
        </div>
        <div class="form-group">
            <label for="target_amount">Target Amount</label>
            <input type="number" class="form-control" id="target_amount" name="target_amount" step="0.01" value="{{ old('target_amount', $goal->target_amount) }}" required>
        </div>
        <div class="form-group">
            <label for="current_amount">Current Amount</label>
            <input type="number" class="form-control" id="current_amount" name="current_amount" step="0.01" value="{{ old('current_amount', $goal->current_amount) }}" required>
        </div>
        <div class="form-group">
            <label for="due_date">Due Date</label>
            <input type="date" class="form-control" id="due_date" name="due_date" value="{{ old('due_date', $goal->due_date ? $goal->due_date->format('Y-m-d') : '' ) }}" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update Goal</button>
    </form>
</div>
@endsection
