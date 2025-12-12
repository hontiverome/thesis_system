<?php

namespace App\Services;

use App\Models\Proposal;
use App\Models\User;
use App\Models\Submission;
use App\Models\Enrollment;
use App\Models\DefensePanel;
use App\Models\Defense;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class StudentProposalService
{
    public static function getGroupInfo(int $userID): Collection
    {
        $user = User::with('groups')->find($userID);
        if (!$user) return new Collection();

        $groupIds = $user->groups->pluck('GroupID')->toArray();
        if (empty($groupIds)) return new Collection();

        return Proposal::with([
            'enrollment' => function ($query) {
                $query->select('EnrollmentID', 'GroupID');
            },
            'enrollment.group' => function ($query) {
                $query->select('GroupID', 'GroupCode', 'YearLevel');
            },
            'enrollment.group.advisers' => function ($query) {
                $query->select('users.UserID', 'FullName');
            }
        ])->get();
    }

    public static function getGroupProposal(int $userID)
    {
        $user = User::with('groups')->find($userID);
        $groupIds = $user->groups->pluck('GroupID')->first();
        $enrollment = Enrollment::where('GroupID', $groupIds)->first();
        $evaluation = Proposal::with('approvals')->where('EnrollmentID', $enrollment->EnrollmentID)->get();
        
        return $evaluation->pluck('approvals')->flatten();
    }

    public static function submitProposal(int $userID, UploadedFile $pdfFile)
    {
        $user = User::with('groups')->find($userID);

        if (!$user) {
            throw new \Exception("User not found");
        }

        if ($user->groups->isEmpty()) {
            throw new \Exception("Student is not a member of any group.");
        }

        $groupIds = $user->groups->pluck('GroupID')->toArray();
        $enrollment = Enrollment::whereIn('GroupID', $groupIds)->first();

        if (!$enrollment) {
            throw new \Exception("No active enrollment found for this student's group.");
        }

        $rawFileName = $pdfFile->getClientOriginalName();
        $extractedTitle = pathinfo($rawFileName, PATHINFO_FILENAME);
        $proposalId = Str::random(10);

        $newProposal = Proposal::create([
            'ProposalID'     => $proposalId,
            'EnrollmentID'   => $enrollment->EnrollmentID,
            'ResearchTitle'  => $extractedTitle,
            'SubmissionDate' => now(),
            'Status'         => 'Pending',
        ]);

        $storageName = $proposalId . '_Proposal.' . $pdfFile->getClientOriginalExtension();
        $path = $pdfFile->storeAs('proposals', $storageName, 'public');

        Submission::create([
            'FileID'           => Str::random(10),
            'ProposalID'       => $proposalId,
            'DefenseID'        => null,
            'FileType'         => 'Proposal',
            'FilePath'         => $path,
            'UploadedByUserID' => $userID
        ]);

        return $newProposal;
    }

    public static function submitManuscript(int $userID, UploadedFile $pdfFile)
    {
        $user = User::with('groups')->find($userID);

        if (!$user) {
            throw new \Exception("User not found");
        }

        if ($user->groups->isEmpty()) {
            throw new \Exception("Student is not a member of any group.");
        }

        $groupIds = $user->groups->pluck('GroupID')->toArray();
        $enrollment = Enrollment::whereIn('GroupID', $groupIds)->first();

        if (!$enrollment) {
            throw new \Exception("No active enrollment found for this student's group.");
        }

        $rawFileName = $pdfFile->getClientOriginalName();
        $extractedTitle = pathinfo($rawFileName, PATHINFO_FILENAME);
        $proposalId = Str::random(10);

        $newProposal = Proposal::create([
            'ProposalID'     => $proposalId,
            'EnrollmentID'   => $enrollment->EnrollmentID,
            'ResearchTitle'  => $extractedTitle,
            'SubmissionDate' => now(),
            'Status'         => 'Pending',
        ]);

        $storageName = $proposalId . '_manuscript.' . $pdfFile->getClientOriginalExtension();
        $path = $pdfFile->storeAs('proposals', $storageName, 'public');

        Submission::create([
            'FileID'           => Str::random(10),
            'ProposalID'       => $proposalId,
            'DefenseID'        => null,
            'FileType'         => 'Proposal',
            'FilePath'         => $path,
            'UploadedByUserID' => $userID
        ]);

        return $newProposal;
    }

    public static function getFacultyList()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('Roles.RoleID', 2);
        })->with('roles')->get();
    }

    public static function setInvitation($userID)
    {
        $faculty = self::getFacultyList();
        $user = User::with('groups')->find($userID);
        $groupIds = $user->groups->pluck('GroupID')->first();
        $enrollment = Enrollment::where('GroupID', $groupIds)->first();
        $defenses = Proposal::with('defenses')->find($enrollment->EnrollmentID);
        $defense = $defenses->defenses->pluck('DefenseID')->first();

        foreach ($faculty as $panel) {
            DefensePanel::create([
                'DefenseID'      => $defense,
                'PanelistUserID' => $panel->UserID,
                'Status'         => 'Pending'
            ]);
        }
    }

    public static function getDefenseInvitation($userID)
    {
        $user = User::with('groups')->find($userID);
        $groupIds = $user->groups->pluck('GroupID')->first();
        $enrollment = Enrollment::where('GroupID', $groupIds)->first();
        $proposal = Proposal::with('defenses')->where('EnrollmentID', $enrollment->EnrollmentID)->first();

        $defenseIds = $proposal->defenses->pluck('DefenseID');
        
        return DefensePanel::whereIn('DefenseID', $defenseIds)->get();
    }

    public static function getDefenseEvaluation($userID)
    {
        $user = User::with('groups')->find($userID);
        $groupIds = $user->groups->pluck('GroupID')->first();
        $enrollment = Enrollment::where('GroupID', $groupIds)->first();
        $evaluation = Proposal::with('defenses.evaluations')->where('EnrollmentID', $enrollment->EnrollmentID)->get();
        $defenses = $evaluation->pluck('defenses')->flatten();

        return $defenses->pluck('evaluations')->flatten();
    }

    public static function checkProposalQuota($userID)
    {
        $user = User::with('groups')->find($userID);
        if (!$user || $user->groups->isEmpty()) return [];

        $groupIds = $user->groups->pluck('GroupID');
        $enrollmentIds = Enrollment::whereIn('GroupID', $groupIds)->pluck('EnrollmentID');
        if ($enrollmentIds->isEmpty()) return [];

        $proposals = Proposal::with('approvals')
            ->whereIn('EnrollmentID', $enrollmentIds)
            ->get();

        $report = $proposals->map(function ($proposal) {
            $totalVoters = $proposal->approvals->count();

            if ($totalVoters === 0) {
                return [
                    'proposal_id' => $proposal->ProposalID,
                    'title'       => $proposal->ResearchTitle ?? 'Untitled',
                    'is_approved' => false,
                    'status'      => 'No panelists assigned'
                ];
            }

            $approvedCount = $proposal->approvals->where('Status', 'Approved')->count();
            $threshold = floor($totalVoters / 2) + 1;
            $isApproved = $approvedCount >= $threshold;

            return [
                'proposal_id'    => $proposal->ProposalID,
                'title'          => $proposal->ResearchTitle ?? 'Untitled',
                'is_approved'    => $isApproved,
                'current_votes'  => $approvedCount,
                'required_votes' => $threshold,
                'status'         => $isApproved ? 'PASSED' : 'PENDING'
            ];
        });

        return $report->values()->toArray();
    }

    public static function deleteProposal($proposalId)
    {
        $delete = Proposal::find($proposalId);
        $delete->delete();

        return $proposalId;
    }

    public static function pickTitle($userID, $proposalId)
    {
        $proposal = Proposal::with('approvals')->find($proposalId);

        if (!$proposal) {
            throw new \Exception("Proposal not found.");
        }

        $totalVoters = $proposal->approvals->count();

        if ($totalVoters === 0) {
            throw new \Exception("This proposal has no panelists.");
        }

        $approvedCount = $proposal->approvals->where('Status', 'Approved')->count();
        $threshold = floor($totalVoters / 2) + 1;

        if ($approvedCount < $threshold) {
            throw new \Exception("This proposal is not eligible yet. It needs more approvals.");
        }

        $existingDefense = Defense::where('ProposalID', $proposalId)->first();

        if ($existingDefense) {
            return $existingDefense;
        }

        $newDefense = Defense::create([
            'DefenseID'   => Str::random(10),
            'ProposalID'  => $proposalId,
            'DefenseDate' => null,
            'EnrollmentID' => $proposal->EnrollmentID,
            'Status'      => 'Pending',
            'Type'        => 'Final'
        ]);

        $proposal->update(['Status' => 'Ready for Defense']);

        return $newDefense;
    }

    /*
    public static function getDefenseVerdict($userID)
    {
        $user = User::with('groups')->find($userID);
        if (!$user || $user->groups->isEmpty()) {
            throw new \Exception("Student is not in a group.");
        }

        $groupIds = $user->groups->pluck('GroupID');
        $enrollment = Enrollment::whereIn('GroupID', $groupIds)->first();

        if (!$enrollment) {
            throw new \Exception("No enrollment record found.");
        }

        $proposal = Proposal::with([
            'defenses' => function ($query) {
                $query->orderBy('created_at', 'desc'); 
            },
            'defenses.panels.user'
        ])->where('EnrollmentID', $enrollment->EnrollmentID)->first();

        if (!$proposal || $proposal->defenses->isEmpty()) {
            return [
                'status'  => 'No Defense Scheduled',
                'verdict' => 'N/A',
                'remarks' => []
            ];
        }

        $currentDefense = $proposal->defenses->first();

        $remarks = $currentDefense->panels->map(function ($panel) {
            return [
                'panelist' => $panel->user->FullName ?? 'Unknown Panelist',
                'verdict'  => $panel->Status, 
                'remarks'  => $panel->Remarks ?? 'No remarks provided.'
            ];
        });

        return [
            'defense_id'    => $currentDefense->DefenseID,
            'defense_date'  => $currentDefense->Schedule, 
            'final_verdict' => $currentDefense->OverallVerdict ?? 'Pending',
            'panel_reviews' => $remarks
        ];
    } */
}