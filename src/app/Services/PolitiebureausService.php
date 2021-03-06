<?php

namespace Politie\Services;

use App\Models\Adres;
use App\Models\Afbeelding;
use App\Models\Locatie;
use App\Models\Politiebureau;
use App\Models\PolitiebureausLocatie;
use Politie\Services\GeoService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PolitiebureausService
{
    // const URLBASE='https://api.politie.nl/politiebureaus/v1';
    const URLBASE='https://api.politie.nl/v4/politiebureaus';

    private $geoService;

    public function __construct(GeoService $geoService)
    {
        $this->geoService = $geoService;
    }

    public function query(int $offset = 0, float $lat = null, float $lon = null, float $radius = null)
    {
        // @todo validate params

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
            $requestQueryParameters['lat'] = $lat;
            $requestQueryParameters['lon'] = $lon;
            $requestQueryParameters['radius'] = $radius;
            $requestQueryParameters['maxnumberofitems'] = $maxnumberofitems;
        }

        $response = Http::get($url, $requestQueryParameters);
        // dump($response);
        // dump($response->json());
        // dump($response->json()["politiebureaus"]);
        return $response->json();
        // return $response->json()["politiebureaus"];
    }

    public function queryWeb(int $offset = 0, string $geoquery, float $distance)
    {
        $geoLocation = $this->geoService->query($geoquery);
        // return $this->query($offset, $geoLocation['lat'], $geoLocation['lon'], $distance);
        // return ($this->query($offset, $geoLocation['lat'], $geoLocation['lon'], $distance));
        return ($this->query($offset, $geoLocation['lat'], $geoLocation['lon'], $distance))["politiebureaus"];
    }

    public function queryAll()
    {
        $politiebureaus = [];
        $offset=0;
        while (true) {
            $response = $this->query($offset);
            // @todo fast escape
            if (isset($response["politiebureaus"])) {
                $politiebureaus = array_merge($politiebureaus, $response["politiebureaus"]);
                if ($response["iterator"]["last"]) {
                    break;
                } else {
                    $offset += 100;
                }
            } else {
                break;
            }
        }
        $filename = 'politiebureaus.json';
        file_put_contents($filename, json_encode($politiebureaus));
    }

    // @todo temp
    public function toDb()
    {
        $filename = 'politiebureaus.json';
        $file = file_get_contents($filename, true);
        $politiebureaus = json_decode($file);

        foreach($politiebureaus as $politiebureauStdClass) {
            $politiebureauArray = (array) $politiebureauStdClass;

            $politiebureauStdClass->postadres->postcode = str_replace(" ", "", $politiebureauStdClass->postadres->postcode);
            $postadresArray = (array) $politiebureauStdClass->postadres;
            if (!empty(array_filter($postadresArray))) {
                $postadres = Adres::firstOrCreate($postadresArray);
                $postadres->save();
                $politiebureauArray['postadres_id'] = $postadres->id;
           }

            $politiebureauStdClass->bezoekadres->postcode = str_replace(" ", "", $politiebureauStdClass->bezoekadres->postcode);
            $bezoekadresArray = (array) $politiebureauStdClass->bezoekadres;
            if (!empty(array_filter($bezoekadresArray))) {
                $bezoekadres = Adres::firstOrCreate($bezoekadresArray);
                $bezoekadres->save();
                $politiebureauArray['bezoekadres_id'] = $bezoekadres->id;
            }

            $afbeeldingArray = (array) $politiebureauStdClass->afbeelding;
            if (!empty(array_filter($afbeeldingArray))) {
                $afbeelding = Afbeelding::firstOrCreate($afbeeldingArray);
                $afbeelding->save();
                $politiebureauArray['afbeelding_id'] = $afbeelding->id;
            }

            unset($politiebureauArray['postadres']);
            unset($politiebureauArray['bezoekadres']);
            unset($politiebureauArray['afbeelding']);
            $politiebureauStdClassLocaties = $politiebureauStdClass->locaties;
            unset($politiebureauArray['locaties']);

            $politiebureau = Politiebureau::firstOrCreate($politiebureauArray);
            $politiebureau->save();

            foreach($politiebureauStdClassLocaties as $locatieStdClass) {
                $location = Locatie::firstOrCreate((array) $locatieStdClass);
                $location->save();
                var_dump($politiebureau);
                var_dump($location);
                PolitiebureausLocatie::create([
                    'politiebureaus_id' => $politiebureau->uid,
                    'locaties_id' => $location->id
                ]);
            }
        }
    }

    public function getPictures()
    {
        $base =  '/';

        $images = Afbeelding::all();

        foreach($images as $image)
        {
            if ($image->url) {
                $file = file_get_contents($image->url, true);
                $filename = str_replace("https://www.politie.nl/", "", $image->url);
                $path = explode('/',$filename);
                array_pop($path);
                $path = implode('/',$path);
                Storage::makeDirectory($base . $path);
                $filename = $base . $filename;
                Storage::disk('local')->put($filename, $file);
            }         
        }
   }
}
