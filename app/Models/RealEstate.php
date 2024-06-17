<?php

namespace App\Models;

use App\Models\Contracts\IsSearchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use NumberFormatter;

class RealEstate extends Model implements IsSearchable
{
    use HasFactory;

    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, table: 'estate_owner');
    }

    protected function formatCep(string $cep)
    {
        return Str::substrReplace($cep, '-', 5, 0);
    }

    protected function formatCurrency(string $price)
    {

        $format = numfmt_create('pt_BR', NumberFormatter::DECIMAL);
        $format->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);

        return numfmt_format_currency($format, $price, 'BRL');
    }

    protected function zipCode(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->formatCep($value),
            set: fn (string $value) => preg_replace('/\D/', '', $value)
        );
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->formatCurrency($value),
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
