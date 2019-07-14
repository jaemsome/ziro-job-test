<?php

namespace App;

use App\TaskStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Allow mass assignment
    protected $fillable = ['name', 'status'];

    /**
     * Access to be appended on model's attributes
     * 
     * @var array
     */
    protected $appends = ['owner'];

    public $owner;
    
    // Accessor for status format
    public function getStatusAttribute($value)
    {
        $status = TaskStatus::find($value);

        return $status ? ucwords($status->name) : 'Inactive';
    }

    // // Mutator for status format
    // public function setStatusAttribute($value)
    // {
    //     $status = TaskStatus::whereName(strtolower($value))->first();
    //     // Default to pending
    //     return $status ? $status->id : 1;
    // }
    
    // Accessor for archived format
    public function getArchivedAttribute($value)
    {
        return $value ? 'Yes' : 'No';
    }

    // // Mutator for archived format
    // public function setArchivedAttribute($value)
    // {
    //     return strtolower($value) == 'yes' ? 1 : 0;
    // }

    // Accessor for created_at human readable date
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->diffForHumans() : '';
    }

    // Accessor for created_at human readable date
    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->diffForHumans() : '';
    }

    // Accessor for owner appended attribute
    public function getOwnerAttribute()
    {
        return $this->pivot ? User::find($this->pivot->user_id) : $this->owner;
    }

    /**
     * Find status of a task.
     * 
     * @return \App\TaskStatus
     */
    public function status()
    {
        return $this->belongsTo('App\TaskStatus', 'status');
    }

    /**
     * Find all users assigned to a task.
     * 
     * @return \App\User
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
