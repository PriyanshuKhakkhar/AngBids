<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kyc;

class AdminKycController extends Controller
{
    public function index()
    {
        $kycs = Kyc::with('user')->latest()->paginate(10);
        return view('admin.kyc.index', compact('kycs'));
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
