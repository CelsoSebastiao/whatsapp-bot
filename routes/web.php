<?php
use Illuminate\Support\Facades\Http;
// routes/web.php
Route::get('/rodar-migrate', function() {
    set_time_limit(300); // 5 minutes execution time
    Artisan::call('migrate', ['--force' => true]);
    return "Migrations rodadas com sucesso!";
});


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

Route::get('/debug-db', function() {
    $config = config('database.connections.pgsql');
    // Hide password
    $config['password'] = '********';
    
    return response()->json([
        'message' => 'Config loaded successfully. Connection NOT attempted.',
        'config_focused' => $config,
        'environment_vars' => [
            'DB_ CONNECTION' => env('DB_CONNECTION'),
            'DB_HOST' => env('DB_HOST'),
            'DB_PORT' => env('DB_PORT'),
            'DB_DATABASE' => env('DB_DATABASE'),
            'DB_USERNAME' => env('DB_USERNAME'),
            'DATABASE_URL' => env('DATABASE_URL'),
            'APP_ENV' => env('APP_ENV'),
        ],
        'server_env' => [
            // Check if actual server env vars are set
            'DB_HOST' => $_SERVER['DB_HOST'] ?? $_ENV['DB_HOST'] ?? 'NOT_SET',
        ]
    ]);
});


