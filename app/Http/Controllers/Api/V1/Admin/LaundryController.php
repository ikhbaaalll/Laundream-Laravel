<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LaundryStoreRequest as AdminLaundryStoreRequest;
use App\Http\Requests\Admin\LaundryUpdateRequest as AdminLaundryUpdateRequest;
use App\Http\Resources\LaundryResource;
use App\Models\Laundry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LaundryController extends Controller
{
    public function index()
    {
        $laundries = Laundry::with('user')->get();

        return LaundryResource::collection($laundries);
    }

    public function store(AdminLaundryStoreRequest $laundryStoreRequest)
    {
        $laundry = DB::transaction(function () use ($laundryStoreRequest) {
            $user = User::create(
                [
                    'name' => $laundryStoreRequest->name,
                    'email' => $laundryStoreRequest->email,
                    'password' => bcrypt($laundryStoreRequest->password),
                    'role' => User::ROLE_OWNER
                ]
            );

            return Laundry::create(
                [
                    'user_id' => $user->id,
                    'name' => $laundryStoreRequest->laundry,
                    'status' => Laundry::STATUS_ACTIVE
                ]
            );
        });

        return LaundryResource::make($laundry->load('user'));
    }

    public function show(Laundry $laundry)
    {
        abort_unless(auth()->user()->tokenCan('laundry.show'), Response::HTTP_FORBIDDEN);

        $laundry->load('user');

        return LaundryResource::make($laundry);
    }

    public function update(AdminLaundryUpdateRequest $laundryUpdateRequest, Laundry $laundry)
    {
        $laundry->update(
            [
                'status' => $laundryUpdateRequest->status
            ]
        );

        $laundry->load('user');

        return LaundryResource::make($laundry);
    }

    public function destroy(Laundry $laundry)
    {
        abort_unless(
            auth()->user()->tokenCan('laundry.delete'),
            Response::HTTP_FORBIDDEN
        );

        $laundry->delete();
    }
}
