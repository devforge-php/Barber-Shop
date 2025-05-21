<?php

namespace App\Service;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Service;
use App\Models\User;
use App\Notifications\OrderNotifactions;
use Illuminate\Support\Facades\DB;


class AdminBronService
{
    public function adminBronPost(array $validated)
{
    return DB::transaction(function () use ($validated) {
        $booking = Booking::create([
            'user_id' => $validated['user_id'],
            'user_name' => $validated['user_name'],
            'user_phone' => $validated['user_phone'],
            'booking_time' => $validated['booking_time'],
        ]);

        // Xizmatlarni qo'shish
        $serviceIds = collect($validated['services'])->pluck('id')->toArray();
        $services = Service::whereIn('id', $serviceIds)->get();

        foreach ($services as $service) {
            BookingService::create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'price' => $service->price,
            ]);
        }

        // Xizmatlar bilan bookingni qayta yuklash
        $booking->load('services', 'user');

        // Endi notification yuborish
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderNotifactions($booking));
        }

        $barber = User::find($booking->user_id);
        if ($barber) {
            $barber->notify(new OrderNotifactions($booking));
        }

        return $booking;
    });
}

}



?>