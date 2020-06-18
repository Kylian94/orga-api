<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
