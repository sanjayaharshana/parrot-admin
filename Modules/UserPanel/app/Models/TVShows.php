<?php

namespace Modules\UserPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\UserPanel\Database\Factories\TVShowsFactory;

class TVShows extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): TVShowsFactory
    // {
    //     // return TVShowsFactory::new();
    // }
}
