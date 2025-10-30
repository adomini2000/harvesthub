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
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        // Create role-specific records
        if ($data['role'] === 'seller') {
            Seller::create([
                'user_id' => $user->id,
                'shop_name' => $data['shop_name'] ?? $data['name'] . "'s Shop",
                'shop_description' => $data['shop_description'] ?? 'Welcome to my shop!',
                'subscription_paid' => true, // Mock subscription
            ]);
        } elseif ($data['role'] === 'rider') {
            $vehicleType = $data['vehicle_type'] ?? 'bike';
            Rider::create([
                'user_id' => $user->id,
                'vehicle_type' => $vehicleType,
                'max_capacity_kg' => $this->getCapacityByVehicle($vehicleType),
                'status' => 'normal',
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
        if ($vehicleType === 'bike') {
            return 10.00;
        } elseif ($vehicleType === 'motorcycle') {
            return 25.00;
        } elseif ($vehicleType === 'car') {
            return 50.00;
        }
        return 10.00;
    }

    protected function registered(Request $request, $user)
    {
        return redirect()->route('dashboard');
    }
}
