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
    
    echo "<h1>Database Debug</h1>";
    echo "<h2>Config (pgsql)</h2>";
    dump($config);
    
    echo "<h2>Environment Variables</h2>";
    dump([
        'DB_CONNECTION' => env('DB_CONNECTION'),
        'DB_HOST' => env('DB_HOST'),
        'DB_PORT' => env('DB_PORT'),
        'DB_DATABASE' => env('DB_DATABASE'),
        'DB_USERNAME' => env('DB_USERNAME'),
        'DATABASE_URL' => env('DATABASE_URL'),
    ]);

    echo "<h2>Connection Test</h2>";
    try {
        DB::connection()->getPdo();
        return "Connected successfully to database: " . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return "Could not connect to the database.  Please check your configuration. error:" . $e->getMessage();
    }
});


