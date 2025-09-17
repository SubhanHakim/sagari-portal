<?php

namespace App\Http\Controllers;

use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class SsoController extends Controller
{
    public function redirectToApp()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with("error", "You must be logged in to access this application.");
        }

        $sessionData = [
            'user_id'    => $user->id,
            'user_name'  => $user->name,
            'role'    => $user->role,
            'role_name'  => $user->role->name ?? null,
            'position'   => $user->position ?? null,
            'jabatan'    => $user->jabatan ?? null,
            'user_photo' => $user->user_photo ?? null,
        ];

        $encryptionKey = env("API_ECRYPTION_AUTH");
        $hmackey       = env("API_SECRET_AUTH");

        if (!$encryptionKey || !$hmackey) {
            abort(500, "Encryption keys are not set.");
        }

        $encryptionKey = Str::after($encryptionKey, "base64:");
        $hmackey       = Str::after($hmackey, "base64:");

        $encryptionKeyDecoded = base64_decode($encryptionKey, true);
        $hmackeyDecoded       = base64_decode($hmackey, true);

        if ($encryptionKeyDecoded === false || strlen($encryptionKeyDecoded) !== 32) {
            abort(500, "Invalid encryption key.");
        }

        if ($hmackeyDecoded === false || strlen($hmackeyDecoded) === 0) {
            abort(500, "Invalid HMAC key.");
        }

        $payloadJsonForSig = json_encode($sessionData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $signature = hash_hmac('sha256', $payloadJsonForSig, $hmackeyDecoded);

        $payload = [
            "data"      => $sessionData,
            "signature" => $signature
        ];


        $encrypter = new Encrypter($encryptionKeyDecoded, "AES-256-CBC");
        $encryptedPayload = $encrypter->encryptString(
            json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
        
        $response = Http::withHeaders([
            'X-Payload'   => $encryptedPayload,
            'Accept'      => 'application/json',
        ])->post(env('API_PORTAL_WEB'));

        

        if ($response->failed()) {
            return redirect()->route('home')->with('error', 'Gagal SSO ke aplikasi tujuan: ' . $response->body());
        }

        $data = $response->json();

        // Pastikan aplikasi tujuan balikin token/callback url
        if (!isset($data['redirect_url'])) {
            return redirect()->route('home')->with('error', 'Aplikasi tujuan tidak mengembalikan redirect URL.');
        }

        // 🚀 Redirect user ke aplikasi tujuan dengan token
        return redirect()->away($data['redirect_url']);
    }
}
