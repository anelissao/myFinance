<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\Advisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $connections = $user->connections()
            ->with('advisor')
            ->latest()
            ->paginate(10);

        return view('connections.index', compact('connections'));
    }

    public function create(Request $request, Advisor $advisor)
    {
        return view('connections.create', compact('advisor'));
    }

    public function store(Request $request, Advisor $advisor)
    {
        $request->validate([
            'message' => 'nullable|string|max:500'
        ]);

        $connection = Connection::create([
            'user_id' => Auth::id(),
            'advisor_id' => $advisor->id,
            'message' => $request->message,
            'status' => 'pending'
        ]);

        return redirect()->route('connections.index')
            ->with('success', 'Consultation request sent successfully.');
    }

    public function update(Request $request, Connection $connection)
    {
        // Only advisors can update connection status
        if (!Auth::user()->isAdvisor()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);

        $connection->update([
            'status' => $request->status
        ]);

        return redirect()->back()
            ->with('success', 'Connection status updated successfully.');
    }
}
