<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Liste extends Model
{
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
    protected $fillable = [
        'title',
    ];
}
