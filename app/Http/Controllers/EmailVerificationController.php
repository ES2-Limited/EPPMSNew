<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\APIMail;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    public function verify($ulid, $token)
    {
        $user = User::where('ulid', $ulid)->where('verification_token', $token)->first();

        if (!$user) {
            return redirect('/')->with('error', 'Invalid verification link.');
        }

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        return redirect('/')->with('message', 'Your email has been verified!');
    }

    public function resend_email()
    {
        return view('auth.verify');
    }

    public function resend()
    {

        $user = Auth::user();

        $user->generateVerificationToken();

        $sendmail = new APIMail;
        $sendmail->sendMail($user, 'verify');

        return back()->with('resent', 'Verification email has been sent!');
    }
}
