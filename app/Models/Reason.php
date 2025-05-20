<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Reason extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function defects(): HasMany
    {
        return $this->hasMany(Defect::class);
    }
}
