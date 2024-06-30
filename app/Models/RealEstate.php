<?php

namespace App\Models;

use App\Casts\LocaleDecimal;
use App\Casts\LocaleZipCode;
use App\Enums\RealEstateTypes;
use App\Models\Contracts\IsSearchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RealEstate extends Model implements IsSearchable
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'type' => RealEstateTypes::class,
        'price' => LocaleDecimal::class,
        'tax_iptu' => LocaleDecimal::class,
        'tax_condominium' => LocaleDecimal::class,
        'area_total' => LocaleDecimal::class,
        'area_built' => LocaleDecimal::class,
        'zip_code' => LocaleZipCode::class,
    ];

    public function estateOwners(): HasMany
    {
        return $this->hasMany(EstateOwner::class);
    }

    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, table: 'estate_owners')->withTimestamps();
    }

    public function getSearchLabel(): string
    {
        $label = $this->title . ", ";
        $label .= $this->address_city . ", ";
        $label .= $this->zip_code;

        return $label;
    }
}
