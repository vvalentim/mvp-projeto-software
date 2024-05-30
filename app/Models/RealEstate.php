<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class RealEstate extends Model
{
    use HasFactory;

    public function getSearchLabel(): string
    {
        $label = $this->title . ", ";
        $label .= $this->address_city . ", ";
        $label .= Str::substrReplace($this->zip_code, '-', 5, 0);

        return $label;
    }

    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, table: 'estate_owner');
    }
}
