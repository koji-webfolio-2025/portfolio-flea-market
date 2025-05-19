<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmail extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // メールで通知を送信
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('メール認証をお願いします')
            ->line('このメールアドレスに関連付けられたアカウントを認証するには、以下のリンクをクリックしてください。')
            ->action('認証する', url('/email/verify/' . $notifiable->id . '/' . sha1($notifiable->email)))
            ->line('もしこのメールに心当たりがない場合は、無視してください。');
    }
}
