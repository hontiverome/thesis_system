<?php

namespace App\Http\Controllers;

use App\Models\Defense;
use App\Models\DefensePanel;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DefenseEvaluationDocumentController extends Controller
{
    /**
     * F-112: POST /api/v1/defenses/{defenseId}/documents
     * Upload evaluation form and grading sheet for completed defenses
     * 
     * Security Features:
     * - File validation: PDF, DOC, DOCX for evaluation forms; XLSX, XLS additional for grading sheets
     * - Maximum file size: 10MB per file
     * - Organized storage: files stored in defense-specific directories
     * - Authorization: Only panelists who have accepted the invitation can upload
     */
    public function uploadDocuments(Request $request, $defenseId)
    {
        $user = $request->user();
        
        // Verify panelist authorization and acceptance status
        $panel = DefensePanel::where('DefenseID', $defenseId)
            ->where('PanelistUserID', $user->UserID)
            ->where('Status', 'Accepted')
            ->first();
        
        if (!$panel) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You must be an accepted panelist to upload evaluation documents.'
            ], 403);
        }
        
        // Validate request
        $validated = $request->validate([
            'evaluation_form' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'grading_sheet' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls|max:10240',
        ]);
        
        if (!$request->hasFile('evaluation_form') && !$request->hasFile('grading_sheet')) {
            return response()->json([
                'success' => false,
                'message' => 'At least one file (evaluation form or grading sheet) is required.'
            ], 400);
        }
        
        $defense = Defense::where('DefenseID', $defenseId)
            ->with('proposal')
            ->first();
        
        if (!$defense) {
            return response()->json([
                'success' => false,
                'message' => 'Defense not found'
            ], 404);
        }
        
        DB::beginTransaction();
        try {
            $uploadedFiles = [];
            
            // Upload Evaluation Form
            if ($request->hasFile('evaluation_form')) {
                $evaluationFile = $request->file('evaluation_form');
                $fileExtension = $evaluationFile->getClientOriginalExtension();
                
                // Generate unique FileID (max 10 chars)
                $lastSubmission = Submission::orderBy('FileID', 'desc')->first();
                $nextNumber = $lastSubmission ? (int)substr($lastSubmission->FileID, 4) + 1 : 1;
                $fileId = 'FILE-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                
                // Store file in defense-specific directory
                $filePath = $evaluationFile->storeAs(
                    "defenses/{$defenseId}/evaluations",
                    "{$fileId}.{$fileExtension}",
                    'public'
                );
                
                // Create Submission record
                $submission = Submission::create([
                    'FileID' => $fileId,
                    'ProposalID' => $defense->ProposalID,
                    'DefenseID' => $defenseId,
                    'FileType' => 'Evaluation Form',
                    'FilePath' => $filePath,
                    'UploadedByUserID' => $user->UserID,
                ]);
                
                $uploadedFiles[] = [
                    'FileID' => $submission->FileID,
                    'FileType' => $submission->FileType,
                    'FilePath' => $submission->FilePath,
                    'url' => Storage::url($filePath),
                ];
            }
            
            // Upload Grading Sheet
            if ($request->hasFile('grading_sheet')) {
                $gradingFile = $request->file('grading_sheet');
                $fileExtension = $gradingFile->getClientOriginalExtension();
                
                // Generate unique FileID (max 10 chars)
                $lastSubmission = Submission::orderBy('FileID', 'desc')->first();
                $nextNumber = $lastSubmission ? (int)substr($lastSubmission->FileID, 5) + 1 : 1;
                $fileId = 'FILE-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                
                // Store file in defense-specific directory
                $filePath = $gradingFile->storeAs(
                    "defenses/{$defenseId}/grading",
                    "{$fileId}.{$fileExtension}",
                    'public'
                );
                
                // Create Submission record
                $submission = Submission::create([
                    'FileID' => $fileId,
                    'ProposalID' => $defense->ProposalID,
                    'DefenseID' => $defenseId,
                    'FileType' => 'Grading Sheet',
                    'FilePath' => $filePath,
                    'UploadedByUserID' => $user->UserID,
                ]);
                
                $uploadedFiles[] = [
                    'FileID' => $submission->FileID,
                    'FileType' => $submission->FileType,
                    'FilePath' => $submission->FilePath,
                    'url' => Storage::url($filePath),
                ];
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Evaluation documents uploaded successfully',
                'data' => [
                    'DefenseID' => $defenseId,
                    'uploaded_by' => [
                        'UserID' => $user->UserID,
                        'name' => $user->first_name . ' ' . $user->last_name,
                    ],
                    'files' => $uploadedFiles
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload documents',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * F-112: GET /api/v1/defenses/{defenseId}/documents
     * Retrieve all uploaded evaluation documents for a specific defense
     */
    public function getDocuments(Request $request, $defenseId)
    {
        $user = $request->user();
        
        // Verify user has access to this defense
        // Either they are a panelist or they are part of the group
        $defense = Defense::where('DefenseID', $defenseId)
            ->with([
                'proposal',
                'enrollment.group.members',
                'panel'
            ])->first();
        
        if (!$defense) {
            return response()->json([
                'success' => false,
                'message' => 'Defense not found'
            ], 404);
        }
        
        $isPanelist = $defense->panel->contains('UserID', $user->UserID);
        $isGroupMember = $defense->enrollment->group->members->contains('MemberUserID', $user->UserID);
        
        // Check if user is coordinator/chairperson (can see all)
        $userRoles = $user->roles()->pluck('RoleName')->toArray();
        $isCoordinatorOrChairperson = in_array('coordinator', $userRoles) || in_array('chairperson', $userRoles);
        
        if (!$isPanelist && !$isGroupMember && !$isCoordinatorOrChairperson) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view these documents'
            ], 403);
        }
        
        $submissions = Submission::where('DefenseID', $defenseId)
            ->with('uploadedBy')
            ->get()
            ->map(function($submission) {
                return [
                    'FileID' => $submission->FileID,
                    'FileType' => $submission->FileType,
                    'FilePath' => $submission->FilePath,
                    'url' => Storage::url($submission->FilePath),
                    'uploaded_by' => [
                        'UserID' => $submission->uploadedBy->UserID,
                        'name' => $submission->uploadedBy->first_name . ' ' . $submission->uploadedBy->last_name,
                    ]
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => [
                'DefenseID' => $defenseId,
                'documents' => $submissions
            ]
        ]);
    }
}
