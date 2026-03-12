<?php

use App\Models\User;
use App\Models\Auction;
use App\Models\Kyc;
use App\Models\AuctionRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user must be kyc approved to register for an auction', function () {
    $user = User::factory()->create();
    $auction = Auction::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('user.auctions.register', $auction->id));

    $response->assertRedirect(route('user.kyc.form'));
    $response->assertSessionHas('error', 'You must complete your Identity Verification (KYC) before you can register for an auction.');
    $this->assertDatabaseMissing('auction_registrations', [
        'user_id' => $user->id,
        'auction_id' => $auction->id,
    ]);
});

test('kyc approved user can register for an auction', function () {
    $user = User::factory()->create();
    Kyc::factory()->create(['user_id' => $user->id, 'status' => 'approved']);
    $auction = Auction::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('user.auctions.register', $auction->id));

    $response->assertRedirect();
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('auction_registrations', [
        'user_id' => $user->id,
        'auction_id' => $auction->id,
        'status' => 'registered'
    ]);
});

test('unregistered user cannot place a bid', function () {
    $user = User::factory()->create();
    Kyc::factory()->create(['user_id' => $user->id, 'status' => 'approved']);
    $auction = Auction::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('auctions.bid', $auction->id), [
            'amount' => 1100,
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('error', 'You must register for this auction before you can place a bid.');
});

test('registered user can place a bid', function () {
    $user = User::factory()->create();
    Kyc::factory()->create(['user_id' => $user->id, 'status' => 'approved']);
    $auction = Auction::factory()->create();
    
    // Register the user
    AuctionRegistration::create([
        'user_id' => $user->id,
        'auction_id' => $auction->id,
    ]);

    $response = $this->actingAs($user)
        ->post(route('auctions.bid', $auction->id), [
            'increment' => 100,
        ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('bids', [
        'user_id' => $user->id,
        'auction_id' => $auction->id,
    ]);
});
