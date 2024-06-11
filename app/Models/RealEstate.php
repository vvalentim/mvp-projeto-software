<?php

namespace App\Models;

use App\Models\Traits\IsSearchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class RealEstate extends Model
{
    use HasFactory, IsSearchable;

    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, table: 'estate_owner');
    }

    protected function formatCep(string $cep)
    {
        return Str::substrReplace($cep, '-', 5, 0);
    }

    protected function zipCode(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->formatCep($value),
            set: fn (string $value) => preg_replace('/\D/', '', $value)
        );
    }

    public function getSearchLabel(): string
    {
        $label = $this->title . ", ";
        $label .= $this->address_city . ", ";
        $label .= $this->zip_code;

        return $label;
    }
}
