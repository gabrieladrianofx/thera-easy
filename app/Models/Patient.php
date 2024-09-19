<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    // use HasFactory;

    /** @return HasMany<Address> */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'belongs_to_patient');
    }
}
