<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\LaundryResource;
use App\Models\Laundry;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $laundries = Laundry::query()
            ->with(['catalogs', 'parfumes', 'operationalHour', 'shippingRates'])
            ->where('status', Laundry::STATUS_ACTIVE)
            ->nearestTo(request('lat'), request('lng'))
            ->take(5);

        return LaundryResource::collection($laundries);
    }
}
