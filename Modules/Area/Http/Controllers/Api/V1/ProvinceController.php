<?php

namespace Modules\Area\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Area\Entities\Province;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Cache::rememberForever('all_provinces', function () {
            return Province::query()
                ->select(['id', 'name'])
                ->orderBy('name', 'asc')
                ->get();
        });

        return response()->success('Get all provinces', compact('provinces'));
    }
}
