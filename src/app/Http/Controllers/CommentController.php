<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $item)
    {

        $item->comments()->create([
            'user_id' => Auth::id(),
            'comment_body' => $request->comment_body,
        ]);

        return back();
    }
}
