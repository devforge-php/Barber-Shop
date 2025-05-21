<?php

namespace App\Http\Controllers\Admin\Notifactions;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotifactionResource;
use App\Models\Notification;
use App\Service\NotifactionService;
use Illuminate\Http\Request;

class NotifactionController extends Controller
{
    protected $notifactionService;

    public function __construct(NotifactionService $notifactionService)
    {
        $this->notifactionService = $notifactionService;
    }

    // Notificationlarni olish
    public function index(Request $request)
    {
        $user = auth()->user(); // Hozirgi foydalanuvchi
        $notifications = $this->notifactionService->getAllNotifications($user);

        return NotifactionResource::collection($notifications); // Chiroyli formatda qaytarish
    }

    // Bitta notificationni ko'rsatish
    public function show(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
    
        // Agar hali o'qilmagan bo'lsa, o'qilgan deb belgilaymiz
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }
    
        return new NotifactionResource($notification);
    }
    

    // Notificationni o'chirish
    public function destroy(string $id)
    {
        $this->notifactionService->deleteNotification($id);
        return response()->json(['message' => 'Notification deleted successfully']);
    }
}
