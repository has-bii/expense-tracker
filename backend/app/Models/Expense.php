<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasUuids;

    protected $fillable = ['user_id', 'category_id', 'amount', 'description', 'expense_date'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
