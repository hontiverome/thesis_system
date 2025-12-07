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
            'facultyDetail', 
            'groups.advisers', 
            'groups.proposal.defenses.panel',
            'groups.proposal.approvals.user',
            'submissions.proposal',
            'groupsAsAdviser.users',
            'defensePanels.defense.proposal.group.users'
        ]);

        $profileData = [
            'basic_info' => [
                'user_id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ],
        ];

        if ($user->hasRole('student')) {
            $profileData['student_info'] = [
                'student_id' => $user->id_number,
                'group_code' => $user->groups->first()?->name ?? null,
                'year_level' => $user->groups->first()?->year_level ?? null,
                'group_role' => $user->groups->first()?->pivot?->role ?? null,
                'adviser' => $user->groups->first()?->advisers->first()?->full_name ?? null,
            ];

            if($user->groups->isNotEmpty() && $user->groups->first()->proposal) {
                $proposal = $user->groups->first()->proposal;
                $profileData['proposal_details'] = [
                    'title' => $proposal->title,
                    'status' => $proposal->status,
                    'submission_date' => $proposal->submission_date,
                    'deadline' => $proposal->deadline,
                    'approvals' => $proposal->approvals->map(function ($approval) {
                        return [
                            'panelist' => $approval->user->full_name,
                            'status' => $approval->status,
                        ];
                    }),
                ];

                $profileData['research_archive'] = [
                    'abstract' => $proposal->abstract,
                    'manuscript' => $proposal->manuscript_path,
                    'published_date' => $proposal->published_date,
                ];

                if($proposal->defenses->isNotEmpty()){
                    $profileData['defense_details'] = $proposal->defenses->map(function ($defense){
                        return [
                            'defense_date' => $defense->defense_date,
                            'location' => $defense->location,
                            'panel' => $defense->panel->map(function($panelist){
                                return [
                                    'name' => $panelist->full_name,
                                    'status' => $panelist->pivot->status,
                                    'evaluation' => $panelist->pivot->evaluation,
                                ];
                            }),
                            'verdict' => $defense->verdict,
                        ];
                    });
                }
            }

            $profileData['submissions'] = $user->submissions->map(function($submission){
                return [
                    'file_type' => $submission->file_type,
                    'submitted_at' => $submission->created_at,
                    'proposal_title' => $submission->proposal->title,
                ];
            });

        }

        if ($user->hasRole('faculty')) {
            $profileData['faculty_info'] = [
                'faculty_type' => $user->facultyDetail?->faculty_type ?? null,
                'groups_advised' => $user->groupsAsAdviser->map(function($group){
                    return [
                        'group_name' => $group->name,
                        'members' => $group->users->pluck('full_name'),
                    ];
                }),
                'defense_panels' => $user->defensePanels->map(function($panel){
                    return [
                        'defense_date' => $panel->defense?->defense_date,
                        'proposal_title' => $panel->defense?->proposal?->title,
                        'group' => $panel->defense?->proposal?->group?->name,
                    ];
                }),
            ];
        }

        return response()->json($profileData);
    }
}
