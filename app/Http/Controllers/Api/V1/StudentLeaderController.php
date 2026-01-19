<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\StudentServices\StudentInterfaceService;
use App\Services\StudentServices\StudentProposalService;
use App\Services\StudentServices\SubmitFileService;
use Illuminate\Support\Facades\Auth;


class StudentLeaderController extends Controller
{
    public function submitProposal(Request $request, $groupId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',
        ]);

        $userID = Auth::user()->UserID;
        $file = $request->file('file');
        $upload = SubmitFileService::submitProposal($userID, $file);

        return response()->json(['message' => $upload], 200);
    }

    public function submitManuscript(Request $request, $groupId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
        $userID = Auth::user()->UserID;
        $file = $request->file('file');
        $upload = SubmitFileService::submitManuscript($userID, $file);

        return response()->json(['message' => $upload], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
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

       public function delete($proposalId)
    {   
        try {
             Auth::user()->UserID;
             $delete = StudentProposalService::deleteProposal($proposalId);
             return response()->json(['message' => 'Proposal #' . $delete . ' deleted'], 200);
            } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getEligibleProposals($groupId)
    {
        $userID = Auth::user()->UserID;
        $quotaStatus = StudentProposalService::checkProposalQuota($userID);

        return response()->json(['Proposal Status' => $quotaStatus], 200);
    }

} 
