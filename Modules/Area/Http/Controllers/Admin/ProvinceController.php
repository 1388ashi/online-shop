<?php

namespace Modules\Area\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Area\Entities\Province;

class ProvinceController extends Controller
{
    public function index(): JsonResponse
    {
        $provinces = Province::query()
            ->orderBy('name', 'asc')
            ->paginate(15);
        
        return response()->success('',compact('provinces'));
    }
}
