<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function events()
    {
        return $this->hasMany('App\Event');
    }

    public function items()
    {
        return $this->belongsToMany('App\Item');
    }

    public function friends()
    {
        return $this->belongsToMany('App\User', 'friends', 'user_id', 'friend_id')
            ->wherePivot('is_accepted', '=', 0);
        //->withPivot('is_accepted');
    }
    function friendsOfMine()
    {
        return $this->belongsToMany('App\User', 'friends', 'user_id', 'friend_id')
            ->wherePivot('is_accepted', '=', 0);
        //->withPivot('is_accepted');
    }
    // friendship that I was invited to 
    function friendOf()
    {
        return $this->belongsToMany('App\User', 'friends', 'friend_id', 'user_id')
            ->wherePivot('is_accepted', '=', 0);
        //->withPivot('is_accepted');
    }
    // accessor allowing you call $user->friends
    public function getFriendsAttribute()
    {
        if (!array_key_exists('friends', $this->relations)) $this->loadFriends();
        return $this->getRelation('friends');
    }
    protected function mergeFriends()
    {
        return $this->friendsOfMine->merge($this->friendOf);
    }
    protected function loadFriends()
    {
        if (!array_key_exists('friends', $this->relations)) {
            $friends = $this->mergeFriends();
            $this->setRelation('friends', $friends);
        }
    }




    public function friendsAccepted()
    {
        return $this->belongsToMany('App\User', 'friends', 'user_id', 'friend_id')
            ->wherePivot('is_accepted', '=', 1);
        //->withPivot('is_accepted');
    }

    function friendsOfMineAccepted()
    {
        return $this->belongsToMany('App\User', 'friends', 'user_id', 'friend_id')
            ->wherePivot('is_accepted', '=', 1);
        //->withPivot('is_accepted');
    }
    // friendship that I was invited to 
    function friendOfAccepted()
    {
        return $this->belongsToMany('App\User', 'friends', 'friend_id', 'user_id')
            ->wherePivot('is_accepted', '=', 1);
        //->withPivot('is_accepted');
    }

    public function getFriendsAttributeAccepted()
    {
        if (!array_key_exists('friendsAccepted', $this->relations)) $this->loadFriendsAccepted();
        return $this->getRelation('friendsAccepted');
    }
    protected function loadFriendsAccepted()
    {
        if (!array_key_exists('friendsAccepted', $this->relations)) {
            $friendsAccepted = $this->mergeFriendsAccepted();
            $this->setRelation('friendsAccepted', $friendsAccepted);
        }
    }
    protected function mergeFriendsAccepted()
    {
        return $this->friendsOfMineAccepted->merge($this->friendOfAccepted);
    }
}
