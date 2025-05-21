<?php 


namespace App\Service;

use App\Models\Notification;



class NotifactionService
{
    // Notificationlarni olish
    public function getAllNotifications($user)
    {
        return $user->notifications; // Barcha notificationlarni olish
    }

    // Yangi notification yaratish
    public function createNotification($user, $data)
    {
        $user->notify(new \App\Notifications\OrderNotifactions($data)); // Yangi notification yuborish
    }

    // Notificationni o'chirish
    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->delete();
        }
    }
}
