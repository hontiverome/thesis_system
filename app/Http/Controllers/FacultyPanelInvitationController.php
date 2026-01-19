<?php

namespace App\Http\Controllers;

use App\Models\Defense;
use App\Models\DefensePanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacultyPanelInvitationController extends Controller
{
    /**
     * F-111: GET /api/v1/faculty/me/invitations
     * Retrieve all defense panel invitations for authenticated faculty
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $invitations = DefensePanel::where('PanelistUserID', $user->UserID)
            ->with([
                'defense.proposal',
                'defense.enrollment.group.members.user',
                'defense.enrollment.course'
            ])
            ->get()
            ->map(function($panel) {
                return [
                    'DefenseID' => $panel->DefenseID,
                    'Status' => $panel->Status,
                    'defense' => [
                        'DefenseID' => $panel->defense->DefenseID,
                        'DefenseType' => $panel->defense->DefenseType,
                        'Schedule' => $panel->defense->Schedule,
                        'OverallVerdict' => $panel->defense->OverallVerdict,
                    ],
                    'proposal' => [
                        'ProposalID' => $panel->defense->proposal->ProposalID,
                        'ResearchTitle' => $panel->defense->proposal->ResearchTitle,
                        'Status' => $panel->defense->proposal->Status,
                    ],
                    'group' => [
                        'GroupID' => $panel->defense->enrollment->group->GroupID,
                        'GroupName' => $panel->defense->enrollment->group->GroupName,
                        'members' => $panel->defense->enrollment->group->members->map(function($member) {
                            return [
                                'UserID' => $member->user->UserID,
                                'name' => $member->user->first_name . ' ' . $member->user->last_name,
                                'id_number' => $member->user->id_number,
                            ];
                        })
                    ],
                    'course' => [
                        'CourseID' => $panel->defense->enrollment->course->CourseID,
                        'CourseName' => $panel->defense->enrollment->course->CourseName,
                    ]
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $invitations
        ]);
    }
    
    /**
     * F-111: GET /api/v1/faculty/me/invitations/{defenseId}
     * Retrieve specific defense panel invitation details
     */
    public function show(Request $request, $defenseId)
    {
        $user = $request->user();
        
        $panel = DefensePanel::where('DefenseID', $defenseId)
            ->where('PanelistUserID', $user->UserID)
            ->with([
                'defense.proposal',
                'defense.enrollment.group.members.user',
                'defense.enrollment.course',
                'defense.panel'
            ])
            ->firstOrFail();
        
        return response()->json([
            'success' => true,
            'data' => [
                'DefenseID' => $panel->DefenseID,
                'Status' => $panel->Status,
                'defense' => [
                    'DefenseID' => $panel->defense->DefenseID,
                    'DefenseType' => $panel->defense->DefenseType,
                    'Schedule' => $panel->defense->Schedule,
                    'OverallVerdict' => $panel->defense->OverallVerdict,
                ],
                'proposal' => [
                    'ProposalID' => $panel->defense->proposal->ProposalID,
                    'ResearchTitle' => $panel->defense->proposal->ResearchTitle,
                    'Status' => $panel->defense->proposal->Status,
                    'SubmissionDate' => $panel->defense->proposal->SubmissionDate,
                ],
                'group' => [
                    'GroupID' => $panel->defense->enrollment->group->GroupID,
                    'GroupName' => $panel->defense->enrollment->group->GroupName,
                    'members' => $panel->defense->enrollment->group->members->map(function($member) {
                        return [
                            'UserID' => $member->user->UserID,
                            'name' => $member->user->first_name . ' ' . $member->user->last_name,
                            'id_number' => $member->user->id_number,
                            'email' => $member->user->email,
                        ];
                    })
                ],
                'course' => [
                    'CourseID' => $panel->defense->enrollment->course->CourseID,
                    'CourseName' => $panel->defense->enrollment->course->CourseName,
                ],
                'all_panelists' => $panel->defense->panel->map(function($panelist) {
                    return [
                        'UserID' => $panelist->UserID,
                        'name' => $panelist->FullName,
                        'email' => $panelist->Email,
                        'Status' => $panelist->pivot->Status,
                    ];
                })
            ]
        ]);
    }
    
    /**
     * F-111: POST /api/v1/invitations/{defenseId}/response
     * Accept or decline panel invitation
     * 
     * Business Logic:
     * - Faculty can only respond to invitations where they are listed as panelist
     * - Prevents duplicate responses (idempotent)
     * - Updates DefensePanel status accordingly
     */
    public function respondToInvitation(Request $request, $defenseId)
    {
        $validated = $request->validate([
            'response' => 'required|in:Accepted,Declined'
        ]);
        
        $user = $request->user();
        
        // Find the panel invitation
        $panel = DefensePanel::where('DefenseID', $defenseId)
            ->where('PanelistUserID', $user->UserID)
            ->firstOrFail();
        
        // Validate current status - allow changing response if needed (idempotent)
        if ($panel->Status === $validated['response']) {
            return response()->json([
                'success' => true,
                'message' => 'You have already ' . strtolower($validated['response']) . ' this invitation',
                'data' => $panel
            ]);
        }
        
        DB::beginTransaction();
        try {
            // Use update instead of save() for composite primary key
            DefensePanel::where('DefenseID', $defenseId)
                ->where('PanelistUserID', $user->UserID)
                ->update(['Status' => $validated['response']]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Invitation response recorded successfully',
                'data' => [
                    'DefenseID' => $defenseId,
                    'PanelistUserID' => $user->UserID,
                    'Status' => $validated['response'],
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to record response',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
