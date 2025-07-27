<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserPanelController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        return view('userpanel::dashboard', compact('user'));
    }

    /**
     * Display user settings.
     */
    public function settings()
    {
        $user = Auth::user();
        return view('userpanel::settings', compact('user'));
    }

    /**
     * Update user settings.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Settings updated successfully');
    }

    /**
     * Display subscription information.
     */
    public function subscription()
    {
        $user = Auth::user();
        // Mock subscription data - replace with actual subscription logic
        $subscription = [
            'plan' => 'Pro',
            'status' => 'active',
            'next_billing' => now()->addMonth(),
            'features' => [
                'Unlimited Projects',
                'Advanced Analytics',
                'Priority Support',
                'Custom Branding'
            ]
        ];
        
        return view('userpanel::subscription', compact('user', 'subscription'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('userpanel.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('userpanel::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('userpanel::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('userpanel::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource in storage.
     */
    public function destroy($id) {}
}
