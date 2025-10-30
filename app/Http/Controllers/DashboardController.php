<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // Redirect to appropriate dashboard based on role
        if ($user->role === 'buyer') {
            return redirect()->route('buyer.dashboard');
        } elseif ($user->role === 'seller') {
            return redirect()->route('seller.dashboard');
        } elseif ($user->role === 'rider') {
            return redirect()->route('rider.dashboard');
        }

        return redirect('/');
    }
}
