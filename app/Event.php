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
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
    public function users_accepted()
    {
        return $this->belongsToMany('App\User')->where('is_accepted', '=', 1);
    }
    public function users_pending()
    {
        return $this->belongsToMany('App\User')->where('is_accepted', '=', 0);
    }
}
