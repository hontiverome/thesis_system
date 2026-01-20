<?php

namespace App\Services\StudentServices;

use App\Models\Proposal;
use App\Models\User;
use App\Models\Role;
use App\Models\ProposalApproval;
use App\Models\Submission;
use App\Models\Enrollment;
use App\Models\DefensePanel;
use App\Models\Defense;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class SubmitFileService
{
    public static function submitFile(int $userID, UploadedFile $pdfFile, string $fileType, ?string $proposalID = null, ?string $defenseID = null)
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

        $fileType = substr((string) $fileType, 0, 50);

        $typeConfig = [
            'Proposal' => ['folder' => 'proposals', 'attach_proposal' => true, 'attach_defense' => false, 'dbType' => 'Proposal'],
            'Manuscript' => ['folder' => 'manuscripts', 'attach_proposal' => true, 'attach_defense' => true, 'dbType' => 'Manuscript'],
            'Defense' => ['folder' => 'defenses', 'attach_proposal' => false, 'attach_defense' => true, 'dbType' => 'Defense'],
        ];

        $config = $typeConfig[$fileType] ?? ['folder' => 'submissions', 'attach_proposal' => false, 'attach_defense' => false, 'dbType' => $fileType];

        return DB::transaction(function () use ($pdfFile, $userID, $config, $proposalID, $defenseID) {
            $fileId = (Submission::max('FileID') ?? 0) + 1;

            $extension = $pdfFile->getClientOriginalExtension();
            $slug = Str::slug(pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME));
            $storageName = $fileId . '_' . $slug . '.' . $extension;
            $path = $pdfFile->storeAs($config['folder'], $storageName, 'public');

            $submission = Submission::create([
                'FileID' => $fileId,
                'ProposalID' => $config['attach_proposal'] ? $proposalID : null,
                'DefenseID' => $config['attach_defense'] ? $defenseID : null,
                'FileType' => $config['dbType'],
                'FilePath' => $path,
                'UploadedByUserID' => $userID,
            ]);

            return $submission;
        });
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
        $proposalId = (Proposal::max('ProposalID') ?? 0) + 1;

        $newProposal = Proposal::create([
            'ProposalID'     => $proposalId,
            'EnrollmentID'   => $enrollment->EnrollmentID,
            'ResearchTitle'  => $extractedTitle,
            'SubmissionDate' => now(),
            'Status'         => 'Pending',
        ]);

        $approvers = User::whereHas('roles', function ($query) {
            $query->where('Roles.RoleID', 3);
        })->pluck('UserID');

        $roleName = Role::where('RoleID', 3)->value('RoleName') ?? 'Adviser';
        foreach ($approvers as $approverId) {
            $approvalId = (ProposalApproval::max('ApprovalID') ?? 0) + 1;
            DB::table('ProposalApprovals')->insert([
                'ApprovalID' => $approvalId,
                'ProposalID' => $proposalId,
                'ApprovedUserID' => $approverId,
                'ApprovalRole' => $roleName,
                'Status' => 'Pending',
            ]);
        }

        $submission = self::submitFile($userID, $pdfFile, 'Proposal', $proposalId, null);
        $newProposal->submission = $submission;

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

        $defense = Defense::where('EnrollmentID', $enrollment->EnrollmentID)
            ->where('DefenseType', 'MOR')
            ->first();

        if (!$defense) {
            throw new \Exception("No MOR defense record found for this group. Call pickTitle() to create one first.");
        }

        $submission = self::submitFile($userID, $pdfFile, 'Manuscript', $defense->ProposalID, $defense->DefenseID);

        return $submission;
    }
}