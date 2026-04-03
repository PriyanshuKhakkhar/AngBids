$request = \Illuminate\Http\Request::create(
    '/register', 'POST',
    ['name' => 'John Doe', 'username' => 'johndoe', 'email' => 'john@ex.com', 'password' => 'Pass!234', 'password_confirmation' => 'Pass!234', 'otp' => '123456']
);
$controller = app(\App\Http\Controllers\Auth\RegisteredUserController::class);
try {
    $controller->store($request);
} catch (\Illuminate\Validation\ValidationException $e) {
    dump("VALIDATION FAIL:");
    dump($e->errors());
} catch (\Exception $e) {
    dump("ERROR:");
    dump($e->getMessage());
}
