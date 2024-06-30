<?php

namespace App\Models;

use App\Enums\MaritalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customer extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'marital_status' => MaritalStatus::class,
        ];
    }

    public function estates(): BelongsToMany
    {
        return $this->belongsToMany(RealEstate::class, table: 'estate_owners');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
