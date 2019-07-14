<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    /**
     * Access for user_id pivot table
     */
    public function getUserIdAttribute($value)
    {
        return $value ? User::find($this->pivot->user_id)->name : '';
    }

    /**
     * Find all tasks that belongs to a user.
     */
    public function tasks()
    {
        return $this->belongsToMany('App\Task')->withPivot('user_id')->orderBy('id', 'desc');
    }
}
