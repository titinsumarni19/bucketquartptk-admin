<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        $client = new Client();
        $url = 'http://103.175.217.148/user/login';

        try {
            $response = $client->post($url, [
                'json' => [
                    'username' => $username,
                    'password' => $password
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['sukses'] && $data['sukses']) {
                Session::put('userId', $data['data']['_id']);
                return redirect()->route('dashboard');
            } else {
                return back()->with('error', $data['msg']);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to authenticate: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        // Clear user session
        Session::forget('user');
        
        // Redirect to login page
        return redirect()->route('login');
    }
}
