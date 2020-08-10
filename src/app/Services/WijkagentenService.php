<?php

namespace Politie\Services;

use App\Models\Adre;

use App\Models\Afbeelding;
use App\Models\Locaty;
use App\Models\wijkagenten;
use App\Models\wijkagentenLocaty;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class wijkagentenService
{
    const URLBASE='https://api.politie.nl/v4/wijkagenten';

    private $geoService;

    public function __construct(GeoService $geoService)
    {
        $this->geoService = $geoService;
    }

    public function query(int $offset = 0, float $lat = null, float $lon = null, float $radius = null)
    {
        // @todo validate params

        $language = 'nl';
        // $lat=53.1511173;
        // $lon=6.756634599999984;
        // $radius=5.0;  # 0.5, 2.0, 5.0, 10.0 and 25.0. default 5.0
        $maxnumberofitems=10; # 10 and 25. default 10
        // @todo activated for !all
        // $offset=0;  # offset (98) has to be a multiple of maxNumberOfItems (100)"

        $requestQueryParameters = [
            'offset' => $offset,
        ];

        $url = self::URLBASE;
        if (!$lat) {
            $url .= '/all';
        } else {
            $requestQueryParameters['language'] = $language;
            $requestQueryParameters['lat'] = $lat;
            $requestQueryParameters['lon'] = $lon;
            $requestQueryParameters['radius'] = $radius;
            $requestQueryParameters['maxnumberofitems'] = $maxnumberofitems;
        }

        $response = Http::timeout(30)->retry(3, 100)->get($url, $requestQueryParameters);
        // dump($response);
        // dump($response->json());
        // dump($response->json()["wijkagenten"]);
        return $response->json();
        // return $response->json()["wijkagenten"];
    }

    public function queryAll()
    {
        // ini_set('max_execution_time', '3000');

        $dir = Carbon::now()->format('Ymd');
        $filename = $dir . '/wijkagenten' . Carbon::now()->format('Hisu') . '.json';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        try {
            $locations = Locaty::all();
            // dump($locations);
            // exit();
            // $wijkagenten = [];
            foreach($locations as $location) {

                $wijkagenten = [];
                $offset=0;
                while (true) {
                    $response = $this->query($offset, $location->latitude, $location->longitude, 25.0);
                    // @todo fast escape
                    if (isset($response["wijkagenten"])) {
                        $wijkagenten = array_merge($wijkagenten, $response["wijkagenten"]);
                        if ($response["iterator"]["last"]) {
                            break;
                        } else {
                            $offset += 10;
                        }
                    } else {
                        break;
                    }
                }
                $wijkagenten['error'] = 'NOTIMEOUT';
                $filename = 'wijkagenten' . Carbon::now()->format('YmdHisu') . '.json';
                file_put_contents($filename, json_encode($wijkagenten));
            }
            // $wijkagenten['error'] = 'NOTIMEOUT';
            // $filename = 'wijkagenten' . Carbon::now()->format('YmdHisu') . '.json';
            // file_put_contents($filename, json_encode($wijkagenten));
        } catch (Exception $e) {
            $wijkagenten['error'] = 'TIMEOUT';
            $filename = 'wijkagenten' . Carbon::now()->format('YmdHisu') . '.json';
            file_put_contents($filename, json_encode($wijkagenten));
        }
    }
}
