<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laundry extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    protected $fillable = [
        'user_id',
        'name',
        'banner',
        'address',
        'province',
        'city',
        'phone',
        'lat',
        'lng',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function parfumes(): HasMany
    {
        return $this->hasMany(Parfume::class);
    }

    public function operationalHour(): HasMany
    {
        return $this->hasMany(OperationalHour::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
