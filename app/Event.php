<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'isPrivate', 'adresse', 'zipCode', 'city', 'banner', 'date',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function listes()
    {
        return $this->hasMany('App\Liste');
    }
}
