<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

class PasswordResetController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('Email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'We can\'t find a user with that email address.'], 404);
        }

        $token = Str::random(60);

        DB::table('PasswordResets')->insert([
            'UserID' => $user->UserID,
            'ResetToken' => $token,
            'CreatedAt' => now(),
            'ExpiresAt' => now()->addHours(1),
        ]);

        // Attempt to send mail if mailer is configured; ignore failures in dev
        try {
            Mail::to($user->Email)->send(new PasswordResetMail($token));
        } catch (\Throwable $e) {
            // For now, silently ignore mail errors so API remains usable without mail setup
        }

        // FOR DEVELOPMENT
        return response()->json([
            'message' => 'We have generated a password reset token.',
            'reset_token' => $token,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::where('Email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'We can\'t find a user with that email address.'], 404);
        }
        
        $resetRecord = DB::table('PasswordResets')
            ->where('UserID', $user->UserID)
            ->where('ResetToken', $request->token)
            ->first();

        if (!$resetRecord || $resetRecord->IsUsed || now()->isAfter($resetRecord->ExpiresAt)) {
            return response()->json(['message' => 'This password reset token is invalid.'], 400);
        }

        $user->forceFill([
            'PasswordHash' => $request->password
        ])->save();

        DB::table('PasswordResets')->where('ResetID', $resetRecord->ResetID)->update(['IsUsed' => true]);

        return response()->json(['message' => 'Your password has been reset!']);
    }
}
