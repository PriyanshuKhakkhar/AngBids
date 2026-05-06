<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubmitKycRequest;
use App\Http\Resources\KycResource;
use App\Models\Kyc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    /**
     * Display the current user's KYC status.
     */
    public function show(Request $request)
    {
        $kyc = $request->user()->kyc;

        if (!$kyc) {
            return response()->json([
                'success' => false,
                'message' => 'No KYC submission found for this user.',
                'status' => 'not_submitted'
            ], 404);
        }

        return (new KycResource($kyc))->additional([
            'success' => true
        ]);
    }

    /**
     * Store or update a KYC submission.
     */
    public function store(SubmitKycRequest $request)
    {
        $user = $request->user();
        $existingKyc = $user->kyc;

        // Block if KYC is already approved or pending
        if ($existingKyc && $existingKyc->status !== 'rejected') {
            return response()->json([
                'success' => false,
                'message' => 'Your KYC request is already being processed or has been approved.',
                'status' => $existingKyc->status
            ], 422);
        }

        $idDocumentPath = $request->file('id_document')->store('kyc/documents', 'public');
        $selfieImagePath = $request->file('selfie_image')->store('kyc/selfies', 'public');

        if ($existingKyc && $existingKyc->status === 'rejected') {
            // Delete old files
            if ($existingKyc->id_document) {
                Storage::disk('public')->delete($existingKyc->id_document);
            }
            if ($existingKyc->selfie_image) {
                Storage::disk('public')->delete($existingKyc->selfie_image);
            }

            // Update existing record
            $existingKyc->update([
                'full_name'     => $request->full_name,
                'date_of_birth' => $request->date_of_birth,
                'address'       => $request->address,
                'id_type'       => $request->id_type,
                'id_number'     => $request->id_number,
                'id_document'   => $idDocumentPath,
                'selfie_image'  => $selfieImagePath,
                'status'        => 'pending',
                'admin_note'    => null,
            ]);
            
            $kyc = $existingKyc;
        } else {
            // Create new record
            $kyc = Kyc::create([
                'user_id'       => $user->id,
                'full_name'     => $request->full_name,
                'date_of_birth' => $request->date_of_birth,
                'address'       => $request->address,
                'id_type'       => $request->id_type,
                'id_number'     => $request->id_number,
                'id_document'   => $idDocumentPath,
                'selfie_image'  => $selfieImagePath,
                'status'        => 'pending',
            ]);
        }

        // Synchronize user kyc_status
        $user->update(['kyc_status' => 'submitted']);

        return (new KycResource($kyc))->additional([
            'success' => true,
            'message' => 'KYC submitted successfully and is pending verification.'
        ]);
    }
}
