<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        
        $response = Http::post('http://103.175.217.148/user/login', [
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ]);

        $responseData = $response->json();

        if ($response->ok() && $responseData['sukses']) {
            $user = $responseData['data']; // Assuming the user data is returned in 'data'

            // Check if the user's role is 2 or 3
            if (in_array($user['role'], [2, 3])) {
                // Handle successful login and redirect
                return redirect()->route('dashboard')->with('success', $responseData['msg']);
            } else {
                // Handle role not authorized
                return redirect()->back()->withErrors(['login_error' => 'You do not have permission to access this area']);
            }
        } else {
            // Handle login failure
            return redirect()->back()->withErrors(['login_error' => $responseData['msg']]);
        }
    }
}
