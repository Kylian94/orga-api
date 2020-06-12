<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'isPrivate', 'adresse', 'zipCode', 'city', 'banner', 'date',
    ];

    public function post()
    {
        return $this->belongsTo('App\Post');
    }
    public function listes()
    {
        return $this->hasMany('App\Liste');
    }
}
