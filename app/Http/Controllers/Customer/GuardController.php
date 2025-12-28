<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GuardController extends Controller
{
    /**
     * Display customer dashboard with guard management.
     */
    public function index()
    {
        $customer = Auth::user();

        // Get customer's guards
        $guards = User::where('customer_id', $customer->id)
            ->where('role', 'guard')
            ->orderBy('name')
            ->get();

        return view('customer.dashboard', compact('customer', 'guards'));
    }

    /**
     * Show create guard form.
     */
    public function create()
    {
        $customer = Auth::user();

        return view('customer.guards.create', compact('customer'));
    }

    /**
     * Store new guard.
     */
    public function store(Request $request)
    {
        $customer = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $customer->id,
            'mobile_number' => 'nullable|string|max:15|unique:users,mobile_number,' . $customer->id,
            'password' => 'required|string|min:8',
        ], [
            'email.unique' => 'This email is already taken.',
            'mobile_number.unique' => 'This mobile number is already taken.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        // Create guard user
        $guard = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
            'role' => 'guard',
            'customer_id' => $customer->id,
        ]);

        return redirect()
            ->route('customer.guards.show', $guard->id)
            ->with('success', sprintf('Guard %s has been created successfully.', $guard->name));
    }

    /**
     * Show guard details.
     */
    public function show($id)
    {
        $customer = Auth::user();
        $guard = User::where('id', $id)
            ->where('customer_id', $customer->id)
            ->where('role', 'guard')
            ->firstOrFail();

        return view('customer.guards.show', compact('customer', 'guard'));
    }

    /**
     * Show edit guard form.
     */
    public function edit($id)
    {
        $customer = Auth::user();
        $guard = User::where('id', $id)
            ->where('customer_id', $customer->id)
            ->where('role', 'guard')
            ->firstOrFail();

        return view('customer.guards.edit', compact('customer', 'guard'));
    }

    /**
     * Update guard.
     */
    public function update(Request $request, $id)
    {
        $customer = Auth::user();
        $guard = User::where('id', $id)
            ->where('customer_id', $customer->id)
            ->where('role', 'guard')
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $guard->id,
            'mobile_number' => 'nullable|string|max:15|unique:users,mobile_number,' . $guard->id,
            'password' => 'nullable|string|min:8',
        ], [
            'email.unique' => 'This email is already taken.',
            'mobile_number.unique' => 'This mobile number is already taken.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        $guard->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' => $request->filled('password') ? Hash::make($request->password) : $guard->password,
        ]);

        return redirect()
            ->route('customer.guards.show', $guard->id)
            ->with('success', sprintf('Guard %s has been updated successfully.', $guard->name));
    }

    /**
     * Delete guard.
     */
    public function destroy($id)
    {
        $customer = Auth::user();
        $guard = User::where('id', $id)
            ->where('customer_id', $customer->id)
            ->where('role', 'guard')
            ->firstOrFail();

        // Check if guard has any entries
        $hasEntries = \App\Models\Entry::where('guard_id', $guard->id)->exists();

        if ($hasEntries) {
            return back()->withErrors([
                'error' => 'Cannot delete guard. They have existing entries. Please delete their entries first.'
            ]);
        }

        // Delete guard
        $guard->delete();

        return redirect()
            ->route('customer.guards.index')
            ->with('success', sprintf('Guard %s has been deleted successfully.', $guard->name));
    }
}
