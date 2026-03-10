<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kyc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    public function showForm()
    {
        $user = Auth::user();
        if ($user->kyc) {
            return redirect()->route('user.kyc.status');
        }

        return view('website.user.kyc_form');
    }

    public function submitKyc(Request $request)
    {
        $user = Auth::user();
        
        if ($user->kyc) {
            return redirect()->back()->with('error', 'You have already submitted a KYC request.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'id_type' => 'required|in:aadhaar,pan,passport,driving_license',
            'id_number' => 'required|string|max:100',
            'id_document' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'selfie_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $idDocumentPath = $request->file('id_document')->store('kyc/documents', 'public');
        $selfieImagePath = $request->file('selfie_image')->store('kyc/selfies', 'public');

        Kyc::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'id_type' => $request->id_type,
            'id_number' => $request->id_number,
            'id_document' => $idDocumentPath,
            'selfie_image' => $selfieImagePath,
            'status' => 'pending',
        ]);

        return redirect()->route('user.kyc.status')->with('success', 'KYC submitted successfully and is pending verification.');
    }

    public function showStatus()
    {
        $kyc = Auth::user()->kyc;
        if (!$kyc) {
            return redirect()->route('user.kyc.form');
        }

        return view('website.user.kyc_status', compact('kyc'));
    }
}
