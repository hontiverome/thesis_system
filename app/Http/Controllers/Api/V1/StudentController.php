<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\StudentServices\StudentProposalService;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Proposal;
use App\Services\StudentServices\DefenseService;
use App\Services\StudentServices\SubmitFileService;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {   
        $userID = Auth::user()->UserID;
        $user = User::with(['roles', 'groups'])->find($userID);

        if (!$user) {
            abort(404, 'User not found');
        }

        if ($user->hasRole('Student') || $user->hasRole('GroupLeader')) {
            $group = $user->groups->first();
            $groupRole = $group->pivot->GroupRole ?? 'Member';
            if (in_array($groupRole, ['Leader', 'GroupLeader'])) {
                return /*view('student.leader_dashboard')*/ 'Leader Dashboard'; 
            } elseif ($groupRole === 'Member') {
                return /*view('student.member_dashboard')*/ 'Member Dashboard'; 
            }
        }
        return view('common.dashboard') ?? 'Common Dashboard'; 
    }

    public function displayInfo()
    {
        $userID = Auth::user()->UserID;
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

        return response()->json(['message' => $studentData], 200);
    }

    public function getProposal($groupId)
    {
        $userID = Auth::user()->UserID;
        $proposals = StudentProposalService::getGroupProposal($userID);

        return response()->json([$proposals]);
    }

    public function getManuscript($groupId)
    {   
        try {
            $userID = Auth::user()->UserID;
            $manuscripts = StudentProposalService::getGroupManuscript($groupId);
            
            if (isset($manuscripts)) {
                return response()->json(['message' => 'No manuscript found.'], 404);
            }

            return response()->json($manuscripts);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }

        
    }

    public function faculty () {
        try {
            $userID = Auth::user()->UserID;
            $facultyData = DefenseService::getFacultyList($userID);

            return response()->json([
                'Faculty List' => $facultyData
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function invitation()
    {
        $userID = Auth::user()->UserID;
        $inv = DefenseService::getDefenseInvitation($userID);
        $list = $inv['invitation_list'];
        $accepted = $inv['invitation_accepted'];

        return response()->json(["Panel Invitation"=>$list, "Panel Approval"=>$accepted], 200);
    }

    public function getDefenseVerdict($groupId)
    {
        try {
            $userID = Auth::user()->UserID;
            $verdictData = DefenseService::getDefenseEvaluation($userID);

            return response()->json([
                'Defense Verdict' => $verdictData
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function defenseEvalutaion () {
         try {
        $userID = Auth::user()->UserID;
        $facultyData = DefenseService::getEvaluationFiles($userID);

        if (isset($facultyData)) {
            return response()->json(['Message' => 'No evaluation files found.'], 404);
        }

        return response()->json([
            'Evaluation Files' => $facultyData
        ], 200);
        
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }

    }


}