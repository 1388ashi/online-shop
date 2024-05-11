<?php

namespace Modules\Area\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Area\Entities\Province;

class CityController extends Controller
{
    public function index(Province $province)
    {
        $cities = Cache::rememberForever('cities_' . $province->id, function () use ($province) {
            return $province->cities()
                ->select(['id', 'name'])
                ->orderBy('name', 'asc')
                ->get();
        });

        return response()->success('Get all province cities #' . $province->id, compact('cities'));
    }
}
