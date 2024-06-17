<?php

namespace App\Models;

use App\Models\Contracts\IsSearchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model implements IsSearchable
{
    use HasFactory;

    protected function formatCpf(string $cpf)
    {
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }

    protected function formatCnpj(string $cnpj)
    {
        return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
    }

    protected function numRegistry(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                if ($this->attributes['type'] === 'F') {
                    return $this->formatCpf($value);
                }

                return $this->formatCnpj($value);
            },
            set: fn (string $value) => preg_replace('/\D/', '', $value),
        );
    }

    public function getSearchLabel(): string
    {
        $label = $this->name;
        $label .= ' - ' . $this->num_registry;

        return $label;
    }
}
