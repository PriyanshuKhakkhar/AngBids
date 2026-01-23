@extends('website.layouts.dashboard')

@section('title', 'Winning Items | LaraBids')

@section('content')

<h2 class="h3 text-white fw-bold mb-4">Won Items</h2>

<div class="glass-panel p-4">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0" style="--bs-table-bg: transparent;">
            <thead class="text-secondary small">
                <tr>
                    <th class="border-secondary border-opacity-10 py-3">ITEM</th>
                    <th class="border-secondary border-opacity-10 py-3">WINNING BID</th>
                    <th class="border-secondary border-opacity-10 py-3">WON DATE</th>
                    <th class="border-secondary border-opacity-10 py-3">PAYMENT STATUS</th>
                    <th class="border-secondary border-opacity-10 py-3 text-end">ACTION</th>
                </tr>
            </thead>
            <tbody class="text-white align-middle">
                <tr>
                    <td colspan="5" class="text-center py-5 text-secondary">
                        <i class="fas fa-trophy fs-1 mb-3 d-block opacity-25"></i>
                        No winning items yet. Keep bidding to win amazing items!
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
