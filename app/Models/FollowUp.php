<?php

namespace App\Models;

use App\Enums\FollowUpStatus;
use App\Models\Traits\IsKanbanRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class FollowUp extends Model implements Sortable
{
    use HasFactory, IsKanbanRecord, SortableTrait;

    protected function casts()
    {
        return [
            'status' => FollowUpStatus::class,
        ];
    }

    public function broker(): BelongsTo
    {
        return $this->belongsTo(User::class, foreignKey: 'broker_id');
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
        return $this->name;
    }

    public function getKanbanRecordContent(): string
    {
        return $this->phone;
    }
}
