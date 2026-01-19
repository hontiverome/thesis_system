<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DefensePanel;
use App\Models\Defense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PanelInvitationController extends Controller
{
    /**
     * Get all panel invitations for the authenticated faculty
     * GET /api/v1/panel/invitations
     */
    public function getAllInvitations(Request $request)
    {
        $userId = $request->user()->UserID;
        
        $invitations = DefensePanel::with(['defense.group.proposal'])
            ->where('PanelistID', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $invitations
        ]);
    }

    /**
     * Get specific panel invitation details
     * GET /api/v1/panel/invitations/{invitation_id}
     */
    public function getInvitation($invitationId)
    {
        $invitation = DefensePanel::with([
            'defense.group.proposal',
            'defense.group.members',
            'panelist'
        ])->find($invitationId);

        if (!$invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $invitation
        ]);
    }

    /**
     * Accept panel invitation
     * PATCH /api/v1/panel/invitations/{invitation_id}/accept
     */
    public function acceptInvitation(Request $request, $invitationId)
    {
        $invitation = DefensePanel::where('PanelID', $invitationId)
            ->where('PanelistID', $request->user()->UserID)
            ->first();

        if (!$invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation not found or unauthorized'
            ], 404);
        }

        if ($invitation->Status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Invitation already ' . $invitation->Status
            ], 400);
        }

        $invitation->update([
            'Status' => 'accepted',
            'ResponseDate' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invitation accepted successfully',
            'data' => $invitation
        ]);
    }

    /**
     * Decline panel invitation
     * PATCH /api/v1/panel/invitations/{invitation_id}/decline
     */
    public function declineInvitation(Request $request, $invitationId)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $invitation = DefensePanel::where('PanelID', $invitationId)
            ->where('PanelistID', $request->user()->UserID)
            ->first();

        if (!$invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation not found or unauthorized'
            ], 404);
        }

        if ($invitation->Status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Invitation already ' . $invitation->Status
            ], 400);
        }

        $invitation->update([
            'Status' => 'declined',
            'ResponseDate' => now(),
            'DeclineReason' => $request->reason
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invitation declined',
            'data' => $invitation
        ]);
    }
}
