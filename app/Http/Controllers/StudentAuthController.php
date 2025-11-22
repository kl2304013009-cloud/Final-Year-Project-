<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('students.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        // Cikgu/Admin login (guna email)
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $login, 'password' => $password])) {
                return redirect()->intended('/dashboard');
            }
        }

        // Student login (guna student_id)
        if (Auth::guard('student')->attempt(['student_id' => $login, 'password' => $password])) {
            return redirect()->intended(route('students.dashboard'));
        }

        return back()->withErrors(['login' => 'Invalid login credentials']);
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        Auth::logout(); // Logout guard default (admin/cikgu) juga
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('student.login');
    }
}
