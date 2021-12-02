<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\ShippingRateStoreRequest;
use App\Http\Resources\ShippingRateResource;
use App\Models\Laundry;
use App\Models\ShippingRate;
use Illuminate\Validation\ValidationException;

class ShippingRateController extends Controller
{
    public function index(Laundry $laundry)
    {
        throw_if(
            !auth()->user()->tokenCan('shipping.show')
                || auth()->id() != $laundry->user_id,
            ValidationException::withMessages(['shipping_rate' => 'Tidak dapat melihat tarif ongkir!'])
        );

        $shippingRates = ShippingRate::query()
            ->whereBelongsTo($laundry)
            ->get();

        return ShippingRateResource::collection($shippingRates);
    }

    public function store(ShippingRateStoreRequest $shippingRateStoreRequest, Laundry $laundry)
    {
        throw_if(
            !auth()->user()->tokenCan('shipping.create')
                || auth()->id() != $laundry->user_id,
            ValidationException::withMessages(['shipping_rate' => 'Tidak dapat membuat tarif ongkir!'])
        );

        $shippingRate = $laundry->shippingRates()
            ->create($shippingRateStoreRequest->validated());

        return ShippingRateResource::make($shippingRate);
    }

    public function destroy(Laundry $laundry, ShippingRate $shippingRate)
    {
        throw_if(
            !auth()->user()->tokenCan('shipping.delete')
                || auth()->id() != $laundry->user_id
                || $shippingRate->laundry_id != $laundry->id,
            ValidationException::withMessages(['shipping_rate' => 'Tidak dapat menghapus tarif ongkir!'])
        );

        $shippingRate->delete();
    }
}
