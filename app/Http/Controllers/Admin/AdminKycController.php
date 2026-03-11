<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kyc;
use Yajra\DataTables\Facades\DataTables;

class AdminKycController extends Controller
{
    public function index()
    {
        return view('admin.kyc.index');
    }

    public function data()
    {
        $kycs = Kyc::with('user')->select('kycs.*');

        return DataTables::of($kycs)
            ->addIndexColumn()
            ->addColumn('user', function ($kyc) {
                return $kyc->user->username;
            })
            ->editColumn('id_type', function ($kyc) {
                return ucfirst(str_replace('_', ' ', $kyc->id_type));
            })
            ->editColumn('created_at', function ($kyc) {
                return $kyc->created_at->format('M d, Y H:i');
            })
            ->editColumn('status', function ($kyc) {
                $badges = [
                    'pending' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger'
                ];
                $color = $badges[$kyc->status] ?? 'secondary';
                return '<span class="badge badge-' . $color . '">' . ucfirst($kyc->status) . '</span>';
            })
            ->addColumn('action', function ($kyc) {
                return '<a href="' . route('admin.kyc.show', $kyc->id) . '" class="btn btn-outline-info btn-sm btn-action" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function show($id)
    {
        $kyc = Kyc::with('user')->findOrFail($id);
        return view('admin.kyc.show', compact('kyc'));
    }

    public function approve($id)
    {
        $kyc = Kyc::findOrFail($id);
        $kyc->update(['status' => 'approved', 'admin_note' => null]);

        return redirect()->back()->with('success', 'KYC approved successfully.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'required|string|max:500',
        ]);

        $kyc = Kyc::findOrFail($id);
        $kyc->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
        ]);

        return redirect()->back()->with('success', 'KYC rejected with note.');
    }
}
