<?php

namespace Politie\Services;

use Illuminate\Support\Facades\Http;

class GeoService
{
    public function query(string $geoquery)
    {
        // @todo do geoquery

        // @todo object geoLocation
        return [
            'lat' => 51.529930,
            'lon' => 5.086950,
        ];
        // return [
        //     'lat' => 53.1511173,
        //     'lon' => 6.756634599999984,
        // ];
    }

    private static function makePlaceApiCall($endpoint, array $queryParameters)
    {
        return self::makeApiCall('place/' . $endpoint, $queryParameters);
    }

    private static function makeApiCall(string $endpoint, array $queryParameters): array
    {
        $queryParameters += [
            'key' => config('google.key'),
            'language' => 'nl',
        ];

        $response = Http::get('https://maps.googleapis.com/maps/api/' . $endpoint . '/json', ['query' => $queryParameters]);

        return json_decode((string) $response->getBody());
    }
}
