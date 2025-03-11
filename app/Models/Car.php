<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'brand', 'price_per_day', 'availability_status'];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
