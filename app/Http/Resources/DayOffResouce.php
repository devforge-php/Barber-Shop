<?php

// App\Http\Resources\DayOffResouce.php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DayOffResouce extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'day_off' => $this->day_off,
            'created_at' => $this->created_at,
        ];
    }
}