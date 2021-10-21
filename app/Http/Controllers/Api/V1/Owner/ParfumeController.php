<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParfumeResource;
use App\Models\Laundry;
use App\Models\Parfume;
use Illuminate\Validation\ValidationException;

class ParfumeController extends Controller
{
    public function index(Laundry $laundry)
    {
        throw_if(
            !auth()->user()->tokenCan('parfume.show')
                || auth()->id() != $laundry->user_id,
            ValidationException::withMessages(['parfume' => 'Tidak dapat melihat parfum!'])
        );

        $parfumes = Parfume::query()
            ->whereBelongsTo($laundry)
            ->get();

        return ParfumeResource::collection($parfumes);
    }

    public function store(Laundry $laundry)
    {
        throw_if(
            !auth()->user()->tokenCan('parfume.create')
                || auth()->id() != $laundry->user_id
                || is_null(request('name')),
            ValidationException::withMessages(['parfume' => 'Tidak dapat membuat parfum!'])
        );

        $parfume = $laundry->parfumes()->create(
            ['name' => request('name')]
        );

        return ParfumeResource::make($parfume);
    }

    public function update(Laundry $laundry, Parfume $parfume)
    {
        throw_if(
            !auth()->user()->tokenCan('parfume.update')
                || auth()->id() != $laundry->user_id
                || $parfume->laundry_id != $laundry->id
                || is_null(request('name')),
            ValidationException::withMessages(['parfume' => 'Tidak dapat mengubah parfum!'])
        );

        $parfume->update(['name' => request('name')]);

        return ParfumeResource::make($parfume);
    }

    public function destroy(Laundry $laundry, Parfume $parfume)
    {
        throw_if(
            !auth()->user()->tokenCan('parfume.delete')
                || auth()->id() != $laundry->user_id
                || $parfume->laundry_id != $laundry->id,
            ValidationException::withMessages(['parfume' => 'Tidak dapat menghapus parfum!'])
        );

        $parfume->delete();
    }
}
