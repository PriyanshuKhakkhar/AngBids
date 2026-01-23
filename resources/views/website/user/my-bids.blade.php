@extends('website.layouts.dashboard')

@section('title', 'My Bids | LaraBids')

@section('content')

<h2 class="h3 text-white fw-bold mb-4">My Active Bids</h2>

<div class="glass-panel p-4">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0" style="--bs-table-bg: transparent;">
            <thead class="text-secondary small">
                <tr>
                    <th class="border-secondary border-opacity-10 py-3">AUCTION ITEM</th>
                    <th class="border-secondary border-opacity-10 py-3">MY BID</th>
                    <th class="border-secondary border-opacity-10 py-3">CURRENT BID</th>
                    <th class="border-secondary border-opacity-10 py-3">STATUS</th>
                    <th class="border-secondary border-opacity-10 py-3">TIME LEFT</th>
                    <th class="border-secondary border-opacity-10 py-3 text-end">ACTION</th>
                </tr>
            </thead>
            <tbody class="text-white align-middle">
                <tr>
                    <td colspan="6" class="text-center py-5 text-secondary">
                        <i class="fas fa-gavel fs-1 mb-3 d-block opacity-25"></i>
                        You haven't placed any bids yet. <a href="{{ route('auctions.index') }}" class="text-gold">Start Bidding</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
