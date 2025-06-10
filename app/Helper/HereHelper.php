<?php

namespace App\Helper;

use Illuminate\Support\Facades\Http;

class HereHelper
{
    public static function getRoute($originLat, $originLng, $destLat, $destLng)
    {
        $response = Http::get('https://router.hereapi.com/v8/routes', [
            'transportMode' => 'scooter',
            'origin' => "{$originLat},{$originLng}",
            'destination' => "{$destLat},{$destLng}",
            'return' => 'summary,polyline',
            'apiKey' => env('HERE_MAP_KEY', 'HxCn0uXDho1pV2wM59D_QWzCgPtWB_E5aIiqIdnBnV0'),
        ]);

        if (!$response->successful()) {
            throw new \Exception('HERE API failed');
        }

        $data = $response->json();
        $section = $data['routes'][0]['sections'][0];
        $summary = $section['summary'];

        $distance = $summary['length']; // meters
        $duration = ceil($summary['duration'] / 60); // minutes
        $polyline = $section['polyline'] ?? '';

        return [
            'distance' => $distance,
            'duration' => $duration,
            'polyline' => $polyline,
            'raw' => $data
        ];
    }

    public static function calculateShippingFee($distanceInMeters)
    {
        $shipFeeKm = (int)(\DB::table('settings')->where('key', 'fee_km')->value('value') ?? 2000);

        $distanceInKm = $distanceInMeters / 1000;

        if ($distanceInKm <= 2) {
            return $shipFeeKm;
        }

        $extraKm = ceil($distanceInKm - 2); // Làm tròn lên để tính phí chính xác
        return $shipFeeKm + ($extraKm * 1000);
    }
}
