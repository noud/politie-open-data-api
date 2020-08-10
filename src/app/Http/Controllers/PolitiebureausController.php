<?php

namespace Politie\Http\Controllers;

use App\Http\Controllers\Controller;
use Politie\Services\PolitiebureausService;
use Illuminate\Http\Request;

class PolitiebureausController extends Controller
{
    private $service;

    public function __construct(PolitiebureausService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $resultText = self::RESULT_TEXT;
        return view('politiebureaus.politiebureaus', ['politiebureaus' => null, 'resultText' => $resultText]);
    }

    public function query(Request $request)
    {
        $geoquery = $request->geoquery;
        $distance = $request->distance;

        $politiebureaus = $this->service->queryWeb(0, $geoquery, $distance);

        $resultText = self::RESULT_TEXT;
        $politiebureausCount = count($politiebureaus);
        if (1 ===$politiebureausCount) {
            $resultText = '1 politiebureau gevonden';
        } else if (1 < $politiebureausCount) {
            $resultText = $politiebureausCount .' politiebureaus gevonden';
        }

        return view('politiebureaus.politiebureaus', ['politiebureaus' => $politiebureaus, 'resultText' => $resultText]);
    }

    public function all()
    {
        $this->service->queryAll();
        echo "Politiebureaus all";
    }

    // @todo temp

    public function toDb()
    {
        $this->service->toDb();
        echo "Politiebureaus toDb";
    }

    public function getPictures()
    {
        $this->service->getPictures();
        echo "Politiebureaus getPictures";
    }
}
