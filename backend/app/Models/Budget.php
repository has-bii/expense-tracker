<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    use HasUuids;

    protected $fillable = ['user_id', 'category_id', 'limit_amount', 'period', 'start_date'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
