<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Item $item)
    {
        $user = Auth::user();

        if ($item->isLikedBy($user->id)) {

            $item->likes()->where('user_id', $user->id)->delete();
            return response()->json(['status' => 'unliked']);
        } else {

            $item->likes()->create(['user_id' => $user->id]);
            return response()->json(['status' => 'liked']);
        }
    }
}
