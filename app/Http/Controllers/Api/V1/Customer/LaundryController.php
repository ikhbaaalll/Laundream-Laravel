<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\LaundryStoreRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Laundry;
use App\Models\Transaction;
use Illuminate\Http\Request;

class LaundryController extends Controller
{
    public function store(LaundryStoreRequest $laundryStoreRequest, Laundry $laundry)
    {
        $transaction = Transaction::query()
            ->whereBelongsTo($laundry)
            ->whereDate('created_at', today()->setTimezone('Asia/Jakarta')->toDateTimeString())
            ->get();

        $now = now()->setTimezone('Asia/Jakarta');

        $transaction = Transaction::create(array_merge($laundryStoreRequest->validated(), [
            'laundry_id' => $laundry->id,
            'user_id' => auth()->id(),
            'serial' => "TRX/" . $now->year . $now->month . $now->day . "/" . str_pad($transaction->count(), 3, 0, STR_PAD_LEFT)
        ]));

        return TransactionResource::make($transaction);
    }
}
