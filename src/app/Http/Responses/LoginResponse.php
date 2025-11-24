<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        if ($user && $user->hasVerifiedEmail()) {

            if (!$user->profile_completed) {
                return redirect()->route('profile.settings.show');
            }

            return redirect()->route('top');
        }

        return redirect()->route('verification.notice');
    }
}
