@extends('website.layouts.dashboard')

@section('title', 'My Bids | LaraBids')

@section('content')

<h2 class="h3 text-dark fw-bold mb-4">My Active Bids</h2>

<div class="card card-elite p-0 overflow-hidden shadow-sm border-light">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="border-light py-3 ps-4 text-xs font-weight-bold text-gray-600 text-uppercase small">AUCTION ITEM</th>
                    <th class="border-light py-3 text-xs font-weight-bold text-gray-600 text-uppercase small">MY BID</th>
                    <th class="border-light py-3 text-xs font-weight-bold text-gray-600 text-uppercase small">CURRENT BID</th>
                    <th class="border-light py-3 text-xs font-weight-bold text-gray-600 text-uppercase small">STATUS</th>
                    <th class="border-light py-3 text-xs font-weight-bold text-gray-600 text-uppercase small">TIME LEFT</th>
                    <th class="border-light py-3 pe-4 text-end text-xs font-weight-bold text-gray-600 text-uppercase small">ACTION</th>
                </tr>
            </thead>
            <tbody class="align-middle bg-white">
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fas fa-gavel fs-1 mb-3 d-block text-gray-300 opacity-25"></i>
                        <span class="text-secondary small">You haven't placed any bids yet.</span>
                        <div class="mt-2">
                             <a href="{{ route('auctions.index') }}" class="btn btn-outline-primary btn-sm px-4">Start Bidding</a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


@endsection
