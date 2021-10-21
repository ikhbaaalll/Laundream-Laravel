<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Catalog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'service_id',
        'name',
        'icon',
        'unit',
        'price',
        'estimation_complete',
        'estimation_type'
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
