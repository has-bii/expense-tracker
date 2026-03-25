<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasUuids;

    protected $fillable = ['amount', 'source', 'description', 'income_date'];
}
