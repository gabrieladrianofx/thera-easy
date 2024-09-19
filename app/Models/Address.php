<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    // use HasFactory;

    /** @return BelongsTo<Patient, Address> */
    public function belongsToPatient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'belongs_to_patient');
    }
}
