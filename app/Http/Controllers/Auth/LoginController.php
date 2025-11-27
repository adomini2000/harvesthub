<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Override the authenticated method to check approval status
     */
    protected function authenticated(Request $request, $user)
    {
        // Admin can always login
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome back, Admin!');
        }

        // Buyers are auto-approved
        if ($user->isBuyer()) {
            return redirect()->route('buyer.dashboard')
                ->with('success', 'Welcome back!');
        }

        // Check if seller/rider is approved
        if (!$user->isApproved()) {
            Auth::logout(); // Log them out
            return redirect()->route('login')
                ->with('warning', 'Your account is pending admin approval. Please wait for approval.');
        }

        // Approved sellers and riders
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Redirect based on user role
     */
    private function redirectBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isBuyer()) {
            return redirect()->route('buyer.dashboard');
        } elseif ($user->isSeller()) {
            return redirect()->route('seller.dashboard');
        } elseif ($user->isRider()) {
            return redirect()->route('rider.dashboard');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Override logout to redirect to login with message
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}
