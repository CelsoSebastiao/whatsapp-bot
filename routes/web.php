<?php
use Illuminate\Support\Facades\Http;

Route::get('/teste-whatsapp', function() {
    $token = env('WHATSAPP_TOKEN');
    $phoneId = env('WHATSAPP_PHONE_ID');
    $numeroDestino = '258841509766'; // número real do destinatário

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Content-Type' => 'application/json',
    ])->post("https://graph.facebook.com/v17.0/$phoneId/messages", [
        'messaging_product' => 'whatsapp',
        'to' => $numeroDestino,
        'type' => 'text',
        'text' => ['body' => 'Olá! Teste de mensagem via WhatsApp API.']
    ]);

    return $response->json();
});


