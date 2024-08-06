<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pages.auth.login');
    }

    /**
     * Handle user login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate the request input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Initialize Guzzle client
            $client = new Client();
            
            // Send POST request to external API for login
            $response = $client->request('POST', 'http://103.175.217.148/user/login', [
                'json' => [
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                ]
            ]);

            // Decode response content
            $data = json_decode($response->getBody()->getContents(), true);

            // Check if login was successful
            if (isset($data['msg']) && $data['msg'] === 'Berhasil Login') {
                // Store user data in session
                Session::put([
                    'logged_in' => true,
                    'user_id' => $data['data']['_id'],
                    'nama' => $data['data']['namalengkap'],
                    'username' => $data['data']['username'],
                ]);

                // Regenerate session ID to prevent session fixation attacks
                $request->session()->regenerate();

                // Redirect to dashboard route or URL
                return redirect('dashboard');
            } else {
                // Redirect back with error message from API response
                return redirect()->back()->withErrors(['message' => $data['msg']]);
            }
        } catch (\Exception $e) {
            // Handle exceptions (e.g., network issues) gracefully
            return redirect()->back()->withErrors(['message' => 'An error occurred during the login process. Please try again later.']);
        }
    }
}
