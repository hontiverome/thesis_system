<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdviserProposalController extends Controller
{
    public function getProposals(Request $request)
    {
       
        $proposals = DB::table('proposals')
                    ->select(
                        'ProposalID', 
                        'ResearchTitle', 
                        'Status', 
                        'SubmissionDate', 
                        'Deadline',
                        'EnrollmentID' 
                    )
                    ->get();

        return response()->json([
            'status' => 'success',
            'data' => $proposals
        ], 200);
    }
}