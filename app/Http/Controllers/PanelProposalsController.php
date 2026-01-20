<?php

namespace App\Http\Controllers;

use App\Models\Defense;
use App\Models\DefensePanel;
use Illuminate\Http\Request;

class PanelProposalsController extends Controller
{
    /**
     * GET /api/v1/faculty/panel/proposals
     * Get all thesis proposals where faculty is assigned as panelist
     * Different from invitations - this shows all proposals they're evaluating
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get all defenses where user is a panelist (any status)
        $defenses = Defense::whereHas('panel', function($query) use ($user) {
            $query->where('PanelistUserID', $user->UserID);
        })->with([
            'proposal',
            'enrollment.group.members.user',
            'enrollment.course',
            'panel'
        ])->get();
        
        $proposals = $defenses->map(function($defense) use ($user) {
            // Get user's panel status for this defense
            $panelStatus = $defense->panel->where('UserID', $user->UserID)->first();
            
            return [
                'DefenseID' => $defense->DefenseID,
                'DefenseType' => $defense->DefenseType,
                'Schedule' => $defense->Schedule,
                'OverallVerdict' => $defense->OverallVerdict,
                'my_panel_status' => $panelStatus ? $panelStatus->pivot->Status : 'Unknown',
                'proposal' => [
                    'ProposalID' => $defense->proposal->ProposalID,
                    'ResearchTitle' => $defense->proposal->ResearchTitle,
                    'Status' => $defense->proposal->Status,
                    'SubmissionDate' => $defense->proposal->SubmissionDate,
                ],
                'group' => [
                    'GroupID' => $defense->enrollment->group->GroupID,
                    'GroupName' => $defense->enrollment->group->GroupName,
                    'members' => $defense->enrollment->group->members->map(function($member) {
                        return [
                            'UserID' => $member->user->UserID,
                            'FullName' => $member->user->FullName,
                            'SchoolID' => $member->user->SchoolID,
                        ];
                    })
                ],
                'course' => [
                    'CourseID' => $defense->enrollment->course->CourseID,
                    'CourseName' => $defense->enrollment->course->CourseName,
                    'CourseCode' => $defense->enrollment->course->CourseCode,
                ],
                'panel_members' => $defense->panel->map(function($panelist) {
                    return [
                        'UserID' => $panelist->UserID,
                        'FullName' => $panelist->FullName,
                        'Status' => $panelist->pivot->Status,
                    ];
                })
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $proposals
        ]);
    }
    
    /**
     * GET /api/v1/faculty/panel/proposals/stats
     * Get statistics about panel assignments
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        
        $totalAssignments = DefensePanel::where('PanelistUserID', $user->UserID)->count();
        $acceptedAssignments = DefensePanel::where('PanelistUserID', $user->UserID)
            ->where('Status', 'Accepted')->count();
        $pendingInvitations = DefensePanel::where('PanelistUserID', $user->UserID)
            ->where('Status', 'Pending')->count();
        $completedDefenses = Defense::whereHas('panel', function($query) use ($user) {
            $query->where('PanelistUserID', $user->UserID)
                  ->where('Status', 'Accepted');
        })->where('OverallVerdict', '!=', 'Pending')->count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_assignments' => $totalAssignments,
                'accepted' => $acceptedAssignments,
                'pending_invitations' => $pendingInvitations,
                'completed_defenses' => $completedDefenses,
            ]
        ]);
    }
}
