<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function index()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        // Validate the request input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $client = new Client();
            $response = $client->request('POST', 'http://103.175.217.148/user/login', [
                'json' => [
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['msg']) && $data['msg'] == 'Berhasil Login') {
                Session::put([
                    'logged_in' => true,
                    'user_id' => $data['data']['_id'],
                    'nama' => $data['data']['namalengkap'],
                    'username' => $data['data']['username'],
                ]);
                $request->session()->regenerate();
                return redirect('dashboard');
            } else {
                return redirect()->back()->withErrors(['message' => $data['msg']]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'An error occurred during the login process. Please try again later.']);
        }
    }
}
