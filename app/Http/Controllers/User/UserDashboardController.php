<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Contact;
use Yajra\DataTables\Facades\DataTables;

class UserDashboardController extends Controller
{
    // Dashboard
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'active_bids'      => $user->bids()->count(),
            'total_wins'       => $user->getWonAuctionsCount(),
            'watchlist_count'  => $user->watchlist()->count(),
            'messages_count'   => 0,
        ];

        return view('website.user.dashboard', compact('stats'));
    }

    // My bids
    public function myBids()
    {
        return view('website.user.my-bids');
    }

    // My bids data for DataTables
    public function myBidsData()
    {
        try {
            $user = auth()->user();
            
            // Get user's bids, unique by auction_id (showing their latest amount per auction)
            $bids = \App\Models\Bid::where('user_id', $user->id)
                ->with(['auction.category'])
                ->select('*')
                ->whereIn('id', function($query) use ($user) {
                    $query->selectRaw('MAX(id)')
                        ->from('bids')
                        ->where('user_id', $user->id)
                        ->groupBy('auction_id');
                })
                ->latest();

            return datatables()->of($bids)
                ->addColumn('item', function($bid) {
                    $auction = $bid->auction;
                    $image = $auction->image ? asset('storage/' . $auction->image) : 'https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=120';
                    $title = e($auction->title);
                    return '
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                <img src="'.$image.'" class="rounded-3 shadow-sm border" width="55" height="55" style="object-fit: cover;" onerror="this.src=\'https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=120\'">
                            </div>
                            <div>
                                <div class="text-dark fw-bold mb-0 text-truncate" style="max-width: 220px;" title="'.$title.'">'.$title.'</div>
                                <div class="text-muted" style="font-size: 0.75rem;">ID: #'.str_pad($auction->id, 5, '0', STR_PAD_LEFT).'</div>
                            </div>
                        </div>';
                })
                ->addColumn('my_bid', function($bid) {
                    return '<span class="fw-bold text-dark">₹'.number_format($bid->amount, 2).'</span>';
                })
                ->addColumn('current_price', function($bid) {
                    return '<span class="text-primary fw-bold">₹'.number_format($bid->auction->current_price, 2).'</span>';
                })
                ->addColumn('status', function($bid) {
                    $auction = $bid->auction;
                    $status = $auction->status_label;
                    $config = [
                        'Live'          => ['color' => 'success', 'icon' => 'fa-circle-play', 'bg' => 'success'],
                        'Starting Soon' => ['color' => 'info',    'icon' => 'fa-clock',       'bg' => 'info'],
                        'Ended'         => ['color' => 'danger',  'icon' => 'fa-circle-stop', 'bg' => 'danger'],
                        'Pending'       => ['color' => 'warning', 'icon' => 'fa-hourglass-start', 'bg' => 'warning'],
                        'Closed'        => ['color' => 'secondary','icon' => 'fa-lock',        'bg' => 'secondary'],
                        'Cancelled'     => ['color' => 'dark',     'icon' => 'fa-ban',         'bg' => 'dark'],
                    ];

                    $style = $config[$status] ?? ['color' => 'secondary', 'icon' => 'fa-question-circle', 'bg' => 'secondary'];
                    
                    return '<span class="badge bg-'.$style['bg'].' bg-opacity-10 text-'.$style['color'].' border border-'.$style['color'].' border-opacity-25 rounded-pill px-3 py-2 fw-semibold shadow-sm" style="font-size: 0.75rem; min-width: 100px;">
                                <i class="fas '.$style['icon'].' me-1 small"></i> '.$status.'
                            </span>';
                })
                ->addColumn('time_left', function($bid) {
                    $auction = $bid->auction;
                    if ($auction->status === 'active' && $auction->end_time->isFuture()) {
                        return '<span class="text-secondary small fw-medium">'. $auction->end_time->diffForHumans(null, true) .' left</span>';
                    }
                    return '<span class="text-muted small">Ended</span>';
                })
                ->addColumn('action', function($bid) {
                    $url = route('auctions.show', $bid->auction->id);
                    return '<div class="text-end">
                                <a href="'.$url.'" class="btn btn-icon-elite btn-outline-primary btn-sm rounded-circle" title="View Listing"><i class="fas fa-eye"></i></a>
                            </div>';
                })
                ->rawColumns(['item', 'my_bid', 'current_price', 'status', 'time_left', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // My auctions
    public function myAuctions()
    {
        return view('website.user.my-auctions');
    }

    // My auctions data for DataTables
    public function myAuctionsData()
    {
        try {
            $auctions = \App\Models\Auction::where('user_id', auth()->id())
                ->with(['category', 'bids.user'])
                ->latest();

            return datatables()->of($auctions)
                ->addColumn('item', function($auction) {
                    $image = $auction->image ? (str_starts_with($auction->image, 'http') ? $auction->image : asset('storage/' . $auction->image)) : 'https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=120';
                    $title = e($auction->title);
                    $date = $auction->created_at->format('M d, Y');
                    return '
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                <img src="'.$image.'" class="rounded-3 shadow-sm border" width="55" height="55" style="object-fit: cover;" onerror="this.src=\'https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=120\'">
                            </div>
                            <div>
                                <div class="text-dark fw-bold mb-0 text-truncate" style="max-width: 220px;" title="'.$title.'">'.$title.'</div>
                                <div class="text-muted" style="font-size: 0.75rem;"><i class="far fa-calendar-alt me-1"></i> Listed on '.$date.'</div>
                            </div>
                        </div>';
                })
                ->editColumn('status', function($auction) {
                    $status = $auction->status_label;
                    $config = [
                        'Live'          => ['color' => 'success', 'icon' => 'fa-circle-play', 'bg' => 'success'],
                        'Starting Soon' => ['color' => 'info',    'icon' => 'fa-clock',       'bg' => 'info'],
                        'Ended'         => ['color' => 'danger',  'icon' => 'fa-circle-stop', 'bg' => 'danger'],
                        'Pending'       => ['color' => 'warning', 'icon' => 'fa-hourglass-start', 'bg' => 'warning'],
                        'Closed'        => ['color' => 'secondary','icon' => 'fa-lock',        'bg' => 'secondary'],
                        'Cancelled'     => ['color' => 'dark',     'icon' => 'fa-ban',         'bg' => 'dark'],
                    ];

                    $style = $config[$status] ?? ['color' => 'secondary', 'icon' => 'fa-question-circle', 'bg' => 'secondary'];
                    
                    return '<span class="badge bg-'.$style['bg'].' bg-opacity-10 text-'.$style['color'].' border border-'.$style['color'].' border-opacity-25 rounded-pill px-3 py-2 fw-semibold shadow-sm" style="font-size: 0.75rem; min-width: 100px;">
                                <i class="fas '.$style['icon'].' me-1 small"></i> '.$status.'
                            </span>';
                })
                ->addColumn('price', function($auction) {
                    return '<div>
                                <div class="text-primary fw-bolder fs-6">₹'.number_format($auction->current_price).'</div>
                                <div class="text-muted x-small" style="font-size: 0.7rem;">Current Bid</div>
                            </div>';
                })
                ->addColumn('winner', function($auction) {
                    $highestBid = $auction->highestBid();
                    if ($highestBid && $highestBid->user) {
                        $username = e($highestBid->user->username);
                        $avatar = $highestBid->user->avatar_url;
                        return '
                            <div class="d-flex align-items-center">
                                <img src="'.$avatar.'" class="rounded-circle border border-2 border-white shadow-sm" width="28" height="28" alt="'.$username.'">
                                <span class="ms-2 small fw-bold text-dark">@'.$username.'</span>
                            </div>';
                    }
                    return '<span class="badge bg-light text-muted border rounded-pill px-3 py-1 fw-medium" style="font-size: 0.7rem;">Fixed Price/No Bids</span>';
                })
                ->addColumn('bids', function($auction) {
                    $count = $auction->bids->count();
                    return '<div class="text-center">
                                <div class="fw-bold text-dark">'.$count.'</div>
                                <div class="text-muted" style="font-size: 0.7rem;">Total Bids</div>
                            </div>';
                })
                ->addColumn('action', function($auction) {
                    $status = $auction->status_label;
                    $isWithin24Hours = $auction->created_at && $auction->created_at->diffInHours(now()) <= 24;
                    $canEdit = $auction->end_time->isFuture() && (
                        $auction->status === 'active' || 
                        ($auction->status === 'pending' && $isWithin24Hours)
                    );
                    
                    $viewUrl = route('auctions.show', $auction->id);
                    $editUrl = route('auctions.edit', $auction->id);
                    
                    return '<div class="d-flex justify-content-end gap-2">
                                <a href="'.$viewUrl.'" class="btn btn-icon-elite btn-outline-primary btn-sm rounded-circle" title="View Listing"><i class="fas fa-eye"></i></a>
                                '.($canEdit ? '<a href="'.$editUrl.'" class="btn btn-icon-elite btn-outline-warning btn-sm rounded-circle" title="Edit Listing"><i class="fas fa-edit"></i></a>' : '').'
                                <button type="button" onclick="confirmDelete('.$auction->id.')" class="btn btn-icon-elite btn-outline-danger btn-sm rounded-circle" title="Delete Listing"><i class="fas fa-trash-alt"></i></button>
                            </div>';
                })
                ->rawColumns(['item', 'status', 'price', 'winner', 'bids', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Winning items
    public function winningItems()
    {
        return view('website.user.winning-items');
    }

    // Winning items data for DataTables
    public function winningItemsData()
    {
        try {
            $user = auth()->user();
            
            $auctions = \App\Models\Auction::where('end_time', '<=', now())
                ->whereIn('status', ['active', 'closed'])
                ->whereHas('bids', function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->whereRaw('amount = (SELECT MAX(amount) FROM bids WHERE auction_id = auctions.id)');
                })
                ->with(['category', 'user'])
                ->latest();

            return datatables()->of($auctions)
                ->addColumn('item', function($auction) {
                    $image = $auction->image ? (str_starts_with($auction->image, 'http') ? $auction->image : asset('storage/' . $auction->image)) : 'https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=120';
                    $title = e($auction->title);
                    return '
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                <img src="'.$image.'" class="rounded-3 shadow-sm border" width="55" height="55" style="object-fit: cover;" onerror="this.src=\'https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=120\'">
                            </div>
                            <div>
                                <div class="text-dark fw-bold mb-0 text-truncate" style="max-width: 220px;" title="'.$title.'">'.$title.'</div>
                                <div class="text-muted" style="font-size: 0.75rem;">ID: #'.str_pad($auction->id, 5, '0', STR_PAD_LEFT).'</div>
                            </div>
                        </div>';
                })
                ->addColumn('winning_bid', function($auction) {
                    return '<span class="fw-bold text-dark">₹'.number_format($auction->current_price, 2).'</span>';
                })
                ->addColumn('won_date', function($auction) {
                    return '<span class="text-secondary small">'. $auction->end_time->format('M d, Y') .'</span>';
                })
                ->addColumn('payment_status', function($auction) {
                    // This can be expanded if you have a payment status in DB
                    return '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 fw-semibold shadow-sm" style="font-size: 0.75rem;">
                                <i class="fas fa-check-circle me-1 small"></i> Won
                            </span>';
                })
                ->addColumn('action', function($auction) {
                    $url = route('auctions.show', $auction->id);
                    return '<div class="text-end">
                                <a href="'.$url.'" class="btn btn-icon-elite btn-outline-primary btn-sm rounded-circle" title="View Listing"><i class="fas fa-eye"></i></a>
                            </div>';
                })
                ->rawColumns(['item', 'winning_bid', 'won_date', 'payment_status', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Watchlist
    public function watchlist()
    {
        return view('website.user.watchlist');
    }

    // Watchlist data for DataTables
    public function watchlistData()
    {
        try {
            $watchlists = auth()->user()
                ->watchlist()
                ->with(['auction.category', 'auction.user'])
                ->latest('watchlists.created_at');

            return datatables()->of($watchlists)
                ->addColumn('item', function($item) {
                    $auction = $item->auction;
                    $image = $auction->image ? (str_starts_with($auction->image, 'http') ? $auction->image : asset('storage/' . $auction->image)) : 'https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=120';
                    $title = e($auction->title);
                    $seller = e($auction->user->name ?? 'Unknown');
                    return '
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                <img src="'.$image.'" class="rounded-3 shadow-sm border" width="55" height="55" style="object-fit: cover;" onerror="this.src=\'https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=120\'">
                            </div>
                            <div>
                                <div class="text-dark fw-bold mb-0 text-truncate" style="max-width: 220px;" title="'.$title.'">'.$title.'</div>
                                <div class="text-muted" style="font-size: 0.75rem;"><i class="fas fa-user me-1"></i> '.$seller.'</div>
                            </div>
                        </div>';
                })
                ->addColumn('category', function($item) {
                    $catName = $item->auction->category->name ?? 'N/A';
                    return '<span class="badge bg-light text-secondary border rounded-pill px-3 py-1 fw-medium" style="font-size: 0.7rem;">'.e($catName).'</span>';
                })
                ->addColumn('price', function($item) {
                    return '<span class="fw-bold text-primary">₹'.number_format($item->auction->current_price, 2).'</span>';
                })
                ->addColumn('end_time', function($item) {
                    return '<div class="text-muted small">
                                <i class="far fa-clock me-1"></i>'.$item->auction->end_time->format('M d, Y H:i').'
                            </div>';
                })
                ->addColumn('action', function($item) {
                    $viewUrl = route('auctions.show', $item->auction_id);
                    $toggleUrl = route('user.watchlist.toggle', $item->auction_id);
                    $csrf = csrf_field();
                    
                    return '<div class="d-flex justify-content-end gap-2">
                                <a href="'.$viewUrl.'" class="btn btn-icon-elite btn-outline-primary btn-sm rounded-circle" title="View Listing"><i class="fas fa-eye"></i></a>
                                <form action="'.$toggleUrl.'" method="POST" class="d-inline">
                                    '.$csrf.'
                                    <button type="submit" class="btn btn-icon-elite btn-outline-danger btn-sm rounded-circle" title="Remove from Watchlist">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </form>
                            </div>';
                })
                ->rawColumns(['item', 'category', 'price', 'end_time', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Show single message (contact)
    public function showMessage($id)
    {
        $contact = Contact::withTrashed()
            ->where('email', auth()->user()->email)
            ->findOrFail($id);

        return view('website.user.message_show', compact('contact'));
    }

    // Profile
    public function profile()
    {
        return view('website.user.profile');
    }
}
