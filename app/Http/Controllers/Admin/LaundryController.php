<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LaundryStoreRequest;
use App\Models\Laundry;
use App\Models\User;
use Illuminate\Http\Request;

class LaundryController extends Controller
{
    public function index()
    {
        $laundries = Laundry::with('user')
            ->withCount('employees')
            ->get();

        return view('pages.laundry.index', compact('laundries'));
    }

    public function create()
    {
        return view('pages.laundry.create');
    }

    public function store(LaundryStoreRequest $laundryStoreRequest)
    {
        $user = User::create([
            'name' => $laundryStoreRequest->name,
            'email' => $laundryStoreRequest->email,
            'password' => bcrypt($laundryStoreRequest->password),
            'no_hp' => $laundryStoreRequest->no_hp,
            'role' => User::ROLE_OWNER
        ]);

        $user->laundry()->create([
            'name' => $laundryStoreRequest->laundry_name,
            'status' => Laundry::STATUS_ACTIVE
        ]);

        return redirect()->route('admin.laundries.index');
    }

    public function status(Request $request, Laundry $laundry)
    {
        $laundry->update(['status' => $request->status]);

        return redirect()->route('admin.laundries.index');
    }
}
