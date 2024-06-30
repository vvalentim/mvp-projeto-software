<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstateOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
    ];

    public function estate(): BelongsTo
    {
        return $this->belongsTo(RealEstate::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
