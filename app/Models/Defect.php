<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Defect extends Model
{
        protected $fillable = [
        'date',
        'department_id',
        'product_type',
        'description',
        'reason_id',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function reason(): BelongsTo
    {
        return $this->belongsTo(Reason::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
