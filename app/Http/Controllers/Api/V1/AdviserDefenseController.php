<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Importante para sa File Upload

class AdviserDefenseController extends Controller
{
    // 1. GET LIST OF DEFENSES (Para sa "Thesis Title Defense" Table - Image 3)
    public function getGroupDefenses(Request $request)
    {
        $user_id = 117; // Hardcoded muna (Change to $request->user()->id later)

        $defenses = DB::table('defenses')
            ->join('proposals', 'defenses.ProposalID', '=', 'proposals.ProposalID')
            ->join('enrollments', 'proposals.EnrollmentID', '=', 'enrollments.EnrollmentID')
            ->join('groups', 'enrollments.GroupID', '=', 'groups.GroupID')
            ->where('groups.AdviserUserID', $user_id) // Kunin lang groups ng adviser na to
            ->select(
                'defenses.DefenseID',
                'groups.GroupCode',
                'proposals.ResearchTitle',
                'defenses.OverallVerdict as Status' // E.g., Pending, Passed, Failed
            )
            ->get();

        return response()->json(['status' => 'success', 'data' => $defenses]);
    }

    // 2. UPDATE DEFENSE STATUS (Passed/Failed - Image 4)
    public function updateDefenseStatus(Request $request, $defenseId)
    {
        $request->validate([
            'verdict' => 'required|in:Passed,Failed,Re-Defense'
        ]);

        DB::table('defenses')
            ->where('DefenseID', $defenseId)
            ->update(['OverallVerdict' => $request->verdict]);

        return response()->json(['status' => 'success', 'message' => 'Defense status updated']);
    }

    // 3. UPLOAD DOCUMENT (Evaluation Form / Grading Sheet - Images 6-13)
    public function uploadDocument(Request $request, $defenseId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:5120', // Max 5MB, PDF only
            'file_type' => 'required|in:Evaluation Form,Grading Sheet' // Para alam kung ano inupload
        ]);

        $user_id = 117; // Hardcoded muna

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Save file to "storage/app/public/submissions"
            $path = $file->store('public/submissions'); 
            
            // Save info to Database
            DB::table('submissions')->insert([
                'FileID' => uniqid('FILE-'),
                'DefenseID' => $defenseId,
                'FileType' => $request->file_type,
                'FilePath' => $path,
                'UploadedByUserID' => $user_id
            ]);

            return response()->json(['status' => 'success', 'message' => 'File uploaded successfully']);
        }

        return response()->json(['status' => 'error', 'message' => 'No file uploaded'], 400);
    }

    // 4. GET UPLOADED DOCUMENTS (Para makita sa listahan - Images 14-16)
    public function getDefenseDocuments(Request $request, $defenseId)
    {
        $documents = DB::table('submissions')
            ->where('DefenseID', $defenseId)
            ->select('FileID', 'FileType', 'FilePath', 'UploadedByUserID')
            ->get();

        return response()->json(['status' => 'success', 'data' => $documents]);
    }

    // 5. DELETE DOCUMENT (Image 15)
    public function deleteDocument($fileId)
    {
        $file = DB::table('submissions')->where('FileID', $fileId)->first();

        if ($file) {
            // Delete actual file from storage
            Storage::delete($file->FilePath);
            
            // Delete record from database
            DB::table('submissions')->where('FileID', $fileId)->delete();

            return response()->json(['status' => 'success', 'message' => 'File deleted']);
        }

        return response()->json(['status' => 'error', 'message' => 'File not found'], 404);
    }
}