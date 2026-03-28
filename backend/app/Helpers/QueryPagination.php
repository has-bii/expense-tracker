<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QueryPagination
{
    public static function getQueries(Request $request): array
    {
        $defaults = [
            'cursor' => null,
            'limit'  => 10,
        ];

        $input = array_merge($defaults, $request->query());

        $validator = Validator::make($input, [
            'cursor' => 'nullable|string',
            'limit'  => 'integer|min:10|max:100',
        ]);

        return $validator->fails() ? $defaults : $validator->validated();
    }
}
