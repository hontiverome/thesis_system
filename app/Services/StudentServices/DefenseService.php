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

class DefenseService
{       

    public static function getFacultyList()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('Roles.RoleID', 4);
        })->with('roles')->get();
    }


    public static function getDefenseInvitation($userID)
    {
        $user = User::with('groups')->find($userID);
        $groupIds = $user->groups->pluck('GroupID')->first();
        $enrollment = Enrollment::where('GroupID', $groupIds)->first();
        $proposal = Proposal::with('defenses')->where('EnrollmentID', $enrollment->EnrollmentID)->first();

        $defenseId = $proposal->defenses->pluck('DefenseID');

        if (DefensePanel::where('Status', 'Accepted')->count() == 3) {
            $invitation = DefensePanel::where('DefenseID', $defenseId)->where('Status', 'Pending')->update(['Status' => 'Canceled']);
        }

        $invitation = DefensePanel::with('user')->whereIn('DefenseID', $defenseId)->get()->map(function ($panel) {
            return [
                'FullName' => $panel->user->FullName,
                'Status'   => $panel->Status,
            ];
        });
        $accepted = DefensePanel::with('user')->where('DefenseID', $defenseId)->where('Status', 'Accepted')->get()->map(function ($panel) {
           return [
                'FullName' => $panel->user->FullName,
                'Status'   => $panel->Status,
            ];
        });

        return ['invitation_list'=>$invitation, 'invitation_accepted'=>$accepted];
    }

    public static function getDefenseVerdict($userID)
    {
        $user = User::with('groups')->find($userID);
        $groupIds = $user->groups->pluck('GroupID')->first();
        $enrollment = Enrollment::where('GroupID', $groupIds)->first();
        
        $defense = Defense::with('evaluations')
            ->where('EnrollmentID', $enrollment->EnrollmentID)
            ->where('DefenseType', 'MOR')
            ->first();

        return $defense ? $defense->evaluations : new Collection();
    }

    public static function getDefenseEvaluation($userID)
    {
        $evaluations = self::getDefenseVerdict($userID);

        if ($evaluations->isEmpty()) {
            return 'Pending';
        }

        $acceptedCount = $evaluations->where('Verdict', 'Accepted')->count();
        $total = $evaluations->count();

        return ($total > 0 && $acceptedCount >= ceil($total * (2/3))) ? 'Accepted' : 'Rejected';
    }

    public static function getEvaluationFiles($userID)
    {
        $user = User::with('groups')->find($userID);
        $groupIds = $user->groups->pluck('GroupID')->first();
        $enrollment = Enrollment::where('GroupID', $groupIds)->first();
        
        if (!$enrollment) {
            return new Collection();
        }

        $defenseIds = Defense::where('EnrollmentID', $enrollment->EnrollmentID)->pluck('DefenseID');

        return Submission::whereIn('DefenseID', $defenseIds)
            ->where('FileType', 'Evaluation')
            ->get()
            ->map(function ($submission) {
                $submission->FileUrl = url('storage/' . $submission->FilePath);
                return $submission;
            });
    }
}
