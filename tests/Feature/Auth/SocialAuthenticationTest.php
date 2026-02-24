<?php

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    if (Schema::hasTable('roles')) {
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
    }
});

test('it creates a user with a unique username from google', function () {
    $googleUser = Mockery::mock(SocialiteUser::class);
    $googleUser->shouldReceive('getId')->andReturn('google-id-123');
    $googleUser->shouldReceive('getName')->andReturn('John Doe');
    $googleUser->shouldReceive('getEmail')->andReturn('john@example.com');
    $googleUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');

    Socialite::shouldReceive('driver')->with('google')->andReturnSelf();
    Socialite::shouldReceive('stateless')->andReturnSelf();
    Socialite::shouldReceive('user')->andReturn($googleUser);

    $response = $this->get('/auth/google/callback');

    $response->assertRedirect(route('home'));
    
    $user = User::where('email', 'john@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->google_id)->toBe('google-id-123')
        ->and($user->username)->toBe('johndoe');
    
    $this->assertAuthenticatedAs($user);
});

test('it handles duplicate usernames by appending a counter', function () {
    // Create an existing user with 'johndoe' username
    User::factory()->create(['username' => 'johndoe', 'email' => 'existing@example.com']);

    $googleUser = Mockery::mock(SocialiteUser::class);
    $googleUser->shouldReceive('getId')->andReturn('google-id-456');
    $googleUser->shouldReceive('getName')->andReturn('John Doe');
    $googleUser->shouldReceive('getEmail')->andReturn('john2@example.com');
    $googleUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar2.jpg');

    Socialite::shouldReceive('driver')->with('google')->andReturnSelf();
    Socialite::shouldReceive('stateless')->andReturnSelf();
    Socialite::shouldReceive('user')->andReturn($googleUser);

    $this->get('/auth/google/callback');

    $user = User::where('email', 'john2@example.com')->first();
    expect($user->username)->toBe('johndoe1');
});
