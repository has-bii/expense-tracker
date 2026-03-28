<?php

namespace App\Helpers;

use App\Enums\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class QueryPagination
{
    public static function getQueries(Request $request): array
    {
        $defaults = [
            'cursor' => null,
            'limit'  => 10,
            'sort_by' => 'created_at',
            'order' => 'desc'
        ];

        $input = array_merge($defaults, $request->query());

        $validator = Validator::make($input, [
            'cursor' => ['nullable', 'string'],
            'limit'  => ['integer', 'min:10', 'max:100'],
            'sort_by' => ['nullable', 'string'],
            'order' => ['nullable', new Enum(Order::class)]
        ]);

        return $validator->fails() ? $defaults : $validator->validated();
    }
}
