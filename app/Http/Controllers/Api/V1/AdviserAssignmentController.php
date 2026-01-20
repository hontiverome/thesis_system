<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdviserAssignmentController extends Controller
{
    /**
     * 1. Get List of Blocks (Sections).
     * Returns all blocks and their current adviser (if any).
     */
    public function getBlocksForDropdown(Request $request)
    {
        // Join Blocks with Users to show the current adviser's name
        $blocks = DB::table('Blocks')
            ->leftJoin('Users', 'Blocks.AdviserUserID', '=', 'Users.UserID')
            ->select(
                'Blocks.BlockID',
                'Blocks.BlockName',
                'Blocks.AdviserUserID',
                'Users.FullName as AdviserName'
            )
            ->orderBy('Blocks.BlockName')
            ->get();

        return response()->json([
            'message' => 'Blocks retrieved successfully.',
            'data' => $blocks
        ]);
    }

    /**
     * 2. Assign Adviser to a Block.
     * Updates the 'Blocks' table AND syncs the 'Groups' table.
     */
    public function assignAdviserToBlock(Request $request)
    {
        try {
            $user = $request->user();

            // A. Authorization: Check if user is Chairperson (5) or Admin (6)
            $isAuthorized = DB::table('UserRoles')
                ->where('UserID', $user->UserID)
                ->whereIn('RoleID', [5,6]) 
                ->exists();

            if (!$isAuthorized) {
                return response()->json(['message' => 'Forbidden. Only Chairpersons can assign advisers.'], 403);
            }

            // B. Validation
            $validator = Validator::make($request->all(), [
                'faculty_id' => 'required|exists:Users,UserID',
                'block_id'   => 'required|exists:Blocks,BlockID', 
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // C. Workload Check (Max 3 Blocks per Adviser)
            $MAX_BLOCKS = 3;

            // Count how many blocks this faculty is currently holding
            $currentBlockLoad = DB::table('Blocks')
                ->where('AdviserUserID', $request->faculty_id)
                ->count();

            // Note: If we are just updating the SAME block, don't count it as "new" load
            $isSameBlock = DB::table('Blocks')
                ->where('BlockID', $request->block_id)
                ->where('AdviserUserID', $request->faculty_id)
                ->exists();

            if ($currentBlockLoad >= $MAX_BLOCKS && !$isSameBlock) {
                return response()->json([
                    'message' => 'Cannot assign adviser.',
                    'error' => "Faculty has reached the maximum block load ({$currentBlockLoad}/{$MAX_BLOCKS})."
                ], 400);
            }

            // D. The Update Transaction (To ensure data consistency)
            DB::transaction(function () use ($request) {
                
                // 1. Update the MAIN Blocks table
                DB::table('Blocks')
                    ->where('BlockID', $request->block_id)
                    ->update(['AdviserUserID' => $request->faculty_id]);

                // 2. Cascade update to GROUPS table
                // Since the groups belong to this block, they must inherit the adviser
                DB::table('Groups')
                    ->where('BlockID', $request->block_id)
                    ->update(['AdviserUserID' => $request->faculty_id]);
                    
                // 3. Cascade update to GroupAdvisers table (if you use that strictly)
                // First, find the GroupIDs involved
                $groupIds = DB::table('Groups')->where('BlockID', $request->block_id)->pluck('GroupID');
                
                if ($groupIds->isNotEmpty()) {
                    // Remove old adviser entries for these groups
                    DB::table('GroupAdvisers')->whereIn('GroupID', $groupIds)->delete();
                    
                    // Add new adviser entries
                    $newEntries = [];
                    foreach ($groupIds as $gid) {
                        $newEntries[] = [
                            'GroupID' => $gid,
                            'AdviserUserID' => $request->faculty_id
                        ];
                    }
                    DB::table('GroupAdvisers')->insert($newEntries);
                }
            });

            return response()->json([
                'message' => 'Adviser assigned to block and all sub-groups successfully.',
                'data' => [
                    'block_id' => $request->block_id,
                    'new_adviser_id' => $request->faculty_id
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error', 'error' => $e->getMessage()], 500);
        }
    }
}