<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'rider') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'status' => 'required|in:normal,busy,closed',
        ]);

        $user->rider->update(['status' => $validated['status']]);

        return redirect()->route('rider.dashboard')->with('success', 'Status updated successfully!');
    }
}
