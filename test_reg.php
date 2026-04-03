<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::create(
        '/register',
        'POST',
        [
            'name' => 'John Doe',
            'username' => 'johndoe123',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'otp' => '123456',
        ]
    )
);

echo "Status: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() == 302) {
    if (session()->has('errors')) {
        echo "Errors: " . json_encode(session('errors')->getMessages(), JSON_PRETTY_PRINT) . "\n";
    }
}
