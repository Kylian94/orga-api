<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    protected $fillable = [
        'title',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
    public function liste()
    {
        return $this->belongsTo('App\Liste');
    }
}
