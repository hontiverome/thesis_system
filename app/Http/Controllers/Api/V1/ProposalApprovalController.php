<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProposalApproval;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProposalApprovalController extends Controller
{
    /**
     * Get all proposals for adviser's advised groups
     * GET /api/v1/proposals
     */
    public function getAllProposals(Request $request)
    {
        $userId = $request->user()->UserID;
        
        // Get proposals where user is adviser or filter by status
        $query = Proposal::with(['group.members', 'group.advisers', 'approvals']);
        
        // If faculty, show only proposals they can review
        if ($request->user()->role !== 'admin') {
            $query->whereHas('group.advisers', function($q) use ($userId) {
                $q->where('AdviserID', $userId);
            });
        }

        if ($request->has('status')) {
            $query->where('Status', $request->status);
        }

        $proposals = $query->orderBy('SubmittedAt', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $proposals
        ]);
    }

    /**
     * Get specific proposal details
     * GET /api/v1/proposals/{proposal_id}
     */
    public function getProposalDetails($proposalId)
    {
        $proposal = Proposal::with([
            'group.members.user',
            'group.advisers.adviser',
            'approvals.approver'
        ])->find($proposalId);

        if (!$proposal) {
            return response()->json([
                'success' => false,
                'message' => 'Proposal not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $proposal
        ]);
    }

    /**
     * Approve proposal (F-1Db)
     * PATCH /api/v1/proposals/{proposal_id}/approve
     */
    public function approveProposal(Request $request, $proposalId)
    {
        $validator = Validator::make($request->all(), [
            'comments' => 'nullable|string|max:1000',
            'conditions' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $proposal = Proposal::find($proposalId);
        if (!$proposal) {
            return response()->json([
                'success' => false,
                'message' => 'Proposal not found'
            ], 404);
        }

        // Check if already approved by this user
        $existingApproval = ProposalApproval::where('ProposalID', $proposalId)
            ->where('ApproverID', $request->user()->UserID)
            ->first();

        if ($existingApproval) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this proposal'
            ], 400);
        }

        // Create approval record
        $approval = ProposalApproval::create([
            'ProposalID' => $proposalId,
            'ApproverID' => $request->user()->UserID,
            'Status' => 'approved',
            'Comments' => $request->comments,
            'ApprovedAt' => now()
        ]);

        // Update proposal status
        $proposal->update([
            'Status' => 'approved',
            'ApprovedAt' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proposal approved successfully',
            'data' => [
                'approval' => $approval,
                'proposal' => $proposal
            ]
        ]);
    }

    /**
     * Disapprove proposal (F-1Db)
     * PATCH /api/v1/proposals/{proposal_id}/disapprove
     */
    public function disapproveProposal(Request $request, $proposalId)
    {
        $validator = Validator::make($request->all(), [
            'comments' => 'required|string|max:1000',
            'reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $proposal = Proposal::find($proposalId);
        if (!$proposal) {
            return response()->json([
                'success' => false,
                'message' => 'Proposal not found'
            ], 404);
        }

        // Check if already reviewed by this user
        $existingApproval = ProposalApproval::where('ProposalID', $proposalId)
            ->where('ApproverID', $request->user()->UserID)
            ->first();

        if ($existingApproval) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this proposal'
            ], 400);
        }

        // Create disapproval record
        $approval = ProposalApproval::create([
            'ProposalID' => $proposalId,
            'ApproverID' => $request->user()->UserID,
            'Status' => 'rejected',
            'Comments' => $request->comments,
            'Reason' => $request->reason,
            'ApprovedAt' => now()
        ]);

        // Update proposal status
        $proposal->update([
            'Status' => 'rejected',
            'RejectedAt' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proposal disapproved',
            'data' => [
                'approval' => $approval,
                'proposal' => $proposal
            ]
        ]);
    }

    /**
     * Update previous verdict
     * PATCH /api/v1/proposals/approvals/{approval_id}/verdict
     */
    public function updateVerdict(Request $request, $approvalId)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
            'comments' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $approval = ProposalApproval::where('ApprovalID', $approvalId)
            ->where('ApproverID', $request->user()->UserID)
            ->first();

        if (!$approval) {
            return response()->json([
                'success' => false,
                'message' => 'Approval record not found or unauthorized'
            ], 404);
        }

        $approval->update([
            'Status' => $request->status,
            'Comments' => $request->comments ?? $approval->Comments,
            'UpdatedAt' => now()
        ]);

        // Update proposal status
        $approval->proposal->update([
            'Status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Verdict updated successfully',
            'data' => $approval
        ]);
    }
}
