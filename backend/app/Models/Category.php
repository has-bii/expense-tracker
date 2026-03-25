<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'icon', 'color', 'is_default', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
