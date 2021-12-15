<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'laundry' => LaundryResource::make($this->whenLoaded('laundry')),
            'catalog' => CatalogResource::make($this->whenLoaded('catalog')),
            'parfume' => ParfumeResource::make($this->whenLoaded('parfume')),
            'serial' => $this->serial,
            'amount' => $this->amount,
            'delivery_fee' => $this->delivery_fee,
            'distance' => $this->distance ?: '0',
            'service_type' => $this->service_type,
            'service_type' => $this->delivery_type,
            'address' => $this->address,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'additional_information_user' => $this->additional_information_user,
            'additional_information_laundry' => $this->additional_information_laundry
        ];
    }
}
