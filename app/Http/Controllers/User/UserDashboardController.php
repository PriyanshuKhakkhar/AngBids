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
                    if(strlen($title) > 45) {
                        $title = substr($title, 0, 45) . '...';
                    }
                    
                    return '
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                <img src="'.$image.'" class="rounded border" width="50" height="50" style="object-fit: cover;" onerror="this.src=\'https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=120\'">
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark mb-1 d-inline-block" style="max-width:350px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="'.e($auction->title).'">'.$title.'</span>
                                <span class="text-muted small">ID: #'.str_pad($auction->id, 5, '0', STR_PAD_LEFT).'</span>
                            </div>
                        </div>';
                })
                ->addColumn('my_bid', function($bid) {
                    return '₹'.number_format($bid->amount, 2);
                })
                ->addColumn('current_price', function($bid) {
                    return '₹'.number_format($bid->auction->current_price, 2);
                })
                ->addColumn('status', function($bid) {
                    $status = $bid->auction->status_label;
                    $bg = match($status) {
                        'Live' => 'success', 'Starting Soon' => 'info', 'Ended' => 'danger',
                        'Pending' => 'warning text-dark', 'Closed' => 'secondary', 'Cancelled' => 'dark', default => 'secondary'
                    };
                    return '<span class="badge bg-'.$bg.'">'.$status.'</span>';
                })
                ->addColumn('time_left', function($bid) {
                    $auction = $bid->auction;
                    if ($auction->status === 'active' && $auction->end_time->isFuture()) {
                        return $auction->end_time->diffForHumans(null, true);
                    }
                    return 'Ended';
                })
                ->addColumn('action', function($bid) {
                    $url = route('auctions.show', $bid->auction->id);
                    return '<a href="'.$url.'" class="btn btn-outline-info btn-sm text-info bg-white" title="View"><i class="fas fa-eye"></i></a>';
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
                    if(strlen($title) > 45) {
                        $title = substr($title, 0, 45) . '...';
                    }
                    $date = $auction->created_at->format('M d, Y');
                    return '
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                <img src="'.$image.'" class="rounded border" width="50" height="50" style="object-fit: cover;" onerror="this.src=\'https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=120\'">
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark mb-1 d-inline-block" style="max-width:350px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="'.e($auction->title).'">'.$title.'</span>
                                <span class="text-muted small"><i class="far fa-calendar-alt me-1"></i> Listed on '.$date.'</span>
                            </div>
                        </div>';
                })
                ->editColumn('status', function($auction) {
                    $status = $auction->status_label;
                    $bg = match($status) {
                        'Live' => 'success', 'Starting Soon' => 'info', 'Ended' => 'danger',
                        'Pending' => 'warning text-dark', 'Closed' => 'secondary', 'Cancelled' => 'dark', default => 'secondary'
                    };
                    return '<span class="badge bg-'.$bg.'">'.$status.'</span>';
                })
                ->addColumn('price', function($auction) {
                    return '₹'.number_format($auction->current_price, 2);
                })
                ->addColumn('winner', function($auction) {
                    $highestBid = $auction->highestBid();
                    if ($highestBid && $highestBid->user) {
                        return e($highestBid->user->name);
                    }
                    return 'N/A';
                })
                ->addColumn('bids', function($auction) {
                    return $auction->bids->count();
                })
                ->addColumn('action', function($auction) {
                    $isWithin24Hours = $auction->created_at && $auction->created_at->diffInHours(now()) <= 24;
                    $canEdit = $auction->end_time->isFuture() && (
                        $auction->status === 'active' || 
                        ($auction->status === 'pending' && $isWithin24Hours)
                    );
                    
                    $viewUrl = route('auctions.show', $auction->id);
                    $editUrl = route('auctions.edit', $auction->id);
                    
                    $html = '<a href="'.$viewUrl.'" class="btn btn-outline-info btn-sm text-info border-info bg-white me-1" title="View"><i class="fas fa-eye"></i></a>';
                    if($canEdit) {
                        $html .= '<a href="'.$editUrl.'" class="btn btn-outline-primary btn-sm text-primary border-primary bg-white me-1" title="Edit"><i class="fas fa-edit"></i></a>';
                    }
                    $html .= '<button type="button" onclick="confirmDelete('.$auction->id.')" class="btn btn-outline-danger btn-sm text-danger border-danger bg-white" title="Delete"><i class="fas fa-trash"></i></button>';
                    
                    return '<div class="text-nowrap">'.$html.'</div>';
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
                    if(strlen($title) > 45) {
                        $title = substr($title, 0, 45) . '...';
                    }
                    return '
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                <img src="'.$image.'" class="rounded border" width="50" height="50" style="object-fit: cover;" onerror="this.src=\'https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=120\'">
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark mb-1 d-inline-block" style="max-width:350px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="'.e($auction->title).'">'.$title.'</span>
                                <span class="text-muted small">ID: #'.str_pad($auction->id, 5, '0', STR_PAD_LEFT).'</span>
                            </div>
                        </div>';
                })
                ->addColumn('winning_bid', function($auction) {
                    return '₹'.number_format($auction->current_price, 2);
                })
                ->addColumn('won_date', function($auction) {
                    return $auction->end_time->format('M d, Y');
                })
                ->addColumn('payment_status', function($auction) {
                    return '<span class="badge bg-success">Won</span>';
                })
                ->addColumn('action', function($auction) {
                    $url = route('auctions.show', $auction->id);
                    return '<a href="'.$url.'" class="btn btn-outline-info btn-sm text-info bg-white" title="View"><i class="fas fa-eye"></i></a>';
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
                    if(strlen($title) > 45) {
                        $title = substr($title, 0, 45) . '...';
                    }
                    $seller = e($auction->user->name ?? 'Unknown');
                    return '
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                <img src="'.$image.'" class="rounded border" width="50" height="50" style="object-fit: cover;" onerror="this.src=\'https://images.unsplash.com/photo-1523275335684-21481017106d?auto=format&fit=crop&w=120\'">
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark mb-1 d-inline-block" style="max-width:350px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="'.e($auction->title).'">'.$title.'</span>
                                <span class="text-muted small"><i class="fas fa-user me-1"></i> '.$seller.'</span>
                            </div>
                        </div>';
                })
                ->addColumn('category', function($item) {
                    return $item->auction->category->name ?? 'N/A';
                })
                ->addColumn('price', function($item) {
                    return '₹'.number_format($item->auction->current_price, 2);
                })
                ->addColumn('end_time', function($item) {
                    return $item->auction->end_time->format('M d, Y H:i');
                })
                ->addColumn('action', function($item) {
                    $viewUrl = route('auctions.show', $item->auction_id);
                    $toggleUrl = route('user.watchlist.toggle', $item->auction_id);
                    $csrf = csrf_field();
                    
                    return '<div class="text-nowrap">
                                <a href="'.$viewUrl.'" class="btn btn-outline-info btn-sm text-info border-info bg-white me-1" title="View"><i class="fas fa-eye"></i></a>
                                <form action="'.$toggleUrl.'" method="POST" class="d-inline">
                                    '.$csrf.'
                                    <button type="submit" class="btn btn-outline-danger btn-sm text-danger border-danger bg-white" title="Remove">
                                        <i class="fas fa-trash"></i>
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
