<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Http\Controllers\Controller;
use App\Models\Laundry;
use App\Models\OperationalHour;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OperationalHourController extends Controller
{
    public function index(Laundry $laundry)
    {
        throw_if(
            auth()->id() != $laundry->user_id || !auth()->user()->tokenCan('ophour.list'),
            ValidationException::withMessages(['op_hour' => 'Tidak dapat melihat jam operasional!'])
        );

        $senin = OperationalHour::query()
            ->whereBelongsTo($laundry)
            ->where('day', 'Senin')->first();

        $sabtu = OperationalHour::query()
            ->whereBelongsTo($laundry)
            ->where('day', 'Sabtu')->first();

        return response()->json([
            'senin' => $senin,
            'sabtu' => $sabtu
        ]);
    }
}
