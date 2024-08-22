<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request, string $id, string $hash)
    {
        $user = User::find($id);
        if(!$user) return redirect(config('app.frontend_url'));

        if (! hash_equals((string) $user->getKey(), (string) $request->route('id'))) {
            return redirect(config('app.frontend_url'));
        }

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $request->route('hash'))) {
            return redirect(config('app.frontend_url'));
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(config('app.frontend_url'));
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect(config('app.frontend_url'));
    }
}
