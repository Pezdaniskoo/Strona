<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class HolidayService
{
    public function isHoliday(?string $date, ?string $country = null): bool
    {
        if (! $date) {
            return false;
        }

        $carbon = Carbon::parse($date);
        $countryCode = strtoupper($country ?: config('todo.holiday_api.default_country', 'PL'));
        $key = sprintf('holidays_%s_%s', $countryCode, $carbon->year);

        $holidays = Cache::remember($key, now()->addHours(12), function () use ($countryCode, $carbon) {
            $response = Http::timeout(10)
                ->get(rtrim(config('todo.holiday_api.base_url'), '/')."/PublicHolidays/{$carbon->year}/{$countryCode}");

            if (! $response->ok()) {
                return [];
            }

            return collect($response->json())
                ->pluck('date')
                ->filter()
                ->values()
                ->all();
        });

        return in_array($carbon->toDateString(), $holidays, true);
    }
}
