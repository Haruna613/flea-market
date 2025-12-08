<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $listedItems = $user->items;

        $purchasedItems = $user->purchases;

        return view('auth.mypage', compact('listedItems', 'purchasedItems'));
    }
}
