<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends \Spatie\Tags\Tag
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'integer',
        'type' => 'integer',
        'order_column' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
