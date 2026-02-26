<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
{
    $user = Auth::user();

    $listedItems = $user->items;
    $purchasedItems = $user->purchasedItems;

    $tradingItems = Item::whereHas('order', function($query) {
            $query->where('status', '!=', 'completed');
        })
        ->where(function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhereHas('order', function($oq) use ($user) {
                  $oq->where('user_id', $user->id);
              });
        })->get();

    foreach ($tradingItems as $item) {
        $item->individual_unread_count = Message::where('item_id', $item->id)
            ->where('user_id', '!=', $user->id)
            ->whereNull('read_at')
            ->count();
        $latestMessage = Message::where('item_id', $item->id)->latest()->first();
        $item->sort_date = $latestMessage ? $latestMessage->created_at : '2000-01-01 00:00:00';
    }
    $tradingItems = $tradingItems->sortByDesc('sort_date');

    $unreadCount = $tradingItems->sum('individual_unread_count');

    return view('auth.mypage', compact(
        'listedItems',
        'purchasedItems',
        'tradingItems',
        'unreadCount'
    ));
}
}
