<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

class ProfileSettingController extends Controller
{
    public function show()
    {
        return view('auth.mypage');
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $user->profile_completed = true;
        $user->save();

        return redirect()->route('top');
    }

}
