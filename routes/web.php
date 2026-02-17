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
    
    $result = [
        'message' => 'Attempting connection...',
        'config_loaded' => $config,
        'connection_status' => 'PENDING',
        'error_message' => null,
    ];

    try {
        DB::connection()->getPdo();
        $result['connection_status'] = 'SUCCESS';
        $result['database_name'] = DB::connection()->getDatabaseName();
        $result['message'] = 'Connected successfully!';
    } catch (\Exception $e) {
        $result['connection_status'] = 'FAILED';
        $result['error_message'] = $e->getMessage();
        $result['message'] = 'Connection failed.';
    }

    return response()->json($result);
});

Route::get('/debug-db-2', function() {
    $config = config('database.connections.pgsql');
    // Hide password
    $config['password'] = '********';
    
    $result = [
        'message' => 'Attempting connection (v2)...',
        'config_loaded' => $config,
        'connection_status' => 'PENDING',
        'error_message' => null,
    ];

    try {
        DB::connection()->getPdo();
        $result['connection_status'] = 'SUCCESS';
        $result['database_name'] = DB::connection()->getDatabaseName();
        $result['message'] = 'Connected successfully!';
    } catch (\Exception $e) {
        $result['connection_status'] = 'FAILED';
        $result['error_message'] = $e->getMessage();
        $result['message'] = 'Connection failed.';
    }

    return response()->json($result);
});


