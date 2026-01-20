<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\ProposalApproval;
use App\Models\Enrollment;
use App\Models\GroupAdviser;
use App\Models\DefensePanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProposalApprovalController extends Controller
{
    /**
     * F-106: GET /api/v1/faculty/proposals
     * Retrieve all proposals from assigned groups, grouped by course with approval statistics
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get user's role(s)
        $userRoles = $user->roles()->pluck('RoleName')->toArray();
        
        // Determine authorization level
        $isCoordinatorOrChairperson = in_array('coordinator', $userRoles) || in_array('chairperson', $userRoles);
        $isAdviser = in_array('adviser', $userRoles) || in_array('faculty', $userRoles);
        
        // Build query based on role
        $proposalsQuery = Proposal::with([
            'enrollment.course',
            'enrollment.group.members',
            'approvals.approvedUser'
        ]);
        
        if ($isAdviser && !$isCoordinatorOrChairperson) {
            // Adviser: Only see proposals from assigned sections/groups
            $assignedGroupIds = GroupAdviser::where('AdviserUserID', $user->UserID)
                ->pluck('GroupID');
            
            $proposalsQuery->whereHas('enrollment', function($q) use ($assignedGroupIds) {
                $q->whereIn('GroupID', $assignedGroupIds);
            });
        }
        // Coordinator/Chairperson: See all proposals (no filter needed)
        
        $proposals = $proposalsQuery->get();
        
        // Group by course and add statistics
        $groupedProposals = $proposals->groupBy('enrollment.course.CourseName')->map(function($courseProposals, $courseName) {
            return [
                'course_name' => $courseName,
                'total_proposals' => $courseProposals->count(),
                'approved' => $courseProposals->where('Status', 'Approved')->count(),
                'pending' => $courseProposals->where('Status', 'Pending')->count(),
                'disapproved' => $courseProposals->where('Status', 'Disapproved')->count(),
                'proposals' => $courseProposals->map(function($proposal) {
                    return [
                        'ProposalID' => $proposal->ProposalID,
                        'ResearchTitle' => $proposal->ResearchTitle,
                        'Status' => $proposal->Status,
                        'SubmissionDate' => $proposal->SubmissionDate,
                        'Deadline' => $proposal->Deadline,
                        'group' => [
                            'GroupID' => $proposal->enrollment->group->GroupID ?? null,
                            'GroupName' => $proposal->enrollment->group->GroupName ?? null,
                            'member_count' => $proposal->enrollment->group->members->count() ?? 0,
                        ],
                        'approval_count' => $proposal->approvals->count(),
                        'approved_count' => $proposal->approvals->where('Status', 'Approved')->count(),
                        'disapproved_count' => $proposal->approvals->where('Status', 'Disapproved')->count(),
                    ];
                })->values()
            ];
        })->values();
        
        return response()->json([
            'success' => true,
            'data' => $groupedProposals
        ]);
    }
    
    /**
     * F-106: GET /api/v1/faculty/proposals/{proposalId}
     * Retrieve detailed proposal information including all approvals
     */
    public function show(Request $request, $proposalId)
    {
        $user = $request->user();
        
        $proposal = Proposal::with([
            'enrollment.course',
            'enrollment.group.members.user',
            'approvals.approvedUser.roles',
        ])->findOrFail($proposalId);
        
        // Check authorization
        $userRoles = $user->roles()->pluck('RoleName')->toArray();
        $isCoordinatorOrChairperson = in_array('coordinator', $userRoles) || in_array('chairperson', $userRoles);
        
        if (!$isCoordinatorOrChairperson) {
            // Check if adviser of this group
            $isAdviser = GroupAdviser::where('AdviserUserID', $user->UserID)
                ->where('GroupID', $proposal->enrollment->GroupID)
                ->exists();
            
            // Check if panelist for any defense of this proposal
            $isPanelist = DefensePanel::whereHas('defense', function($query) use ($proposalId) {
                $query->where('ProposalID', $proposalId);
            })->where('PanelistUserID', $user->UserID)->exists();
            
            if (!$isAdviser && !$isPanelist) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view this proposal'
                ], 403);
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'ProposalID' => $proposal->ProposalID,
                'ResearchTitle' => $proposal->ResearchTitle,
                'Status' => $proposal->Status,
                'SubmissionDate' => $proposal->SubmissionDate,
                'Deadline' => $proposal->Deadline,
                'course' => [
                    'CourseID' => $proposal->enrollment->course->CourseID,
                    'CourseName' => $proposal->enrollment->course->CourseName,
                ],
                'group' => [
                    'GroupID' => $proposal->enrollment->group->GroupID,
                    'GroupName' => $proposal->enrollment->group->GroupName,
                    'members' => $proposal->enrollment->group->members->map(function($member) {
                        return [
                            'UserID' => $member->user->UserID,
                            'name' => $member->user->first_name . ' ' . $member->user->last_name,
                            'id_number' => $member->user->id_number,
                        ];
                    })
                ],
                'approvals' => $proposal->approvals->map(function($approval) {
                    return [
                        'ApprovalID' => $approval->ApprovalID,
                        'Status' => $approval->Status,
                        'Remarks' => $approval->Remarks,
                        'ApprovalRole' => $approval->ApprovalRole,
                        'approver' => [
                            'UserID' => $approval->approvedUser->UserID,
                            'name' => $approval->approvedUser->first_name . ' ' . $approval->approvedUser->last_name,
                        ],
                        'created_at' => $approval->created_at,
                        'updated_at' => $approval->updated_at,
                    ];
                })
            ]
        ]);
    }
    
    /**
     * F-106: PATCH /api/v1/proposals/{proposalId}/verdict
     * Submit or update approval verdict for a proposal
     * 
     * Business Logic:
     * - Status set to "Disapproved" if any approval is disapproved
     * - Status set to "Approved" only when all approvals are approved
     * - Status remains "Pending" for partial approvals
     * - Idempotent: allows faculty to change their verdict
     */
    public function updateVerdict(Request $request, $proposalId)
    {
        $validated = $request->validate([
            'verdict' => 'required|in:Approved,Disapproved',
            'remarks' => 'nullable|string|max:500'
        ]);
        
        $user = $request->user();
        
        $proposal = Proposal::with('enrollment.group')->findOrFail($proposalId);
        
        // Determine user's role for this proposal
        $userRoles = $user->roles()->pluck('RoleName')->toArray();
        $approvalRole = null;
        
        $isCoordinatorOrChairperson = in_array('coordinator', $userRoles) || in_array('chairperson', $userRoles);
        $isAdviser = GroupAdviser::where('AdviserUserID', $user->UserID)
            ->where('GroupID', $proposal->enrollment->GroupID)
            ->exists();
        
        if ($isCoordinatorOrChairperson) {
            $approvalRole = 'Coordinator/Chairperson';
        } elseif ($isAdviser) {
            $approvalRole = 'Adviser';
        } else {
            // Check if committee member with invitation
            // For now, allow faculty role
            if (in_array('committee', $userRoles) || in_array('faculty', $userRoles)) {
                $approvalRole = 'Committee';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to approve this proposal'
                ], 403);
            }
        }
        
        DB::beginTransaction();
        try {
            // Check if approval already exists
            $approval = ProposalApproval::where('ProposalID', $proposalId)
                ->where('ApprovedUserID', $user->UserID)
                ->first();
            
            if ($approval) {
                // Update existing approval
                $approval->update([
                    'ApprovalRole' => $approvalRole,
                    'Status' => $validated['verdict'],
                    'Remarks' => $validated['remarks'] ?? null,
                ]);
            } else {
                // Generate new ApprovalID
                $lastApproval = ProposalApproval::orderBy('ApprovalID', 'desc')->first();
                $nextNumber = $lastApproval ? (int)substr($lastApproval->ApprovalID, 4) + 1 : 1;
                $approvalId = 'APPR-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                
                // Create new approval
                $approval = ProposalApproval::create([
                    'ApprovalID' => $approvalId,
                    'ProposalID' => $proposalId,
                    'ApprovedUserID' => $user->UserID,
                    'ApprovalRole' => $approvalRole,
                    'Status' => $validated['verdict'],
                    'Remarks' => $validated['remarks'] ?? null,
                ]);
            }
            
            // Auto-calculate proposal status based on all approvals
            $allApprovals = ProposalApproval::where('ProposalID', $proposalId)->get();
            
            $hasDisapproved = $allApprovals->contains('Status', 'Disapproved');
            $allApproved = $allApprovals->isNotEmpty() && $allApprovals->every(fn($a) => $a->Status === 'Approved');
            
            if ($hasDisapproved) {
                $proposal->Status = 'Disapproved';
            } elseif ($allApproved) {
                $proposal->Status = 'Approved';
            } else {
                $proposal->Status = 'Pending';
            }
            
            $proposal->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Verdict submitted successfully',
                'data' => [
                    'approval' => $approval,
                    'proposal_status' => $proposal->Status
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit verdict',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
