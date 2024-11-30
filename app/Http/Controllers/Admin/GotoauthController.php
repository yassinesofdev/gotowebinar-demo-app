<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gotoauth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;


class GotoauthController extends Controller
{
    public function edit() {
        
        $gotoauth = Auth::user()->find(1)->gotoauth;
        return view('admin.gotoauth.edit', compact('gotoauth'));
    }

    public function update(Request $request) {

        //Validate the input
        $validated = $request->validate([
            'client_id' => 'required',
            'client_secret' => 'required',
            'organizer_key' => 'required'
        ]);
        
        $gotoauth = Auth::user()->find(1)->gotoauth;

        if (!$gotoauth) {
            $gotoauth = new Gotoauth();
            $gotoauth->user_id = Auth::user()->id;
        }

        $gotoauth->client_id = $validated['client_id'];
        $gotoauth->client_secret = $validated['client_secret'];
        $gotoauth->organizer_key = $validated['organizer_key'];
        $gotoauth->save();

        return redirect()->route('gotoauth.edit');
    }

    public function getAccess() {

        //check if the credentials are setup
        $gotoauth = Auth::user()->find(1)->gotoauth;
        if (!$gotoauth)  return redirect()->route('gotoauth.edit');

        // Build the authorization URL
        $url = "https://authentication.logmeininc.com/oauth/authorize?response_type=code&client_id={$gotoauth->client_id}";

        // Redirect user to GoToWebinar authorization page
        return Redirect::to($url);
    }

    public function refreshToken() {

        //check if the credentials are setup
        $gotoauth = Auth::user()->find(1)->gotoauth;
        
        if (!$gotoauth)  return redirect()->route('gotoauth.edit');

        define('CLIENT_ID', $gotoauth->client_id);
        define('CLIENT_SECRET', $gotoauth->client_secret);
        define('CLIENT_REFRESH_TOKEN', $gotoauth->refresh_token);

        // Create the Base64-encoded Authorization token
        $authToken = base64_encode(CLIENT_ID . ':' . CLIENT_SECRET);

        // Make the API call to refresh the token
        $response = Http::asForm()->withHeaders([
            'Authorization' => "Basic {$authToken}",
        ])->post('https://authentication.logmeininc.com/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => CLIENT_REFRESH_TOKEN,
        ]);

        // Check if the response is successful
        if ($response->successful()) {
            $newTokenData = $response->json();

            // Handle and store the new tokens securely
            $gotoauth->access_token = $newTokenData['access_token'];
            if (isset($newTokenData['refresh_token'])) $gotoauth->refresh_token = $newTokenData['refresh_token'];
            
            $gotoauth->save();
        }

        return redirect()->route('webinar.index');
    }

    public function OAuthCallback(Request $request) {

        //check if the credentials are setup
        $gotoauth = Auth::user()->find(1)->gotoauth;
        if (!$gotoauth)  return redirect()->route('gotoauth.edit');

        define('CLIENT_ID', $gotoauth->client_id);
        define('CLIENT_SECRET', $gotoauth->client_secret);

        // Get the authorization code from the request
        $authorizationCode = $request->input('code');

        // Prepare the Basic Authentication header
        $authHeader = base64_encode(CLIENT_ID . ':' . CLIENT_SECRET);

        // Prepare the token request data
        $data = [
            'grant_type' => 'authorization_code',
            //'redirect_uri' => env('GOTOWEBINAR_REDIRECT_URI'),
            'client_id' => CLIENT_ID,
            'code' => $authorizationCode,
            'scope' => 'collab'
        ];

        // Make the POST request to exchange the authorization code for an access token
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $authHeader,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])
        ->asForm()
        ->post('https://authentication.logmeininc.com/oauth/token', $data);

        // Check if the response was successful
        if ($response->successful()) {

            $gotoauth->access_token = $response->json('access_token');
            $gotoauth->refresh_token = $response->json('refresh_token');
            $gotoauth->token_type = $response->json('token_type');
            $gotoauth->expires_in = $response->json('expires_in');
            $gotoauth->scope = $response->json('scope');
            $gotoauth->principal = $response->json('principal');
            $gotoauth->save();
        }

        return redirect()->route('webinar.index');
    }
}
