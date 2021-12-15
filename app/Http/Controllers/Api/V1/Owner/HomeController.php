<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Laundry;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function __invoke(Laundry $laundry)
    {
        $confirmation = Transaction::query()
            ->whereBelongsTo($laundry)
            ->with(['laundry', 'catalog', 'parfume'])
            ->where('status', Transaction::STATUS_CONFIRM)
            ->get();

        $pickup = Transaction::query()
            ->whereBelongsTo($laundry)
            ->with(['laundry', 'catalog', 'parfume'])
            ->where('status', Transaction::STATUS_PICKUP)
            ->get();

        $queue = Transaction::query()
            ->whereBelongsTo($laundry)
            ->with(['laundry', 'catalog', 'parfume'])
            ->where('status', Transaction::STATUS_QUEUE)
            ->get();

        $process = Transaction::query()
            ->whereBelongsTo($laundry)
            ->with(['laundry', 'catalog', 'parfume'])
            ->where('status', Transaction::STATUS_PROCESS)
            ->get();

        $ready = Transaction::query()
            ->whereBelongsTo($laundry)
            ->with(['laundry', 'catalog', 'parfume'])
            ->where('status', Transaction::STATUS_READY)
            ->get();

        $deliver = Transaction::query()
            ->whereBelongsTo($laundry)
            ->with(['laundry', 'catalog', 'parfume'])
            ->where('status', Transaction::STATUS_DELIVER)
            ->get();

        $revenue = Transaction::query()
            ->whereBelongsTo($laundry)
            ->with(['laundry', 'catalog', 'parfume'])
            ->get();

        $revenue = $revenue->sum('amout') + $revenue->sum('delivery_fee');

        return response()->json([
            'confirmation' => TransactionResource::collection($confirmation),
            'pickup' => TransactionResource::collection($pickup),
            'queue' => TransactionResource::collection($queue),
            'process' => TransactionResource::collection($process),
            'ready' => TransactionResource::collection($ready),
            'deliver' => TransactionResource::collection($deliver),
            'revenue' => $revenue + 0
        ], Response::HTTP_OK);
    }
}
