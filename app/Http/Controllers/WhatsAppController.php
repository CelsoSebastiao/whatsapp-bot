<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    public function verify(Request $request)
    {
        $verify_token = config('services.whatsapp.verify_token');
        $mode = $request->input('hub_mode') ?? $request->input('hub.mode');
        $token = $request->input('hub_verify_token') ?? $request->input('hub.verify_token');
        $challenge = $request->input('hub_challenge') ?? $request->input('hub.challenge');

        Log::info('Webhook Verification Request:', [
            'mode' => $mode,
            'token' => $token,
            'challenge' => $challenge,
            'expected_token' => $verify_token,
        ]);

        if ($mode === 'subscribe' && $token === $verify_token) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response('Unauthorized', 403);
    }

    public function handle(Request $request)
    {
        Log::info($request->all());

        $message = $request['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'] ?? null;
        $from = $request['entry'][0]['changes'][0]['value']['messages'][0]['from'] ?? null;

        if ($message && $from) {
            $this->sendMessage($from, "Recebi sua mensagem: " . $message);
        }

        return response()->json(['status' => 'ok']);
    }

    private function sendMessage($to, $text)
    {
        $token = config('services.whatsapp.token');
        $phoneId = config('services.whatsapp.phone_id');

        Http::withToken($token)->post(
            "https://graph.facebook.com/v18.0/{$phoneId}/messages",
            [
                "messaging_product" => "whatsapp",
                "to" => $to,
                "type" => "text",
                "text" => ["body" => $text]
            ]
        );
    }
}
