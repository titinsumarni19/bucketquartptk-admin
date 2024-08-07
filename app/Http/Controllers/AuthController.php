<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
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
            $user = $data['data']['role'];

            if (isset($data['sukses']) && $data['msg'] == "Berhasil Login" && $user == 2) {
                Session::put([
                    'logged_in' => true,
                    'user_id' => $data['data']['_id'],
                    'username' => $data['data']['username'],
                    'name' => $data['data']['namalengkap'],
                    'phone' => $data['data']['telepon'],
                    'role' => $data['data']['role'],
                ]);

                $request->session()->regenerate();
                return redirect()->route('dashboard');
            } else {
                return back()->with('error', $data['msg']);
            }
        } catch (\Exception $e) {
            \Log::error('Login Error:', ['exception' => $e]);
            return back()->with('error', 'Failed to authenticate: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        $request->session()->regenerate();
        return redirect()->route('login');
    }
}
