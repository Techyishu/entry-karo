<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the customer dashboard.
     */
    public function index()
    {
        $customer = Auth::user();

        // Get all guards belonging to this customer
        $guards = $customer->guards()->with('entries')->get();

        return view('customer.dashboard', [
            'customer' => $customer,
            'guards' => $guards,
        ]);
    }
}

