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
            'groups.proposal.defenses.panel',
            'groups.proposal.defenses.evaluations',
        ]);

        $profileData = [
            'basic_info' => [
                'user_id' => $user->UserID,
                'full_name' => $user->FullName,
                'email' => $user->Email,
                'roles' => $user->roles->pluck('RoleName'),
            ],
        ];

        if ($user->hasRole('student')) {
            $profileData['student_info'] = [
                'student_id' => $user->SchoolID,
                'group_code' => $user->groups->first()?->GroupCode ?? null,
                'year_level' => $user->groups->first()?->YearLevel ?? null,
                'group_role' => $user->groups->first()?->pivot?->GroupRole ?? null,
                'adviser' => $user->groups->first()?->advisers->first()?->FullName ?? null,
            ];

            if($user->groups->isNotEmpty() && $user->groups->first()->proposal) {
                $proposal = $user->groups->first()->proposal;
                $profileData['proposal_details'] = [
                    'title' => $proposal->ResearchTitle,
                    'status' => $proposal->Status,
                    'submission_date' => $proposal->SubmissionDate,
                    'deadline' => $proposal->Deadline,
                    'approvals' => $proposal->approvals->map(function ($approval) {
                        return [
                            'panelist' => $approval->approvedUser->FullName,
                            'status' => $approval->Status,
                        ];
                    }),
                ];

                $profileData['research_archive'] = [
                    'abstract' => $proposal->AbstractFilePath ?? null,
                    'manuscript' => $proposal->FullManuscriptPath ?? null,
                    'published_date' => $proposal->PublishedDate ?? null,
                ];

                if($proposal->defenses->isNotEmpty()){
                    $profileData['defense_details'] = $proposal->defenses->map(function ($defense){
                        return [
                            'defense_date' => $defense->Schedule,
                            'location' => null,
                            'panel' => $defense->panel->map(function($panelist){
                                return [
                                    'name' => $panelist->FullName,
                                    'status' => $panelist->pivot->Status,
                                    'evaluation' => $panelist->pivot->evaluation,
                                ];
                            }),
                            'verdict' => $defense->OverallVerdict,
                        ];
                    });
                }
            }

            $profileData['submissions'] = $user->submissions->map(function($submission){
                return [
                    'file_type' => $submission->FileType,
                    'submitted_at' => $submission->created_at,
                    'proposal_title' => $submission->proposal?->ResearchTitle,
                ];
            });

        }

        if ($user->hasRole('faculty')) {
            $profileData['faculty_info'] = [
                'faculty_type' => $user->facultyDetail?->FacultyType ?? null,
                'groups_advised' => $user->groupsAsAdviser->map(function($group){
                    return [
                        'group_name' => $group->GroupCode,
                        'members' => $group->users->pluck('FullName'),
                    ];
                }),
                'defense_panels' => $user->defensePanels->map(function($panel){
                    return [
                        'defense_date' => $panel->defense?->Schedule,
                        'proposal_title' => $panel->defense?->proposal?->ResearchTitle,
                        'group' => $panel->defense?->proposal?->group?->GroupCode,
                    ];
                }),
            ];
        }

        return response()->json($profileData);
    }
}
