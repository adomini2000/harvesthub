<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use App\Models\Rider;
use App\Models\BuyerPoint;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:buyer,seller,rider'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            // Seller fields
            'shop_name' => ['required_if:role,seller', 'nullable', 'string', 'max:255'],
            'shop_description' => ['required_if:role,seller', 'nullable', 'string', 'max:1000'],
            // Rider fields
            'vehicle_type' => ['required_if:role,rider', 'nullable', 'in:bike,motorcycle,car'],
            'license_number' => ['nullable', 'string', 'max:50'],
        ]);
    }

    protected function create(array $data)
    {
        // Buyers are auto-approved, sellers and riders need admin approval
        $isApproved = $data['role'] === 'buyer' ? true : false;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'is_approved' => $isApproved,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        // Create role-specific records
        if ($data['role'] === 'seller') {
            Seller::create([
                'user_id' => $user->id,
                'shop_name' => $data['shop_name'] ?? $data['name'] . "'s Shop",
                'shop_description' => $data['shop_description'] ?? 'Welcome to my shop!',
                'subscription_paid' => false, // Requires subscription until approved
            ]);
        } elseif ($data['role'] === 'rider') {
            $vehicleType = $data['vehicle_type'] ?? 'bike';
            Rider::create([
                'user_id' => $user->id,
                'vehicle_type' => $vehicleType,
                'license_number' => $data['license_number'] ?? null,
                'max_capacity_kg' => $this->getCapacityByVehicle($vehicleType),
                'status' => 'closed', // Default to closed until approved
            ]);
        } elseif ($data['role'] === 'buyer') {
            BuyerPoint::create([
                'buyer_id' => $user->id,
                'total_points' => 0,
            ]);
        }

        return $user;
    }

    private function getCapacityByVehicle($vehicleType)
    {
        $capacities = [
            'bike' => 10.00,
            'motorcycle' => 25.00,
            'car' => 50.00,
        ];

        return $capacities[$vehicleType] ?? 10.00;
    }

    /**
     * Override registered method to redirect based on approval status
     */
    protected function registered(Request $request, $user)
    {
        // Buyers go directly to dashboard
        if ($user->isBuyer()) {
            return redirect()->route('buyer.dashboard')
                ->with('success', 'Welcome to HarvestHub! Start shopping now.');
        }

        // Sellers and Riders need to wait for approval
        if ($user->isSeller() || $user->isRider()) {
            // Log them out since they're not approved yet
            auth()->logout();

            return redirect()->route('login')
                ->with('info', 'Your account has been created and is pending admin approval. You will be able to login once approved (usually 24-48 hours).');
        }

        // Default fallback
        return redirect()->route('dashboard');
    }
}
