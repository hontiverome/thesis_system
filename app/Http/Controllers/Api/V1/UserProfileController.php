<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'submissions',
            'facultyDetail',
            'groupsAsAdviser.users',
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
            // Get student's group info with direct query
            $groupInfo = \DB::table('thesis.GroupMembers')
                ->join('thesis.Groups', 'thesis.GroupMembers.GroupID', '=', 'thesis.Groups.GroupID')
                ->where('thesis.GroupMembers.StudentUserID', $user->UserID)
                ->first();

            $profileData['student_info'] = [
                'StudentID' => $user->SchoolID,
                'GroupCode' => $groupInfo->GroupCode ?? null,
                'YearLevel' => $groupInfo->YearLevel ?? null,
                'GroupRole' => $groupInfo->GroupRole ?? null,
            ];

            // Get adviser info
            if ($groupInfo) {
                $adviserInfo = \DB::table('thesis.GroupAdvisers')
                    ->join('thesis.Users', 'thesis.GroupAdvisers.AdviserUserID', '=', 'thesis.Users.UserID')
                    ->where('thesis.GroupAdvisers.GroupID', $groupInfo->GroupID)
                    ->first();
                $profileData['student_info']['Adviser'] = $adviserInfo->FullName ?? null;
            }

            // Get proposal info if group exists
            if ($groupInfo) {
                $enrollmentInfo = \DB::table('thesis.Enrollments')
                    ->where('GroupID', $groupInfo->GroupID)
                    ->first();
                
                $proposalInfo = null;
                if ($enrollmentInfo) {
                    $proposalInfo = \DB::table('thesis.Proposals')
                        ->where('EnrollmentID', $enrollmentInfo->EnrollmentID)
                        ->first();
                }

                if ($proposalInfo) {
                    $profileData['proposal_details'] = [
                        'ResearchTitle' => $proposalInfo->ResearchTitle,
                        'Status' => $proposalInfo->Status,
                        'SubmissionDate' => $proposalInfo->SubmissionDate,
                        'Deadline' => $proposalInfo->Deadline,
                    ];
                }
            }

            $profileData['submissions'] = $user->submissions->map(function($submission){
                return [
                    'FileID' => $submission->FileID,
                    'FileType' => $submission->FileType,
                    'FilePath' => $submission->FilePath,
                    'FileUrl' => url('storage/' . $submission->FilePath),
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
