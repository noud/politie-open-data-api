<?php

namespace Politie\Http\Controllers;

use App\Http\Controllers\Controller;
use Politie\Jobs\ProcessWijkagenten;

class WijkagentenController extends Controller
{
    public function all()
    {
        ProcessWijkagenten::dispatchAfterResponse();
        echo "Wijkagenten all";
    }
}
