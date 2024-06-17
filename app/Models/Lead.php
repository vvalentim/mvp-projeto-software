<?php

namespace App\Models;

use App\Enums\LeadStatus;
use App\Models\Contracts\IsSearchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model implements IsSearchable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'real_estate_id',
        'user_id'
    ];

    protected function casts()
    {
        return [
            'status' => LeadStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function estate(): BelongsTo
    {
        return $this->belongsTo(
            RealEstate::class,
            foreignKey: 'real_estate_id',
            ownerKey: 'id'
        );
    }

    public function getSearchLabel(): string
    {
        $label = $this->name;
        $label .= ' - ' . $this->email;

        return $label;
    }
}
