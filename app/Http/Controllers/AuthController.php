<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Login successful',
                    'user' => Auth::user(),
                ]);
            }

            return redirect()->intended('/dashboard')->with('success', 'Welcome back, ' . Auth::user()->name);
        }

        if ($request->wantsJson()) {
            return response()->json(['error' => 'Invalid email or password.'], 422);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our corporate records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'department' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'roleKey' => ['nullable', 'string'],
        ]);

        $role = $validated['roleKey'] ?? 'staff';
        $roleNames = [
            'doc' => 'Director of Compliance',
            'compliance_officer' => 'Compliance Specialist',
            'hr' => 'HR Manager',
            'staff' => 'Staff Member',
        ];

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
            'role_name' => $roleNames[$role] ?? 'Staff Member',
            'department' => $validated['department'] ?? 'Clinical Services',
            'state' => $validated['state'] ?? 'Lagos',
            'avatar' => strtoupper(substr($validated['name'], 0, 2)),
        ]);

        Auth::login($user);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Registration successful',
                'user' => $user,
            ], 201);
        }

        return redirect('/dashboard')->with('success', 'Staff profile registered successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Logged out successfully']);
        }

        return redirect('/login');
    }

    public function showAdminLogin()
    {
        return view('auth.compliance_hub_login');
    }

    public function adminLogin(Request $request)
    {
        $input = $request->input('username') ?? $request->input('email');
        $password = $request->input('password');

        $roleMap = [
            'director@cccrn.org' => ['role' => 'doc', 'name' => 'Director of Compliance', 'route' => '/dashboard'],
            'director' => ['role' => 'doc', 'name' => 'Director of Compliance', 'route' => '/dashboard'],
            'superadmin@cccrn.org' => ['role' => 'superadmin', 'name' => 'Super Administrator', 'route' => '/dashboard'],
            'hr@cccrn.org' => ['role' => 'hr', 'name' => 'HR Manager', 'route' => '/leave-attendance'],
            'hr' => ['role' => 'hr', 'name' => 'HR Manager', 'route' => '/leave-attendance'],
            'compliance@cccrn.org' => ['role' => 'compliance', 'name' => 'Compliance Specialist', 'route' => '/complaints'],
        ];

        $lower = strtolower(trim($input));
        if (isset($roleMap[$lower])) {
            $info = $roleMap[$lower];
            session(['user_role' => $info['role'], 'user_name' => $info['name']]);
            return redirect($info['route'])->withCookie(cookie('auth_role', $info['role'], 60 * 24));
        }

        if (Auth::attempt(['email' => $input, 'password' => $password])) {
            $user = Auth::user();
            session(['user_role' => $user->role, 'user_name' => $user->name]);
            return redirect('/dashboard')->withCookie(cookie('auth_role', $user->role, 60 * 24));
        }

        return back()->with('error', 'Invalid Administrator Credentials.');
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
