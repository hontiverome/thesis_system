<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Defense;
use App\Models\DefenseEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DefenseEvaluationController extends Controller
{
    /**
     * Upload evaluation document for a defense
     * POST /api/v1/defenses/{defense_id}/documents
     */
    public function uploadDocument(Request $request, $defenseId)
    {
        $validator = Validator::make($request->all(), [
            'document' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
            'document_type' => 'required|string|in:evaluation_form,feedback,other',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $defense = Defense::find($defenseId);
        if (!$defense) {
            return response()->json([
                'success' => false,
                'message' => 'Defense not found'
            ], 404);
        }

        // Store the file
        $file = $request->file('document');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('defense_evaluations', $filename, 'public');

        // Create evaluation record
        $evaluation = DefenseEvaluation::create([
            'DefenseID' => $defenseId,
            'PanelistID' => $request->user()->UserID,
            'Score' => $request->score ?? null,
            'Feedback' => $request->notes,
            'DocumentPath' => $path,
            'DocumentType' => $request->document_type,
            'UploadedAt' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully',
            'data' => $evaluation
        ], 201);
    }

    /**
     * Get all evaluation documents for a defense
     * GET /api/v1/defenses/{defense_id}/documents
     */
    public function getDocuments($defenseId)
    {
        $defense = Defense::with(['evaluations.panelist'])->find($defenseId);
        
        if (!$defense) {
            return response()->json([
                'success' => false,
                'message' => 'Defense not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'defense' => $defense,
                'evaluations' => $defense->evaluations
            ]
        ]);
    }

    /**
     * Download a specific evaluation document
     * GET /api/v1/evaluations/{evaluation_id}/download
     */
    public function downloadDocument($evaluationId)
    {
        $evaluation = DefenseEvaluation::find($evaluationId);
        
        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => 'Evaluation document not found'
            ], 404);
        }

        if (!Storage::disk('public')->exists($evaluation->DocumentPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found on server'
            ], 404);
        }

        return Storage::disk('public')->download($evaluation->DocumentPath);
    }
}
