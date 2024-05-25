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

    protected $fillable = [
        'status',
        'lead_id',
        'broker_id',
    ];

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

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function getKanbanRecordTitle(): string
    {
        return $this->lead->name;
    }
}
