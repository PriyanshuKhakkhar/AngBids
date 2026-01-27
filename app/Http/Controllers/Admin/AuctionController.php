<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Auction::with('user')->latest();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user', function($row){
                    return $row->user ? $row->user->name : 'N/A';
                })
                ->filterColumn('user', function($query, $keyword) {
                    $query->whereHas('user', function($q) use($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('current_price', function($row){
                    return '$' . number_format($row->current_price, 2);
                })
                ->addColumn('status', function($row){
                    $badgeClass = match($row->status) {
                        'active' => 'success',
                        'closed' => 'secondary',
                        'cancelled' => 'danger',
                        default => 'warning'
                    };
                    return '<span class="badge badge-'.$badgeClass.'">'.ucfirst($row->status).'</span>';
                })
                ->addColumn('end_time', function($row){
                    return $row->end_time ? $row->end_time->format('M d, Y H:i') : 'N/A';
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    
                    // View
                    $btn .= '<a href="'.route('admin.auctions.show', $row->id).'" class="btn btn-info btn-sm mr-1" title="View"><i class="fas fa-eye"></i></a>';
                    
                    // Cancel (only if active/pending)
                    if($row->status != 'cancelled' && $row->status != 'closed' && $row->status != 'completed') {
                        $btn .= '<button type="button" class="btn btn-warning btn-sm mr-1 cancel-auction" data-id="'.$row->id.'" data-url="'.route('admin.auctions.cancel', $row->id).'" title="Cancel Auction"><i class="fas fa-ban"></i></button>';
                    }

                    // Delete
                    $btn .= '<button type="button" class="btn btn-danger btn-sm delete-auction" data-id="'.$row->id.'" data-url="'.route('admin.auctions.destroy', $row->id).'" title="Delete"><i class="fas fa-trash"></i></button>';
                    
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.auctions.index', [
            'total_auctions' => Auction::count()
        ]);
    }

    public function show(Auction $auction)
    {
        return view('admin.auctions.show', compact('auction'));
    }

    public function destroy(Auction $auction)
    {
        $auction->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Auction deleted successfully.']);
        }

        return redirect()->route('admin.auctions.index')->with('success', 'Auction deleted successfully.');
    }

    public function cancel(Auction $auction)
    {
        $auction->update(['status' => 'cancelled']);

        if (request()->ajax()) { // Handle POST/GET ajax logic if needed, usually passed as POST
             return response()->json(['success' => 'Auction cancelled successfully.']);
        }

        return redirect()->back()->with('success', 'Auction cancelled successfully.');
    }
}
