<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileSettingController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('auth.mypage-profile', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = $request->user();

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image_path) {
                Storage::delete($user->profile_image_path);
            }
            $path = $request->file('profile_image')->store('public/avatars');
            $user->profile_image_path = $path;
        }

        $user->username = $request->username;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building_name = $request->building_name;

        if (!$user->profile_completed) {
            $user->profile_completed = true;
        }
        $user->save();

        return redirect()->route('top');
    }
}
