<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Show admin dashboard
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Show all pending users
    public function pendingUsers()
    {
        $pendingUsers = User::where('is_approved', false)
            ->whereIn('role', ['seller', 'rider'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pending-users', compact('pendingUsers'));
    }

    // Approve a user
    public function approveUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot modify admin accounts');
        }

        $user->is_approved = true;
        $user->save();

        return back()->with('success', "User {$user->name} has been approved!");
    }

    // Reject/Delete a user
    public function rejectUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot modify admin accounts');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "User {$userName} has been rejected and removed.");
    }

    // View all users
    public function allUsers()
    {
        $users = User::where('role', '!=', 'admin')
            ->orderBy('is_approved', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.all-users', compact('users'));
    }

    // Suspend/Block a user
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot modify admin accounts');
        }

        $user->is_approved = !$user->is_approved;
        $user->save();

        $status = $user->is_approved ? 'activated' : 'suspended';
        return back()->with('success', "User {$user->name} has been {$status}.");
    }
}
