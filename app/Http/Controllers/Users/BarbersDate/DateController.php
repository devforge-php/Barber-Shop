<?php

namespace App\Http\Controllers\Users\BarbersDate;

use Illuminate\Routing\Controller;
use App\Models\WorkSchedule;
use App\Models\DayOff;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DateController extends Controller
{
  public function getAvailableTimesForDate(Request $request, int $userId): JsonResponse
{
    // Barber mavjudligini tekshiramiz
    $barber = \App\Models\User::find($userId);
    if (!$barber) {
        return response()->json(['error' => 'Barber not found'], 404);
    }

    // So'rovda berilgan sanani olish
    $date = $request->query('date');

    // Sana to'g'riligini tekshirish
    if (!$date || !@Carbon::hasFormat($date, 'Y-m-d')) {
        return response()->json(['error' => 'Valid date is required (format: Y-m-d)'], 400);
    }

    $parsedDate = Carbon::parse($date)->toDateString();

    // Dam olish kuni ekanini tekshirish
    $isDayOff = DayOff::where('user_id', $userId)
        ->where('day_off', $parsedDate)
        ->exists();
    if ($isDayOff) {
        return response()->json([
            'barber_name' => $barber->name,
            'available_times' => [],
            'message' => 'Barber has a day off on this date',
        ]);
    }

    // Ish jadvalini topish
    $schedule = WorkSchedule::where('user_id', $userId)
        ->where('date', $parsedDate)
        ->first();

    if (!$schedule) {
        return response()->json([
            'barber_name' => $barber->name,
            'available_times' => [],
            'message' => 'No work schedule for this date',
        ]);
    }

    // Barcha bron qilingan vaqtlarni bir marta olib qo'yamiz
    $bookedTimes = Booking::where('user_id', $userId)
        ->where('booking_time', 'like', "$parsedDate%")
        ->pluck('booking_time')
        ->map(fn($t) => Carbon::parse($t)->toDateTimeString())
        ->toArray();

    // Soatlarni generatsiya qilish
    $startTime = Carbon::createFromFormat('H:i:s', $schedule->start_time);
    $endTime = Carbon::createFromFormat('H:i:s', $schedule->end_time);

    $startDate = Carbon::parse($parsedDate . ' ' . $startTime->format('H:i:s'));
    $endDate = Carbon::parse($parsedDate . ' ' . $endTime->format('H:i:s'));

    $availableTimes = [];

    while ($startDate < $endDate) {
        $bookingTime = $startDate->copy()->toDateTimeString();

        if (!in_array($bookingTime, $bookedTimes)) {
            $availableTimes[] = $startDate->format('H:i');
        }

        $startDate->addHour();
    }

    return response()->json([
        'barber_name' => $barber->name,
        'available_times' => $availableTimes
    ]);
}
}