<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request 
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = $request->user()->load([
            'roles', 
            'groups.enrollment.proposal',
            'groups.enrollment.proposal.defenses',
            'groups.enrollment.proposal.defenses.panel',
            'groups.enrollment.proposal.defenses.evaluations',
            'groups.enrollment.proposal.approvals',
            'groups.enrollment.proposal.archive',
            'submissions',
            'facultyDetail',
            'groupsAsAdviser.users',
            'defensePanels.defense.proposal.enrollment.group',
        ]);

        $profileData = [
            'basic_info' => [
                'UserID' => $user->UserID,
                'SchoolID' => $user->SchoolID,
                'FullName' => $user->FullName,
                'Email' => $user->Email,
                'BirthDate' => $user->BirthDate,
                'Roles' => $user->roles->pluck('RoleName'),
            ],
        ];

        if ($user->hasRole('student')) {
            $profileData['student_info'] = [
                'StudentID' => $user->SchoolID,
                'GroupCode' => $user->groups->first()?->GroupCode ?? null,
                'YearLevel' => $user->groups->first()?->YearLevel ?? null,
                'GroupRole' => $user->groups->first()?->pivot?->GroupRole ?? null,
                'Adviser' => $user->groups->first()?->advisers->first()?->FullName ?? null,
            ];

            if($user->groups->isNotEmpty() && $user->groups->first()->enrollment && $user->groups->first()->enrollment->proposal) {
                $proposal = $user->groups->first()->enrollment->proposal;
                $profileData['proposal_details'] = [
                    'ResearchTitle' => $proposal->ResearchTitle,
                    'Status' => $proposal->Status,
                    'SubmissionDate' => $proposal->SubmissionDate,
                    'Deadline' => $proposal->Deadline,
                    'Approvals' => $proposal->approvals->map(function ($approval) {
                        return [
                            'Approver' => $approval->approvedUser->FullName,
                            'ApprovalRole' => $approval->ApprovalRole,
                            'Status' => $approval->Status,
                            'Remarks' => $approval->Remarks,
                        ];
                    }),
                ];

                if($proposal->archive) {
                    $profileData['research_archive'] = [
                        'AbstractFilePath' => $proposal->archive->AbstractFilePath,
                        'FullManuscriptPath' => $proposal->archive->FullManuscriptPath,
                        'PublishedDate' => $proposal->archive->PublishedDate,
                    ];
                }

                if($proposal->defenses->isNotEmpty()){
                    $profileData['defense_details'] = $proposal->defenses->map(function ($defense){
                        return [
                            'DefenseID' => $defense->DefenseID,
                            'DefenseType' => $defense->DefenseType,
                            'Schedule' => $defense->Schedule,
                            'OverallVerdict' => $defense->OverallVerdict,
                            'Panel' => $defense->panel->map(function($panelist){
                                return [
                                    'FullName' => $panelist->FullName,
                                    'Status' => $panelist->pivot->Status,
                                ];
                            }),
                            'Evaluations' => $defense->evaluations->map(function($evaluation){
                                return [
                                    'Panelist' => $evaluation->panelistUser->FullName,
                                    'Verdict' => $evaluation->Verdict,
                                ];
                            }),
                        ];
                    });
                }
            }

            $profileData['submissions'] = $user->submissions->map(function($submission){
                return [
                    'FileID' => $submission->FileID,
                    'FileType' => $submission->FileType,
                    'FilePath' => $submission->FilePath,
                    'ProposalID' => $submission->ProposalID,
                    'DefenseID' => $submission->DefenseID,
                ];
            });

        }

        if ($user->hasRole('faculty') || $user->hasRole('adviser') || $user->hasRole('research coordinator')) {
            $profileData['faculty_info'] = [
                'FacultyType' => $user->facultyDetail?->FacultyType ?? null,
                'GroupsAdvised' => $user->groupsAsAdviser->map(function($group){
                    return [
                        'GroupID' => $group->GroupID,
                        'GroupCode' => $group->GroupCode,
                        'YearLevel' => $group->YearLevel,
                        'Members' => $group->users->map(function($member){
                            return [
                                'FullName' => $member->FullName,
                                'GroupRole' => $member->pivot->GroupRole,
                            ];
                        }),
                    ];
                }),
                'DefensePanels' => $user->defensePanels->map(function($panel){
                    return [
                        'DefenseID' => $panel->DefenseID,
                        'DefenseType' => $panel->defense?->DefenseType,
                        'Schedule' => $panel->defense?->Schedule,
                        'ResearchTitle' => $panel->defense?->proposal?->ResearchTitle,
                        'Group' => $panel->defense?->proposal?->enrollment?->group?->GroupCode,
                        'Status' => $panel->pivot->Status,
                    ];
                }),
            ];
        }

        if ($user->hasRole('administrator')) {
            $profileData['admin_info'] = [
                'SystemAccess' => 'full',
                'CanCreateFaculty' => true,
                'CanManageUsers' => true,
            ];
        }

        return response()->json($profileData);
    }
}
