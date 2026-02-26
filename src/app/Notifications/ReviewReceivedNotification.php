<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class ReviewReceivedNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $itemName = $this->order->item->name;
        return (new MailMessage)
            ->subject('【重要】購入者からの評価が届きました')
            ->greeting('いつもご利用ありがとうございます。')
            ->line("商品「{$itemName}」の購入者から評価が届きました。")
            ->line('チャット画面から購入者の評価を行い、取引を完了させてください。')
            ->action('取引チャットを確認する', route('chat.show', ['item_id' => $this->order->item_id]))
            ->line('評価が完了するまで売上金は反映されません。');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
