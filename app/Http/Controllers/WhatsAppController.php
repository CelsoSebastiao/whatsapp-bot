<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    public function verify(Request $request)
    {
        $verify_token = env('WHATSAPP_VERIFY_TOKEN');

        if ($request->hub_mode === 'subscribe' &&
            $request->hub_verify_token === $verify_token) {

            return response($request->hub_challenge, 200);
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
        $token = env('WHATSAPP_TOKEN');
        $phoneId = env('WHATSAPP_PHONE_ID');

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
