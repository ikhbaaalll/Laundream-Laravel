<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Laundry;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    public function index(Laundry $laundry)
    {
        throw_if(
            !auth()->user()->tokenCan('service.show')
                || auth()->id() != $laundry->user_id,
            ValidationException::withMessages(['service' => 'Tidak dapat melihat layanan!'])
        );

        $service = Service::query()
            ->whereBelongsTo($laundry)
            ->get();

        return ServiceResource::collection($service);
    }

    public function store(Laundry $laundry)
    {
        throw_if(
            !auth()->user()->tokenCan('service.create')
                || auth()->id() != $laundry->user_id
                || is_null(request('name')),
            ValidationException::withMessages(['service' => 'Tidak dapat membuat layanan!'])
        );

        $service = $laundry->services()->create(
            [
                'name' => request('name')
            ]
        );

        return ServiceResource::make($service);
    }

    public function update(Laundry $laundry, Service $service)
    {
        throw_if(
            !auth()->user()->tokenCan('service.update')
                || auth()->id() != $laundry->user_id
                || $service->laundry_id != $laundry->id
                || is_null(request('name')),
            ValidationException::withMessages(['service' => 'Tidak dapat mengubah layanan!'])
        );

        $service->update(['name' => request('name')]);

        return ServiceResource::make($service);
    }

    public function destroy(Laundry $laundry, Service $service)
    {
        throw_if(
            !auth()->user()->tokenCan('service.delete')
                || auth()->id() != $laundry->user_id
                || $service->laundry_id != $laundry->id,
            ValidationException::withMessages(['service' => 'Tidak dapat menghapus layanan!'])
        );

        $service->delete();
    }
}
