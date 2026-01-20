<?php

namespace App\Services\StudentServices;

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
    public static function getGroupProposal(int $userID) # Get the proposals of the group from Proposals table
    {
        $user = User::with('groups')->find($userID);
        $groupIds = $user->groups->pluck('GroupID')->first();
        $enrollment = Enrollment::where('GroupID', $groupIds)->first();
        $evaluation = Proposal::with('submissions')->where('EnrollmentID', $enrollment->EnrollmentID)->get();
        
        $evaluation->each(function ($proposal) {
            $proposal->submissions->each(function ($submission) {
                $submission->FileUrl = url('storage/' . $submission->FilePath);
            });
        });

        return $evaluation->flatten();
    }

    public static function getGroupProposalRecords(int $userID) # Get the record of Proposal Approval from ProposalApprovals table
    {
        $user = User::with('groups')->find($userID);
        $groupIds = $user->groups->pluck('GroupID')->first();
        $enrollment = Enrollment::where('GroupID', $groupIds)->first();
        $evaluation = Proposal::with('approvals.approvedUser')->where('EnrollmentID', $enrollment->EnrollmentID)->get();

        $return = [];
        foreach ($evaluation as $proposal) {
            $approvals = $proposal->approvals->map(function ($approval) {
                return [
                    'ApprovalID' => $approval->ApprovalID,
                    'Name'    => $approval->approvedUser->FullName ?? 'Unknown',
                    'Status'  => $approval->Status,
                    'Remarks' => $approval->Remarks
                ];
            });
            $return[$proposal->ProposalID] = $approvals;
        }
        
        return $return;
    }

    public static function getGroupManuscript($groupId)
    {
        $enrollment = Enrollment::where('GroupID', $groupId)->first();
        
        if (!$enrollment) {
            return new Collection();
        }

        $defense = Defense::where('EnrollmentID', $enrollment->EnrollmentID)
            ->where('DefenseType', 'MOR')
            ->first();
        
        if (!$defense) {
            return new Collection();
        }

        $manuscripts = Submission::where('DefenseID', $defense->DefenseID)
            ->where('FileType', 'Manuscript')
            ->get();
        
        $manuscripts->each(function ($submission) {
            $submission->FileUrl = url('storage/' . $submission->FilePath);
            $uploader = User::find($submission->UploadedByUserID);
            $submission->UploadedBy = $uploader ? $uploader->FullName : 'Unknown';
        });

        return $manuscripts;
    }


    public static function checkProposalQuota($userID, $proposalId = null)
    {
        $user = User::with('groups')->find($userID);
        if (!$user || $user->groups->isEmpty()) return [];

        $groupIds = $user->groups->pluck('GroupID');
        $enrollmentIds = Enrollment::whereIn('GroupID', $groupIds)->pluck('EnrollmentID');
        if ($enrollmentIds->isEmpty()) return [];

        $requiredTotal = User::whereHas('roles', function ($query) {
            $query->whereIn('Roles.RoleID', [3, 5]);
        })->count();
        $threshold = floor($requiredTotal / 2) + 1;

        if ($proposalId) {
            $proposal = Proposal::with('approvals.approvedUser')->where('ProposalID', $proposalId)->whereIn('EnrollmentID', $enrollmentIds)->first();
            if (!$proposal) return [];

            $validVotes = $proposal->approvals->whereIn('Status', ['Approved', 'Rejected']);
            $voteCount = $validVotes->count();

            if ($voteCount < $requiredTotal) {
                $proposal->status = 'Pending';
                $proposal->save();

                $proposal->approvals->transform(function ($approval) {
                    $approval->UserID = $approval->approvedUser->UserID ?? null;
                    $approval->FullName = $approval->approvedUser->FullName ?? null;
                    $approval->unsetRelation('approvedUser');
                    return $approval;
                });

                return $proposal;
            }

            $approvedCount = $validVotes->where('Status', 'Approved')->count();
            $isApproved = $approvedCount >= $threshold;

            $proposal->status = $isApproved ? 'Passed' : 'Rejected';
            $proposal->save();

            $proposal->approvals->transform(function ($approval) {
                $approval->UserID = $approval->approvedUser->UserID ?? null;
                $approval->FullName = $approval->approvedUser->FullName ?? null;
                $approval->unsetRelation('approvedUser');
                return $approval;
            });

            return $proposal;
        }

        $proposals = Proposal::with('approvals.approvedUser')
            ->whereIn('EnrollmentID', $enrollmentIds)
            ->get();

        $report = $proposals->map(function ($proposal) use ($requiredTotal, $threshold) {
            $validVotes = $proposal->approvals->whereIn('Status', ['Approved', 'Rejected']);

            $voteCount = $validVotes->count();

            if ($voteCount < $requiredTotal) {
                $proposal->approvals->transform(function ($approval) {
                    $approval->UserID = $approval->approvedUser->UserID ?? null;
                    $approval->FullName = $approval->approvedUser->FullName ?? null;
                    $approval->unsetRelation('approvedUser');
                    return $approval;
                });
                return $proposal;
            }

            $approvedCount = $validVotes->where('Status', 'Approved')->count();
            $isApproved = $approvedCount >= $threshold;

            $proposal->status = $isApproved ? 'Passed' : 'Rejected';
            $proposal->save();

            $proposal->approvals->transform(function ($approval) {
                $approval->UserID = $approval->approvedUser->UserID ?? null;
                $approval->FullName = $approval->approvedUser->FullName ?? null;
                $approval->unsetRelation('approvedUser');
                return $approval;
            });

            return $proposal;
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
        $proposal = Proposal::find($proposalId);
        if (!$proposal) {
            throw new \Exception("Proposal not found.");
        }

        $quota = self::checkProposalQuota($userID, $proposalId);
        if (empty($quota)) {
            throw new \Exception("Unable to evaluate proposal quota for this proposal.");
        }

        if (($quota['status'] ?? '') !== 'Passed') {
            throw new \Exception("Only proposals with status 'PASSED' can be selected. Current status: " . ($quota['status'] ?? 'UNKNOWN'));
        }

        $existingDefense = Defense::where('ProposalID', $proposalId)->first();
        if ($existingDefense) {
            return $existingDefense;
        }

        $nextDefenseId = (Defense::max('DefenseID') ?? 0) + 1;

            $newDefense = new Defense();
            $newDefense->DefenseID = $nextDefenseId;
            $newDefense->ProposalID = $proposalId;
            $newDefense->Schedule = null;
            $newDefense->EnrollmentID = $proposal->EnrollmentID;
            $newDefense->DefenseType = 'MOR';
            $newDefense->OverallVerdict = 'Pending';
            $newDefense->incrementing = false;
            $newDefense->save();

            $panelistIds = User::whereHas('roles', function ($query) {
                $query->whereIn('Roles.RoleID', [3, 4, 5]);
            })->pluck('UserID');
            foreach ($panelistIds as $panelistId) {
                DefensePanel::create([
                    'DefenseID' => $newDefense->DefenseID,
                    'PanelistUserID' => $panelistId,
                    'Status' => 'Pending',
                ]);
            }

        return $newDefense;
    }

    


}