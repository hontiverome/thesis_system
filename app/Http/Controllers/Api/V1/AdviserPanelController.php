<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdviserPanelController extends Controller
{
    // ==========================================
    // PHASE 1: INVITATIONS (Images 1, 2, 3)
    // ==========================================

    // API: Get Invitations
    public function getPanelInvitations(Request $request)
    {
        // TODO: Sa actual login, gagamitin natin: $user_id = $request->user()->id;
        // Sa ngayon, HARDCODED muna natin sa 117 (Adviser) para ma-test mo.
        $user_id = 117; 

        $invitations = DB::table('defensepanel')
            ->join('defenses', 'defensepanel.DefenseID', '=', 'defenses.DefenseID')
            ->join('proposals', 'defenses.ProposalID', '=', 'proposals.ProposalID')
            ->where('defensepanel.PanelistUserID', $user_id) // Kunin lang ang invite para sa adviser na to
            ->select(
                'defensepanel.DefenseID',
                'defensepanel.Status as InvitationStatus', // Pending/Accepted
                'defenses.DefenseType',
                'defenses.Schedule',
                'proposals.ResearchTitle',
                'proposals.ProposalID'
            )
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $invitations
        ], 200);
    }

    // API: Respond to Invitation (Accept/Decline) (Image 1 buttons)
    public function respondToInvitation(Request $request, $defenseId)
    {
        // Validate input: status should be 'Accepted' or 'Declined'
        $request->validate([
            'status' => 'required|in:Accepted,Declined'
        ]);

        $user_id = 117; // Hardcoded muna ulit

        // Update status
        DB::table('defensepanel')
            ->where('DefenseID', $defenseId)
            ->where('PanelistUserID', $user_id)
            ->update(['Status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Invitation ' . $request->status
        ], 200);
    }

    // ==========================================
    // PHASE 2: EVALUATION / JUDGING (Images 4-8)
    // ==========================================

    // API: Get List of Thesis to Judge (Yung Accepted na siya as Panel)
    public function getDefensesToEvaluate(Request $request)
    {
        $user_id = 117; // Hardcoded muna

        $defenses = DB::table('defensepanel')
            ->join('defenses', 'defensepanel.DefenseID', '=', 'defenses.DefenseID')
            ->join('proposals', 'defenses.ProposalID', '=', 'proposals.ProposalID')
            // Join para makuha Group Code (Chain: Proposal -> Enrollment -> Group)
            ->join('enrollments', 'proposals.EnrollmentID', '=', 'enrollments.EnrollmentID')
            ->join('groups', 'enrollments.GroupID', '=', 'groups.GroupID')
            // Left Join para makita kung nag-grade na ba si Adviser o hindi pa
            ->leftJoin('defenseevaluations', function($join) use ($user_id) {
                $join->on('defenses.DefenseID', '=', 'defenseevaluations.DefenseID')
                     ->where('defenseevaluations.PanelistUserID', '=', $user_id);
            })
            ->where('defensepanel.PanelistUserID', $user_id)
            ->where('defensepanel.Status', 'Accepted') // Dapat Accepted na siya as Panelist
            ->select(
                'defenses.DefenseID',
                'groups.GroupCode',
                'proposals.ResearchTitle',
                'defenses.Schedule',
                'defenseevaluations.Verdict as MyVerdict' // Null kung di pa nag-judge, 'Approved'/'Rejected' kung tapos na
            )
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $defenses
        ], 200);
    }

    // API: Submit Verdict (Accept/Reject Button)
    public function submitVerdict(Request $request, $defenseId)
    {
        // Validate input
        $request->validate([
            'verdict' => 'required|in:Approved,Rejected,Revise' 
        ]);

        $user_id = 117; // Hardcoded muna

        // Gamit tayo ng updateOrInsert para kung nag-judge na dati, ma-update lang.
        // Kung first time, mag-iinsert ng bago.
        DB::table('defenseevaluations')->updateOrInsert(
            [
                'DefenseID' => $defenseId,
                'PanelistUserID' => $user_id
            ],
            [
                'EvaluationID' => uniqid('EVAL-'), // Auto-generate ID like EVAL-654abc
                'Verdict' => $request->verdict
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Verdict submitted successfully'
        ], 200);
    }
}