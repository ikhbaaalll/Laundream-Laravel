<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\LaundryUpdateRequest;
use App\Http\Resources\LaundryResource;
use App\Models\Laundry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LaundryController extends Controller
{
    public function update(LaundryUpdateRequest $laundryUpdateRequest, Laundry $laundry)
    {
        throw_if(
            auth()->id() != $laundry->user_id,
            ValidationException::withMessages(['laundry' => 'Tidak dapat mengubah laundry!'])
        );

        $laundry->update([
            'name'      => $laundryUpdateRequest->name,
            'lat'       => $laundryUpdateRequest->lat,
            'lng'       => $laundryUpdateRequest->lng,
            'address'   => $laundryUpdateRequest->address,
            'province'  => $laundryUpdateRequest->province,
            'city'      => $laundryUpdateRequest->city,
            'phone'     => $laundryUpdateRequest->phone,
        ]);

        if ($laundryUpdateRequest->hasFile('banner')) {
            $path = $laundryUpdateRequest->file('banner')->store('image', 's3');

            $laundry->update([
                'banner' => $path
            ]);
        }

        return $laundry;

        return LaundryResource::make($laundry);
    }
}
