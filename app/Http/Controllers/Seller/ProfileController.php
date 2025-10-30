<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'seller') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->seller->update($validated);

        return redirect()->route('seller.dashboard')->with('success', 'Profile updated successfully!');
    }
}
