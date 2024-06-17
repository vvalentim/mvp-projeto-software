<?php

namespace App\Models;

use App\Enums\FollowUpStatus;
use App\Models\Contracts\IsKanbanRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class FollowUp extends Model implements Sortable, IsKanbanRecord
{
    use HasFactory, SortableTrait;

    protected $fillable = [
        'status',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'real_estate_id',
        'customer_id',
        'user_id',
    ];

    protected function casts()
    {
        return [
            'status' => FollowUpStatus::class,
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getKanbanRecordTitle(): string
    {
        if ($this->customer_id) {
            return $this->customer->person->name;
        }

        return $this->name;
    }

    public function getKanbanRecordContent(): string
    {
        if ($this->customer_id) {
            return $this->customer->person->phone_1;
        }

        return $this->phone;
    }
}
