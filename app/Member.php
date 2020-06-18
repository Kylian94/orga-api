<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public function events()
    {
        return $this->belongsTo('App\Event');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
