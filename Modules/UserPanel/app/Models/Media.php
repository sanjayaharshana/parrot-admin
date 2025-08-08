<?php

namespace Modules\UserPanel\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'filename',
        'original_name',
        'disk',
        'path',
        'url',
        'mime',
        'size',
        'width',
        'height',
        'user_id',
    ];
}


