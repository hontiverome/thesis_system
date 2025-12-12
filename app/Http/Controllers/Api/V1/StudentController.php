<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\StudentProposalService;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function displayInfo()
    {
        $userID = Auth::user()->UserID;
        $studentData = StudentProposalService::getGroupInfo($userID);

        return response()->json(['message' => $studentData], 200);
    }

    public function submitProposal(Request $request, $groupId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',
        ]);

        $userID = Auth::user()->UserID;
        $file = $request->file('file');
        $upload = StudentProposalService::submitProposal($userID, $file);

        return response()->json(['message' => $upload], 200);
    }

    public function getProposalStatus($groupId)
    {
        $userID = Auth::user()->UserID;
        $proposals = StudentProposalService::getGroupProposal($userID);

        return response()->json($proposals);
    }

    public function invitation()
    {
        $userID = Auth::user()->UserID;
        $inv = StudentProposalService::getDefenseInvitation($userID);

        return response()->json(['message' => $inv], 200);
    }

    public function evaluation()
    {
        $userID = Auth::user()->UserID;
        $inv = StudentProposalService::getDefenseEvaluation($userID);

        return response()->json(['message' => $inv], 200);
    }

    public function getEligibleProposals($groupId)
    {
        $userID = Auth::user()->UserID;
        $quotaStatus = StudentProposalService::checkProposalQuota($userID);

        return response()->json(['message' => $quotaStatus], 200);
    }

    public function delete($proposalId)
    {
        $delete = StudentProposalService::deleteProposal($proposalId);

        return response()->json(['message' => 'Proposal #' . $delete . ' deleted'], 200);
    }

    public function submitManuscript(Request $request, $groupId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',
        ]);

        $userID = Auth::user()->UserID;
        $file = $request->file('file');
        $upload = StudentProposalService::submitManuscript($userID, $file);

        return response()->json(['message' => $upload], 200);
    }
    
    public function selectTitleForDefense(Request $request, $groupId)
    {
        $request->validate([
            'proposal_id' => 'required'
        ]);

        try {
            $userID = Auth::user()->UserID;
            $proposalId = $request->input('proposal_id');

            $defense = StudentProposalService::pickTitle($userID, $proposalId);

            return response()->json([
                'message' => 'Title successfully selected for defense!',
                'data'    => $defense
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /*
    public function getDefenseVerdict($groupId)
    {
        try {
            $userID = Auth::user()->UserID;
            $verdictData = StudentProposalService::getDefenseVerdict($userID);

            return response()->json([
                'message' => 'Defense verdict retrieved successfully.',
                'data'    => $verdictData
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }*/
}