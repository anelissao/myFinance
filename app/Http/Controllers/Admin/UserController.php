<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.dashboard', compact('users'));
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Les administrateurs ne peuvent pas être supprimés.');
        }

        // Delete related records (you might want to customize this based on your needs)
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Utilisateur supprimé avec succès.');
    }
} 