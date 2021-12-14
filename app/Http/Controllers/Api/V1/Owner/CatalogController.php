<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\CatalogStoreRequest;
use App\Http\Resources\CatalogResource;
use App\Models\Catalog;
use App\Models\Laundry;
use Illuminate\Validation\ValidationException;

class CatalogController extends Controller
{
    public function index(Laundry $laundry)
    {
        throw_if(
            !auth()->user()->tokenCan('catalog.show')
                || auth()->id() != $laundry->user_id,
            ValidationException::withMessages(['catalog' => 'Tidak dapat melihat varian!'])
        );

        $catalogs = Catalog::query()
            ->whereBelongsTo($laundry)
            ->get();

        return CatalogResource::collection($catalogs);
    }

    public function store(CatalogStoreRequest $catalogStoreRequest, Laundry $laundry)
    {
        throw_if(
            auth()->id() != $laundry->user_id,
            ValidationException::withMessages(['catalog' => 'Tidak dapat membuat varian!'])
        );

        $catalog = $laundry->catalogs()
            ->create($catalogStoreRequest->validated());

        return CatalogResource::make($catalog);
    }

    public function update(CatalogStoreRequest $catalogStoreRequest, Laundry $laundry, Catalog $catalog)
    {
        throw_if(
            auth()->id() != $laundry->user_id,
            ValidationException::withMessages(['catalog' => 'Tidak dapat mengubah varian!'])
        );

        $catalog = $catalog->update($catalogStoreRequest->validated());

        return CatalogResource::make($catalog);
    }

    public function destroy(Laundry $laundry, Catalog $catalog)
    {
        throw_if(
            !auth()->user()->tokenCan('catalog.destroy')
                || auth()->id() != $laundry->user_id
                || $laundry->id == $catalog->laundry_id,
            ValidationException::withMessages(['catalog' => 'Tidak dapat menghapus varian!'])
        );

        $catalog->delete();
    }
}
