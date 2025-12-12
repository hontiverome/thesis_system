<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdviserGroupController extends Controller
{
    public function getAdviserGroups(Request $request)
    {
        // Query: Kunin lahat ng groups
        // NOTE: Soon, dadagdagan natin to ng ->where('AdviserUserID', $user->id)
        
        $groups = DB::table('groups')
                    ->select('GroupID', 'GroupCode', 'YearLevel', 'AdviserUserID')
                    ->get();

        return response()->json([
            'status' => 'success',
            'data' => $groups
        ], 200);
    }
}