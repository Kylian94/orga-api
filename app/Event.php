<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'isPrivate', 'adresse', 'zipcode', 'city', 'banner',
    ];

    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
