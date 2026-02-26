<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Message;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ReviewReceivedNotification;

class ChatController extends Controller
{
    public function index($item_id)
    {
        $user = Auth::user();
        $item = Item::with(['messages.user', 'user', 'order'])->findOrFail($item_id);

        $otherItems = Item::where('id', '!=', $item_id)
        ->whereHas('order', function($q) {
            $q->where('status', '!=', 'completed');
        })
        ->where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhereHas('order', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
        })
        ->with('order')
        ->get();

        $isSeller = $item->user_id === $user->id;
        $isBuyer = $item->order && $item->order->user_id === $user->id;

        Message::where('item_id', $item_id)
        ->where('user_id', '!=', Auth::id())
        ->whereNull('read_at')
        ->update(['read_at' => now()]);

        if (!$isSeller && !$isBuyer) {
            abort(403, 'このチャットにアクセスする権限がありません。');
        }

        return view('chat.show', compact('item','otherItems', 'isSeller', 'isBuyer'));
    }

    public function store(MessageRequest $request, $item_id)
    {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('messages', 'public');
        }

        Message::create([
            'item_id' => $item_id,
            'user_id' => Auth::id(),
            'message_body' => $request->message_body ?? '',
            'image_path' => $imagePath,
        ]);

        return back();
    }

    public function update(Request $request, Message $message)
    {
        $this->authorize('update', $message);

        $message->update(['message_body' => $request->message_body]);

        return back();
    }

    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);

        if ($message->image_path) {
            Storage::disk('public')->delete($message->image_path);
        }
        $message->delete();

        return back();
    }

    public function storeReview(Request $request, $order_id)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $order = Order::with('item.user')->findOrFail($order_id);
        $user = auth()->user();

        Review::create([
            'order_id' => $order->id,
            'reviewer_id' => $user->id,
            'reviewee_id' => ($user->id === $order->user_id) ? $order->item->user_id : $order->user_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        if ($user->id === $order->user_id) {
            $order->update(['status' => 'awaiting_review']);
            $seller = $order->item->user;
            $seller->notify(new ReviewReceivedNotification($order));
        } else {
            $order->update(['status' => 'completed']);
        }

        return redirect()->route('top');
    }
}
